<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function store_endpoint_accepts_checkbox_values_and_saves_booleans()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $response = $this->post(route('admin.plans.store'), [
            'name' => 'Checkbox Plan',
            'price' => 5.00,
            'attempts_allowed' => 2,
            'duration_days' => 10,
            // simulate the browser sending "on" for a checked checkbox
            'has_explanations' => 'on',
            'has_leaderboard' => 'on',
            'has_streak' => 'on',
        ]);

        $response->assertRedirect(route('admin.plans.index'));
        $this->assertDatabaseHas('plans', [
            'name' => 'Checkbox Plan',
            'has_explanations' => true,
            'has_leaderboard' => true,
            'has_streak' => true,
        ]);
    }

    /** @test */
    public function update_endpoint_handles_missing_checkbox_fields_correctly()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $plan = \App\Models\Plan::create([
            'name' => 'Orig',
            'price' => 1,
            'attempts_allowed' => 1,
            'duration_days' => null,
            'has_explanations' => true,
            'has_leaderboard' => true,
            'has_streak' => true,
        ]);

        $response = $this->put(route('admin.plans.update', $plan), [
            'name' => 'Orig',
            'price' => 1,
            'attempts_allowed' => 1,
            // do not send any checkbox keys so they should all become false
        ]);

        $response->assertRedirect(route('admin.plans.index'));
        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'has_explanations' => false,
            'has_leaderboard' => false,
            'has_streak' => false,
        ]);
    }
}
