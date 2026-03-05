<?php

namespace Tests\Feature;

use App\Models\ExamSession;
use App\Models\ExamToken;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ExamFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function start_school_exam_requires_enough_questions()
    {
        $user = User::factory()->create();
        $subject = Subject::create(['name' => 'Mathematics']);

        // only 100 questions in this subject
        for ($i = 0; $i < 100; $i++) {
            Question::create([
                'subject_id' => $subject->id,
                'question_text' => "Question {$i}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
                'difficulty_level' => 'medium',
            ]);
        }

        Cache::put('school_questions_count', 180);

        $this->actingAs($user);

        $response = $this->post(route('exam.start'), ['subject_id' => $subject->id]);
        $response->assertSessionHasErrors('subject_id');
        $this->assertStringContainsString('Insufficient questions', session('errors')->first('subject_id'));
    }

    /** @test */
    public function plan_question_counts_override_cache_and_attempts_are_consumed()
    {
        $user = User::factory()->create();

        // create a plan with custom question counts
        $plan = \App\Models\Plan::create([
            'name' => 'Custom Plan',
            'price' => 50,
            'attempts_allowed' => 3,
            'duration_days' => 7,
            'has_explanations' => false,
            'has_leaderboard' => false,
            'has_streak' => false,
            'school_questions' => 50,
            'jamb_questions_per_subject' => 15,
            'jamb_english_questions' => 20,
        ]);

        // give the user a student plan record
        $record = \App\Models\StudentPlan::create([
            'student_id' => $user->id,
            'plan_id' => $plan->id,
            'attempts_remaining' => 3,
            'expires_at' => now()->addDays(10),
        ]);

        // create a subject for school exam
        $subject = Subject::create(['name' => 'Biology']);
        for ($i = 0; $i < 200; $i++) {
            Question::create([
                'subject_id' => $subject->id,
                'question_text' => "Q{$i}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
                'difficulty_level' => 'easy',
            ]);
        }

        // default cache is higher
        Cache::put('school_questions_count', 180);
        Cache::put('shuffle_questions', false);
        Cache::put('shuffle_options', false);

        $token = ExamToken::create([
            'code' => 'PLAN-USE1',
            'max_uses' => 1,
            'used_count' => 0,
            'is_active' => true,
            'created_by' => $user->id,
            'plan_id' => $plan->id,
        ]);

        $this->actingAs($user);

        // dashboard should show plan info and still 50 questions
        $dashboard = $this->get(route('student.dashboard'));
        $dashboard->assertSee('Active plan:');
        $dashboard->assertSee('Attempts remaining: 3');

        // start & confirm school exam
        $this->post(route('exam.start'), ['subject_id' => $subject->id]);
        $confirm = $this->post(route('exam.confirm.school'), [
            'subject_id' => $subject->id,
            'token_code' => 'PLAN-USE1',
        ]);
        $confirm->assertStatus(302);

        $session = ExamSession::latest()->first();
        $this->assertEquals(50, $session->total_questions);
        $this->assertCount(50, $session->question_ids);

        // record attempts should have decremented on the most recent plan row
        $latest = \App\Models\StudentPlan::where('student_id', $user->id)
                    ->where('plan_id', $plan->id)
                    ->latest()
                    ->first();
        $this->assertEquals(2, $latest->attempts_remaining);

        // token is plan-bound so should not decrement even though max_uses is 1
        $this->assertTrue($token->isValid());
        $token->use($user);
        $this->assertTrue($token->isValid(), 'plan token should still be valid despite crossing max_uses');
    }

    /** @test */
    public function jamb_exam_respects_plan_counts_and_includes_english()
    {
        $user = User::factory()->create();
        $plan = \App\Models\Plan::create([
            'name' => 'Jamb Plan',
            'price' => 30,
            'attempts_allowed' => 2,
            'duration_days' => 7,
            'has_explanations' => false,
            'has_leaderboard' => false,
            'has_streak' => false,
            'school_questions' => 40,
            'jamb_questions_per_subject' => 10,
            'jamb_english_questions' => 5,
        ]);
        $record = \App\Models\StudentPlan::create([
            'student_id' => $user->id,
            'plan_id' => $plan->id,
            'attempts_remaining' => 2,
            'expires_at' => now()->addDays(5),
        ]);

        $english = Subject::create(['name' => 'English']);
        $s1 = Subject::create(['name' => 'Maths']);
        $s2 = Subject::create(['name' => 'Physics']);
        $s3 = Subject::create(['name' => 'Chemistry']);

        foreach ([$english, $s1, $s2, $s3] as $subj) {
            for ($i = 0; $i < 50; $i++) {
                Question::create([
                    'subject_id' => $subj->id,
                    'question_text' => "Q{$i}",
                    'option_a' => 'A',
                    'option_b' => 'B',
                    'option_c' => 'C',
                    'option_d' => 'D',
                    'correct_option' => 'A',
                    'difficulty_level' => 'easy',
                ]);
            }
        }

        Cache::put('jamb_english_questions', 60); // default high
        Cache::put('jamb_questions_per_subject', 40);
        Cache::put('shuffle_questions', false);
        Cache::put('shuffle_options', false);

        $token = ExamToken::create([
            'code' => 'JAMB-PLN',
            'max_uses' => 2,
            'used_count' => 0,
            'is_active' => true,
            'created_by' => $user->id,
            'plan_id' => $plan->id,
        ]);

        $this->actingAs($user);
        // go through start -> confirm flow
        $start = $this->post(route('exam.start.jamb'), ['subject_ids' => [$s1->id, $s2->id, $s3->id]]);
        $start->assertStatus(200);
        $confirm = $this->post(route('exam.confirm.jamb'), [
            'subject_ids' => [$s1->id, $s2->id, $s3->id],
            'token_code' => 'JAMB-PLN',
        ]);
        $confirm->assertStatus(302);

        $session = ExamSession::latest()->first();
        $this->assertEquals(5 + 10*3, $session->total_questions);
        $this->assertCount(5 + 10*3, $session->question_ids);

        // english should be the first subject id stored
        $this->assertEquals($english->id, $session->subject_id);

        // due to token use creating a fresh plan record, ensure we look at
        // the latest entry for this user/plan when asserting remaining attempts
        $record = \App\Models\StudentPlan::where('student_id', $user->id)
                    ->where('plan_id', $plan->id)
                    ->latest()
                    ->first();
        $this->assertEquals(1, $record->attempts_remaining);
    }

    /** @test */
    public function start_jamb_requires_three_distinct_subjects()
    {
        $user = User::factory()->create();
        // create English subject so the controller can find it and supply enough
        $english = Subject::create(['name' => 'English']);
        // populate english question pool so insufficiency check does not fire
        for ($i = 0; $i < 60; $i++) {
            Question::create([
                'subject_id' => $english->id,
                'question_text' => "E{$i}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
                'difficulty_level' => 'easy',
            ]);
        }

        // choose two other subjects, then intentionally duplicate one
        $sub1 = Subject::create(['name' => 'Maths']);
        $sub2 = Subject::create(['name' => 'Physics']);

        $this->actingAs($user);

        // simulate selecting the same subject twice (and not including English)
        // satisfy minimum question counts so we hit the distinct validation
        for ($i = 0; $i < 50; $i++) {
            Question::create([
                'subject_id' => $sub1->id,
                'question_text' => "M{$i}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
                'difficulty_level' => 'easy',
            ]);
            Question::create([
                'subject_id' => $sub2->id,
                'question_text' => "P{$i}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
                'difficulty_level' => 'easy',
            ]);
        }
        $response = $this->post(route('exam.start.jamb'), [
            'subject_ids' => [$sub1->id, $sub1->id, $sub2->id],
        ]);

        $response->assertSessionHasErrors('subject_ids');
        $this->assertEquals('Please select three different subjects.', session('errors')->first('subject_ids'));
    }

    /** @test */
    public function plan_features_still_available_on_review_even_after_attempt_used()
    {
        // turn off Laravel's exception handling so we can see any underlying error
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $plan = Plan::create([
            'name' => 'Premium',
            'price' => 0,
            'attempts_allowed' => 1,
            'duration_days' => null,
            'has_explanations' => true,
            'has_leaderboard' => true,
            'has_streak' => false,
            'school_questions' => 10,
        ]);

        // give the user a token for the plan
        $token = ExamToken::create([
            'code' => 'PREM-001',
            'max_uses' => 1,
            'used_count' => 0,
            'is_active' => true,
            'created_by' => $user->id,
            'plan_id' => $plan->id,
        ]);

        $subject = Subject::create(['name' => 'Biology']);
        for ($i = 0; $i < 10; $i++) {
            Question::create([
                'subject_id' => $subject->id,
                'question_text' => "Q{$i}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
                'difficulty_level' => 'easy',
                'explanation' => 'Some explanation here',
            ]);
        }

        $this->actingAs($user);
        // start a school exam
        $this->post(route('exam.start'), ['subject_id' => $subject->id]);
        $confirm = $this->post(route('exam.confirm.school'), [
            'subject_id' => $subject->id,
            'token_code' => 'PREM-001',
        ]);
        $confirm->assertStatus(302);

        $session = ExamSession::latest()->first();
        // confirm the session was linked to the plan record
        $this->assertNotNull($session->student_plan_id, 'Session should store student_plan_id');
        // the session should remember that it was tied to a plan with explanations
        $this->assertTrue($session->hasFeature('explanations'), 'Session must report it has explanations feature');

        // attempts consumed: should be 0 now
        $record = $user->studentPlans()->where('plan_id', $plan->id)->latest()->first();
        $this->assertEquals(0, $record->attempts_remaining);

        // complete the exam so that review page is available
        Cache::put('allow_exam_review', true);
        $this->post(route('exam.submit', $session), ['answers' => []]);
        $session->refresh();

        // navigate to review page of the session; explanation should appear
        Cache::put('allow_exam_review', true);
        $response = $this->get(route('exam.review', $session));
        $response->assertStatus(200);
        $response->assertSee('Explanation');

        // leaderboard link should still be accessible via sidebar during review
        // (check for href attribute since it may not be visible text)
        $this->assertStringContainsString(
            'href="'.route('student.leaderboard').'"',
            $response->getContent()
        );
    }
    /** @test */
    public function confirm_school_exam_builds_correct_number_and_avoids_repeats_when_possible()
    {
        $user = User::factory()->create();
        $subject = Subject::create(['name' => 'Physics']);

        // create a pool of 400 questions so we have enough fresh ones for two exams
        for ($i = 0; $i < 400; $i++) {
            Question::create([
                'subject_id' => $subject->id,
                'question_text' => "Question {$i}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
                'difficulty_level' => 'medium',
            ]);
        }

        $token = ExamToken::create([
            'code' => 'ABC123',
            'max_uses' => 5,
            'used_count' => 0,
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        Cache::put('school_questions_count', 180);
        Cache::put('shuffle_questions', false); // turn off shuffle for predictable testing
        Cache::put('shuffle_options', false); // disable option shuffling during tests

        $this->actingAs($user);

        // first exam
        $start = $this->post(route('exam.start'), ['subject_id' => $subject->id]);
        $start->assertStatus(200);

        $confirm = $this->post(route('exam.confirm.school'), [
            'subject_id' => $subject->id,
            'token_code' => 'ABC123',
        ]);

        // confirmation should redirect to exam.take (session created)
        $confirm->assertStatus(302);
        $this->assertTrue(ExamSession::count() > 0, 'No exam session was created; response: ' . $confirm->getContent());

        $session1 = ExamSession::latest()->first();
        $this->assertNotNull($session1);
        $this->assertEquals(180, $session1->total_questions);
        $this->assertCount(180, $session1->question_ids);

        // complete the exam via the submit endpoint so the change is visible to later HTTP requests
        $this->post(route('exam.submit', $session1), ['answers' => []]);
        $session1->refresh();
        $this->assertNotNull($session1->completed_at, 'first session did not get marked completed');

        // verify used question ids count
        $usedIds = ExamSession::where('student_id', $user->id)
            ->pluck('question_ids')
            ->filter()
            ->map(function ($ids) {
                return is_array($ids) ? $ids : (json_decode($ids, true) ?: []);
            })->flatten()->unique()->values()->all();
        $this->assertCount(180, $usedIds, 'expected 180 used question ids before second exam');

        // sanity check: excluding usedIds from pool should leave 220 fresh questions
        $freshCount = Question::where('subject_id', $subject->id)
            ->whereNotIn('id', $usedIds)
            ->count();
        $this->assertEquals(220, $freshCount, "expected 220 fresh questions after excluding used ones, got {$freshCount}");

        // simulate what confirmSchool would pick for the student using old SQL logic
        $questionCount = Cache::get('school_questions_count', 40);
        $shuffleQuestions = Cache::get('shuffle_questions', false);
        $baseQuery = Question::where('subject_id', $subject->id);
        $selected = collect();
        $freshQuery = (clone $baseQuery)->when(!empty($usedIds), function ($q) use ($usedIds) {
            return $q->whereNotIn('id', $usedIds);
        });
        if ($shuffleQuestions) {
            $freshQuery->inRandomOrder();
        }
        $fresh = $freshQuery->limit($questionCount)->get();
        $selected = $selected->merge($fresh);
        if ($selected->count() < $questionCount) {
            $needed = $questionCount - $selected->count();
            $excludeIds = $selected->pluck('id')->all();
            $fallbackQuery = (clone $baseQuery)->when(!empty($excludeIds), function ($q) use ($excludeIds) {
                return $q->whereNotIn('id', $excludeIds);
            });
            if ($shuffleQuestions) {
                $fallbackQuery->inRandomOrder();
            }
            $fallback = $fallbackQuery->limit($needed)->get();
            $selected = $selected->merge($fallback);
        }
        $this->assertCount(180, $selected, 'old SQL algorithm produced 180 selected questions');
        $intersection = array_intersect($usedIds, $selected->pluck('id')->all());
        $this->assertEmpty($intersection, 'old SQL algorithm picked some used IDs: '.implode(',', $intersection));

        // now simulate using the current PHP-based logic from ConfirmSchool
        $allQuestions = Question::where('subject_id', $subject->id)->get();
        $fresh = !empty($usedIds)
            ? $allQuestions->whereNotIn('id', $usedIds)
            : $allQuestions;
        if ($shuffleQuestions) {
            $fresh = $fresh->shuffle();
        }
        $simulated2 = $fresh->take($questionCount);
        if ($simulated2->count() < $questionCount) {
            $needed = $questionCount - $simulated2->count();
            $remaining = $allQuestions->whereNotIn('id', $simulated2->pluck('id')->all());
            if ($shuffleQuestions) {
                $remaining = $remaining->shuffle();
            }
            $simulated2 = $simulated2->merge($remaining->take($needed));
        }
        $this->assertCount(180, $simulated2, 'PHP algorithm produced 180 selected questions');
        $intersection2 = array_intersect($usedIds, $simulated2->pluck('id')->all());
        $this->assertEmpty($intersection2, 'PHP algorithm picked some used IDs: '.implode(',', $intersection2));

        // now actually perform the second exam confirmation
        $beforeCount = ExamSession::count();
        $confirm2 = $this->post(route('exam.confirm.school'), [
            'subject_id' => $subject->id,
            'token_code' => 'ABC123',
        ]);
        $confirm2->assertStatus(302);

        $this->assertEquals($beforeCount + 1, ExamSession::count(), 'Expected a new exam session to be created');

        // use id ordering to avoid ties on timestamp
        $session2 = ExamSession::orderBy('id', 'desc')->first();
        $this->assertNotNull($session2);
        $this->assertNotEquals($session1->id, $session2->id, 'Expected a new session record for second exam');
        $this->assertEquals(180, $session2->total_questions);
        $this->assertCount(180, $session2->question_ids);

        // there should be no overlap between the two sets since pool (400) > 2 * 180
        $overlap = array_intersect($session1->question_ids, $session2->question_ids);
        if (!empty($overlap)) {
            dump('session1 ids', $session1->question_ids);
            dump('session2 ids', $session2->question_ids);
        }
        $this->assertEmpty($overlap, 'Second exam reused questions from the first despite enough fresh supply: '.implode(',', $overlap));
    }

    /** @test */
    public function when_pool_small_reuses_questions_but_still_returns_full_count()
    {
        $user = User::factory()->create();
        $subject = Subject::create(['name' => 'Physics']);

        // pool only 200 questions, so second exam must reuse 160 of them
        for ($i = 0; $i < 200; $i++) {
            Question::create([
                'subject_id' => $subject->id,
                'question_text' => "Question {$i}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
                'difficulty_level' => 'medium',
            ]);
        }

        $token = ExamToken::create([
            'code' => 'XYZ789',
            'max_uses' => 5,
            'used_count' => 0,
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        Cache::put('school_questions_count', 180);
        Cache::put('shuffle_questions', false);

        $this->actingAs($user);
        $this->post(route('exam.start'), ['subject_id' => $subject->id]);
        $this->post(route('exam.confirm.school'), [
            'subject_id' => $subject->id,
            'token_code' => 'XYZ789',
        ]);

        $session1 = ExamSession::latest()->first();
        $session1->update(['completed_at' => now()]);

        $this->post(route('exam.confirm.school'), [
            'subject_id' => $subject->id,
            'token_code' => 'XYZ789',
        ]);

        $session2 = ExamSession::latest()->first();
        $this->assertEquals(180, $session2->total_questions);
        $this->assertCount(180, $session2->question_ids);

        // with pool of 200, overlap must be at least 160
        $overlap = array_intersect($session1->question_ids, $session2->question_ids);
        $this->assertGreaterThanOrEqual(160, count($overlap));
    }

    /** @test */
    public function jamb_selection_handles_large_used_question_sets()
    {
        $user = User::factory()->create();

        $english = Subject::create(['name' => 'English']);
        $s1 = Subject::create(['name' => 'Maths']);
        $s2 = Subject::create(['name' => 'Physics']);
        $s3 = Subject::create(['name' => 'Chemistry']);

        // create a reasonably large pool per subject
        foreach ([$english, $s1, $s2, $s3] as $subj) {
            for ($i = 0; $i < 100; $i++) {
                Question::create([
                    'subject_id' => $subj->id,
                    'question_text' => "Q{$i}",
                    'option_a' => 'A',
                    'option_b' => 'B',
                    'option_c' => 'C',
                    'option_d' => 'D',
                    'correct_option' => 'A',
                    'difficulty_level' => 'easy',
                ]);
            }
        }

        // mark many questions as already used by creating prior sessions
        $used = [];
        foreach ([$english, $s1, $s2, $s3] as $subj) {
            // take first 60 ids from each subject and mark them used
            $ids = Question::where('subject_id', $subj->id)->orderBy('id')->limit(60)->pluck('id')->all();
            $used = array_merge($used, $ids);
        }

        ExamSession::create([
            'student_id' => $user->id,
            'subject_id' => $english->id,
            'exam_mode' => 'school',
            'total_questions' => count($used),
            'duration_minutes' => 60,
            'score' => 0,
            'question_ids' => $used,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        // set smaller per-subject counts so selection must fallback when fresh pool limited
        Cache::put('jamb_english_questions', 10);
        Cache::put('jamb_questions_per_subject', 15);
        Cache::put('shuffle_questions', false);
        Cache::put('shuffle_options', false);

        $token = ExamToken::create([
            'code' => 'REGRE-1',
            'max_uses' => 5,
            'used_count' => 0,
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        $this->actingAs($user);

        $start = $this->post(route('exam.start.jamb'), ['subject_ids' => [$s1->id, $s2->id, $s3->id]]);
        $start->assertStatus(200);

        $confirm = $this->post(route('exam.confirm.jamb'), [
            'subject_ids' => [$s1->id, $s2->id, $s3->id],
            'token_code' => 'REGRE-1',
        ]);
        $confirm->assertStatus(302);

        $session = ExamSession::latest()->first();
        $expected = 10 + 15 * 3;
        $this->assertEquals($expected, $session->total_questions);
        $this->assertCount($expected, $session->question_ids);
    }

    /** @test */
    public function validate_respects_bound_user_and_rejects_others()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $plan = \App\Models\Plan::create([
            'name' => 'Unique',
            'price' => 5,
            'attempts_allowed' => 1,
            'duration_days' => 1,
            'has_explanations' => false,
            'has_leaderboard' => false,
            'has_streak' => false,
        ]);

        $token = ExamToken::create([
            'code' => 'BOUND1',
            'max_uses' => 5,
            'used_count' => 0,
            'is_active' => true,
            'created_by' => $owner->id,
            'plan_id' => $plan->id,
        ]);

        // first user validates and then actually uses it
        $this->actingAs($owner);
        $resp1 = $this->postJson(route('exam.validate.token'), ['code' => $token->code]);
        $resp1->assertStatus(200)->assertJson(['valid' => true, 'plan_token' => true]);
        $token->use($owner);

        // second user now tries to validate
        $this->actingAs($other);
        $resp2 = $this->postJson(route('exam.validate.token'), ['code' => $token->code]);
        $resp2->assertStatus(403);
        $this->assertFalse($resp2->json('valid'));
    }

    /** @test */
    public function student_can_submit_exam_and_score_is_recorded()
    {
        $user = User::factory()->create();
        $subject = Subject::create(['name' => 'Chemistry']);
        for ($i = 0; $i < 50; $i++) {
            Question::create([
                'subject_id' => $subject->id,
                'question_text' => "Q{$i}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
                'difficulty_level' => 'easy',
            ]);
        }

        $token = ExamToken::create(['code' => 'SUBMIT', 'max_uses' => 1, 'used_count' => 0, 'is_active' => true, 'created_by' => $user->id]);
        Cache::put('school_questions_count', 10);
        Cache::put('shuffle_questions', false);
        Cache::put('shuffle_options', false);

        $this->actingAs($user);
        $this->post(route('exam.start'), ['subject_id' => $subject->id]);
        $this->post(route('exam.confirm.school'), ['subject_id' => $subject->id, 'token_code' => 'SUBMIT']);
        $session = ExamSession::latest()->first();

        // submit 10 answers, half correct
        $answers = [];
        foreach (array_slice($session->question_ids, 0, 10) as $idx => $qid) {
            $answers[$qid] = $idx % 2 === 0 ? 'A' : 'B';
        }

        $resp = $this->post(route('exam.submit', $session), ['answers' => $answers]);
        $resp->assertRedirect();

        $session->refresh();
        $this->assertEquals(5, $session->score);
        $this->assertNotNull($session->completed_at);
    }

    /** @test */
    public function submit_with_invalid_question_id_returns_error()
    {
        $user = User::factory()->create();
        $subject = Subject::create(['name' => 'Chemistry']);
        for ($i = 0; $i < 20; $i++) {
            Question::create([
                'subject_id' => $subject->id,
                'question_text' => "Q{$i}",
                'option_a' => 'A',
                'option_b' => 'B',
                'option_c' => 'C',
                'option_d' => 'D',
                'correct_option' => 'A',
                'difficulty_level' => 'easy',
            ]);
        }

        $token = ExamToken::create(['code' => 'BADQ', 'max_uses' => 1, 'used_count' => 0, 'is_active' => true, 'created_by' => $user->id]);
        Cache::put('school_questions_count', 5);
        Cache::put('shuffle_questions', false);
        Cache::put('shuffle_options', false);

        $this->actingAs($user);
        $this->post(route('exam.start'), ['subject_id' => $subject->id]);
        $this->post(route('exam.confirm.school'), ['subject_id' => $subject->id, 'token_code' => 'BADQ']);
        $session = ExamSession::latest()->first();

        // try submitting with one extra bogus question id
        $answers = [];
        foreach ($session->question_ids as $qid) {
            $answers[$qid] = 'A';
        }
        $answers[9999] = 'B';

        $resp = $this->post(route('exam.submit', $session), ['answers' => $answers]);
        $resp->assertSessionHasErrors('answers');

        $session->refresh();
        $this->assertNull($session->completed_at, 'Session should not be marked completed on invalid submit');
    }
}
