<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicCategoryListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anyone_can_list_categories() {
        Category::factory()->create(['name' => 'Poetry', 'slug' => 'poetry']);
        Category::factory()->create(['name' => 'Essay', 'slug' => 'essay']);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
                 ->assertJsonFragment(['slug' => 'poetry'])
                 ->assertJsonFragment(['slug' => 'essay']);
    }
}
