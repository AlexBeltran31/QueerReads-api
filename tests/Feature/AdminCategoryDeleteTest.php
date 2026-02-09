<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminCategoryDeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_delete_a_category() {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        Passport::actingAs($admin);

        $response = $this->deleteJson(
            "/api/admin/categories/{$category->id}"
        );

        $response->assertStatus(204);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
}
