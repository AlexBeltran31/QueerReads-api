<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_reviews_for_a_book() {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        Review::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'rating' => 5,
            'comment' => 'Great book!'
        ]);

        $response = $this->getJson("/api/books/{$book->id}/reviews");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                    'rating' => 5,
                    'comment' => 'Great book!'
                 ]);
    }

    /** @test */
    public function it_returns_empty_array_if_no_reviews_exist() {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->id}/reviews");

        $response->assertStatus(200)
                 ->assertExactJson([]);
    }
}
