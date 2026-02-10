<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminBookAssignCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_assign_categories_to_a_book() {
        $admin = User::factory()->create(['role' => 'admin']);

        $book = Book::factory()->create();

        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        Passport::actingAs($admin);

        $payload = [
            'categories' => [
                $category1->id,
                $category2->id,
            ],
        ];

        $response = $this->putJson(
            "/api/admin/books/{$book->id}",
            $payload
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('book_category', [
            'book_id' => $book->id,
            'category_id' => $category1->id,
        ]);

        $this->assertDatabaseHas('book_category', [
            'book_id' => $book->id,
            'category_id' => $category2->id,
        ]);
    }
}
