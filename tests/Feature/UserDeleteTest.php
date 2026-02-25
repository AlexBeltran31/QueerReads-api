<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserDeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_cannot_delete_own_account() {
        $user = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function user_cannot_delete_another_user() {
        $user = User::factory()->create(['role' => 'user']);
        $otherUser = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->deleteJson("/api/users/{$otherUser->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_delete_other_user() {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $user = User::factory()->create();

        Passport::actingAs($admin);

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function admin_cannot_delete_self() {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        Passport::actingAs($admin);

        $response = $this->deleteJson("/api/users/{$admin->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }
}
