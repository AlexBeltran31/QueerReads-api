<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_list_users() {
        User::factory()->count(3)->create();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Passport::actingAs($admin);

        $response = $this->getJson('/api/admin/users');

        $response->assertStatus(200)
                 ->assertJsonCount(4);
    }

    /** @test */
    public function normal_user_cannot_list_users() {
        User::factory()->count(3)->create();

        $user = User::factory()->create([
            'role' => 'user',
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/admin/users');

        $response->assertStatus(403);
    }
}
