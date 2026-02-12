<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_update_own_profile() {
        $user = User::factory()->create([
            'name' => 'Old Name',
        ]);

        Passport::actingAs($user);

        $response = $this->putJson(
            "/api/users/{$user->id}",
            ['name' => 'New Name']
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
        ]);
    }

    /** @test */
    public function user_cannot_update_another_user_profile() {
        $user = User::factory()->create(['role' => 'user']);
        $otherUser = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->putJson(
            "/api/users/{$otherUser->id}",
            ['name' => 'Hacked Name']
        );

        $response->assertStatus(403);
    }
}
