<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminBookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_create_a_book() {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);
        $category = Category::factory()->create();

        Passport::actingAs($admin);

        $response = $this->postJson('/api/books', [
            'title' => 'Stone Butch Blues',
            'author' => 'Leslie Feinberg',
            'description' => 'A classic queer novel',
            'category_id' => $category->id,
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                    'title' => 'Stone Butch Blues',
                    'author' => 'Leslie Feinberg',
                 ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Stone Butch Blues',
        ]);
    }
}
