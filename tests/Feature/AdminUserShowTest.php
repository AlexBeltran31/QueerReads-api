<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminUserShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_view_a_user_profile() {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Passport::actingAs($admin);

        $response = $this->getJson("/api/admin/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJson([
                    'id' => $user->id,
                    'name' => 'Test User',
                    'email' => 'test@example.com',
                 ]);
    }

    /** @test */
    public function normal_user_cannot_view_other_user_profile() {
        $user = User::factory()->create(['role' => 'user']);
        $otherUser = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->getJson("/api/admin/users/{$otherUser->id}");

        $response->assertStatus(403);
    }
}
