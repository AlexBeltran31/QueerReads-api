<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class UserMeTest extends TestCase {
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_get_own_profile() {
        $user = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->getJson('/api/users/me');

        $response->assertStatus(200)
                 ->assertJson([
                    'id' => $user->id,
                    'email' => $user->email,
                    'role' => 'user',
                 ]);
    }

    /** @test */
    public function guest_cannot_access_user_profile() {
        $response = $this->getJson('/api/users/me');

        $response->assertStatus(401);
    }
}