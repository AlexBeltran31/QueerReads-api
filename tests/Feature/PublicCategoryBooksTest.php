<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicCategoryBooksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anyone_can_view_books_of_a_category() {
        $category = Category::factory()->create([
            'name' => 'Poetry',
            'slug' => 'poetry',
        ]);

        $book1 = Book::factory()->create(['title' => 'Book One']);
        $book2 = Book::factory()->create(['title' => 'Book Two']);
        $book3 = Book::factory()->create(['title' => 'Other Book']);

        $category->books()->attach([$book1->id, $book2->id]);

        $reponse = $this->getJson("/api/categories/{$category->id}/books");

        $reponse->assertStatus(200)
                ->assertJsonFragment(['title' => 'Book One'])
                ->assertJsonFragment(['title' => 'Book Two'])
                ->assertJsonMissing(['title' => 'Other Book']);
    }

    /** @test */
    public function returns_404_if_category_not_fount() {
        $response = $this->getJson("/api/categories/999/books");

        $response->assertStatus(404);
    }
}
