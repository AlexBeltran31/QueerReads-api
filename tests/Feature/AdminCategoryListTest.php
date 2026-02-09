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

        Category::factory()->create(['name' => 'Queer Poetry', 'slug' => 'queer poetry']);
        Category::factory()->create(['name' => 'Essay', 'slug' => 'essay']);

        Passport::actingAs($admin);

        $response = $this->getJson('/api/admin/categories');

        $response->assertStatus(200)
                 ->assertJsonFragment(['slug' => 'queer poetry'])
                 ->assertJsonFragment(['slug' => 'essay']);
    }
}
