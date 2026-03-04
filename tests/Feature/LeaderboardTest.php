<?php

namespace Tests\Feature;

use App\Models\ExamSession;
use App\Models\Plan;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function students_without_leaderboard_feature_cannot_access()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('student.leaderboard'));
        $response->assertStatus(403);
    }

    /** @test */
    public function students_with_leaderboard_feature_can_view_rankings()
    {
        $user = User::factory()->create();
        // give them plan with leaderboard turned on
        $plan = Plan::create([
            'name' => 'Premium',
            'price' => 0,
            'attempts_allowed' => 1,
            'duration_days' => null,
            'has_explanations' => false,
            'has_leaderboard' => true,
            'has_streak' => false,
        ]);
        \App\Models\StudentPlan::create([
            'student_id' => $user->id,
            'plan_id' => $plan->id,
            'attempts_remaining' => 1,
            'expires_at' => null,
        ]);

        // create another student with a high score
        $other = User::factory()->create(['name' => 'Alice']);
        $subject = Subject::create(['name' => 'Dummy']);
        ExamSession::create([
            'student_id' => $other->id,
            'subject_id' => $subject->id,
            'total_questions' => 10,
            'score' => 95,
            'question_ids' => json_encode([]),
            'duration_minutes' => 60,
            'started_at' => now(),
            'completed_at' => now(),
        ]);
        // lower score by current user for ordering
        ExamSession::create([
            'student_id' => $user->id,
            'subject_id' => $subject->id,
            'total_questions' => 10,
            'score' => 80,
            'question_ids' => json_encode([]),
            'duration_minutes' => 60,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        $this->actingAs($user);
        $response = $this->get(route('student.leaderboard'));
        $response->assertStatus(200);

        // leader page should contain names and scores
        $response->assertSee('Alice');
        $response->assertSee('95');
        $response->assertSee('80');

        // rank badges should start at 1 and increase
        $response->assertSee('<div class="rank-badge">1</div>', false);
        $response->assertSee('<div class="rank-badge">2</div>', false);
    }

    /** @test */
    public function student_outside_top_10_but_within_30_sees_their_position()
    {
        $user = User::factory()->create(['name' => 'Learner']);
        $plan = Plan::create([
            'name' => 'Premium',
            'price' => 0,
            'attempts_allowed' => 1,
            'duration_days' => null,
            'has_explanations' => false,
            'has_leaderboard' => true,
            'has_streak' => false,
        ]);
        \App\Models\StudentPlan::create([
            'student_id' => $user->id,
            'plan_id' => $plan->id,
            'attempts_remaining' => 1,
            'expires_at' => null,
        ]);

        $subject = Subject::create(['name' => 'Dummy']);

        // create 14 higher scoring users to occupy ranks 1-14
        for ($i = 1; $i <= 14; $i++) {
            $other = User::factory()->create();
            ExamSession::create([
                'student_id' => $other->id,
                'subject_id' => $subject->id,
                'total_questions' => 10,
                'score' => 100 - $i,
                'question_ids' => json_encode([]),
                'duration_minutes' => 60,
                'started_at' => now(),
                'completed_at' => now(),
            ]);
        }

        // current user at position 15
        ExamSession::create([
            'student_id' => $user->id,
            'subject_id' => $subject->id,
            'total_questions' => 10,
            'score' => 50,
            'question_ids' => json_encode([]),
            'duration_minutes' => 60,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        // some lower users just to fill up but not necessary for assertion
        for ($i = 16; $i <= 30; $i++) {
            $other = User::factory()->create();
            ExamSession::create([
                'student_id' => $other->id,
                'subject_id' => $subject->id,
                'total_questions' => 10,
                'score' => 50 - $i,
                'question_ids' => json_encode([]),
                'duration_minutes' => 60,
                'started_at' => now(),
                'completed_at' => now(),
            ]);
        }

        $this->actingAs($user);
        $response = $this->get(route('student.leaderboard'));
        $response->assertStatus(200);

        // user name and score should appear below top ten
        $response->assertSee('Learner');
        $response->assertSee('50');
        $response->assertSee('<div class="rank-badge">15</div>', false);
        // ensure that the top list did not accidentally include an 11th slot
        $response->assertDontSee('<div class="rank-badge">11</div>', false);
    }

    /** @test */
    public function student_with_rank_beyond_30_does_not_see_position()
    {
        $user = User::factory()->create(['name' => 'FarAway']);
        $plan = Plan::create([
            'name' => 'Premium',
            'price' => 0,
            'attempts_allowed' => 1,
            'duration_days' => null,
            'has_explanations' => false,
            'has_leaderboard' => true,
            'has_streak' => false,
        ]);
        \App\Models\StudentPlan::create([
            'student_id' => $user->id,
            'plan_id' => $plan->id,
            'attempts_remaining' => 1,
            'expires_at' => null,
        ]);

        $subject = Subject::create(['name' => 'Dummy']);

        // create 31 higher scoring sessions
        for ($i = 1; $i <= 31; $i++) {
            $other = User::factory()->create();
            ExamSession::create([
                'student_id' => $other->id,
                'subject_id' => $subject->id,
                'total_questions' => 10,
                'score' => 100 - $i,
                'question_ids' => json_encode([]),
                'duration_minutes' => 60,
                'started_at' => now(),
                'completed_at' => now(),
            ]);
        }

        ExamSession::create([
            'student_id' => $user->id,
            'subject_id' => $subject->id,
            'total_questions' => 10,
            'score' => 1,
            'question_ids' => json_encode([]),
            'duration_minutes' => 60,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        $this->actingAs($user);
        $response = $this->get(route('student.leaderboard'));
        $response->assertStatus(200);

        $response->assertDontSee('<div class="rank-badge">32</div>', false);
        $response->assertDontSee('FarAway');
    }
}
