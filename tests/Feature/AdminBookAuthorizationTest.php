<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AdminBookAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function normal_user_cannot_create_a_book() {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        Passport::actingAs($user);

        $response = $this->postJson('/api/books', [
            'title' => 'Stone Butch Blues',
            'author' => 'Leslie Feinberg',
            'description' => 'A classic queer novel',
        ]);

        $response->assertStatus(403);
    }
}
