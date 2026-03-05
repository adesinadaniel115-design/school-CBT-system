<?php

namespace Tests\Unit;

use App\Models\ExamToken;
use App\Models\Plan;
use App\Models\StudentPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function token_with_plan_creates_student_plan_on_use()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $plan = Plan::create([
            'name' => 'Test Plan',
            'price' => 9.99,
            'attempts_allowed' => 3,
            'duration_days' => 7,
            'has_explanations' => true,
            'has_leaderboard' => false,
            'has_streak' => false,
        ]);

        $token = ExamToken::create([
            'code' => 'ABC-DEF-GHI',
            'max_uses' => 1,
            'used_count' => 0,
            'is_active' => true,
            'created_by' => $user->id,
            'plan_id' => $plan->id,
        ]);

        $this->assertDatabaseCount('student_plans', 0);

        $result = $token->use($user);
        $this->assertTrue($result);

        $this->assertDatabaseHas('student_plans', [
            'student_id' => $user->id,
            'plan_id' => $plan->id,
            'attempts_remaining' => 3,
        ]);

        // validating the token via controller should indicate it's a plan token
        $response = $this->actingAs($user)->postJson(route('exam.validate.token'), ['code' => $token->code]);
        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['valid']);
        $this->assertTrue($data['plan_token']);
        $this->assertNull($data['remaining_uses']);
        $this->assertEquals('Test Plan', $data['plan']['name']);

        $record = StudentPlan::first();
        $this->assertNotNull($record->expires_at);
        $this->assertFalse($record->isExpired());
    }

    /** @test */
    public function active_plan_helper_returns_plan_only_if_not_expired_and_with_attempts()
    {
        $user = User::factory()->create();
        $plan = Plan::create([
            'name' => 'Dummy',
            'price' => 0,
            'attempts_allowed' => 2,
            'duration_days' => 1,
            'has_explanations' => false,
            'has_leaderboard' => false,
            'has_streak' => false,
        ]);

        // no plan yet
        $this->assertNull($user->activePlan());

        // create a student plan record manually
        $record = StudentPlan::create([
            'student_id' => $user->id,
            'plan_id' => $plan->id,
            'attempts_remaining' => 1,
            'expires_at' => now()->addDay(),
        ]);

        $this->assertSame($plan->id, $user->activePlan()->id);

        // expire it
        $record->expires_at = now()->subMinute();
        $record->save();
        $this->assertNull($user->activePlan());

        // make unexpired but zero attempts
        $record->expires_at = now()->addDay();
        $record->attempts_remaining = 0;
        $record->save();
        $this->assertNull($user->activePlan());
    }

    /** @test */
    public function has_feature_returns_true_for_legacy_users_and_checks_plan_flags()
    {
        $user = User::factory()->create();
        $this->assertFalse($user->hasFeature('explanations'));

        $plan = Plan::create([
            'name' => 'Foo',
            'price' => 0,
            'attempts_allowed' => 1,
            'duration_days' => null,
            'has_explanations' => false,
            'has_leaderboard' => true,
            'has_streak' => false,
        ]);

        StudentPlan::create([
            'student_id' => $user->id,
            'plan_id' => $plan->id,
            'attempts_remaining' => 1,
            'expires_at' => null,
        ]);

        $this->assertFalse($user->hasFeature('explanations'));
        $this->assertTrue($user->hasFeature('leaderboard'));
        $this->assertFalse($user->hasFeature('streak'));
    }

    /** @test */
    public function token_binds_to_first_user_and_blocks_others()
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();

        $token = ExamToken::create([
            'code' => 'SHARE-001',
            'max_uses' => 5,
            'used_count' => 0,
            'is_active' => true,
            'created_by' => $owner->id,
        ]);

        // first use binds it
        $result1 = $token->use($owner, null, '1.2.3.4', 'UA/1');
        $this->assertTrue($result1);
        $token->refresh();
        $this->assertEquals($owner->id, $token->bound_user_id);
        $this->assertFalse($token->sharing_detected);

        // second user cannot use it
        $result2 = $token->use($other, null, '5.6.7.8', 'UA/2');
        $this->assertFalse($result2, 'token should reject second user');
        $token->refresh();
        $this->assertTrue($token->sharing_detected, 'sharing flag should be set once conflict occurs');
    }
}
