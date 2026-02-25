<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminCategoryUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_update_a_category() {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create([
            'name' => 'Poetry',
            'slug' => 'poetry',
        ]);

        Passport::actingAs($admin);
        
        $payload = [
            'name' => 'Queer Poetry',
            'slug' => 'queer-poetry',
        ];

        $response = $this->putJson(
            "/api/categories/{$category->id}",
            $payload
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Queer Poetry',
            'slug' => 'queer-poetry',
        ]);
    }
}
