<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminCategoryListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_list_categories() {
        $admin = User::factory()->create(['role' => 'admin']);

        Category::factory()->create(['name' => 'Poetry', 'slug' => 'poetry']);
        Category::factory()->create(['name' => 'Essay', 'slug' => 'essay']);

        Passport::actingAs($admin);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
                 ->assertJsonFragment(['slug' => 'poetry'])
                 ->assertJsonFragment(['slug' => 'essay']);
    }
}
