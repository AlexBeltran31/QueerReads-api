<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminCategoryCreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_a_category() {
        $admin = User::factory()->create(['role' => 'admin']);

        Passport::actingAs($admin);

        $payload = [
            'name' => 'Poetry',
            'slug' => 'poetry',
        ];

        $response = $this->postJson('/api/admin/categories', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('categories', [
            'name' => 'Poetry',
            'slug' => 'poetry',
        ]);
    }
}
