<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminCategoryAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function normal_user_cannot_create_a_category() {
        $user = User::factory()->create(['role' => 'user']);

        Passport::actingAs($user);

        $payload = [
            'name' => 'Poetry',
            'slug' => 'poetry',
        ];

        $response = $this->postJson('/api/categories', $payload);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('categories', [
            'slug' => 'poetry',
        ]);
    }
}
