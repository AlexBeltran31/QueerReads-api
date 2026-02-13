<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_create_review_if_book_is_finished() {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $user->readingList()->attach($book->id, [
            'status' => 'finished'
        ]);

        Passport::actingAs($user);

        $response = $this->postJson("/api/books/{$book->id}/reviews", [
            'rating' => 5,
            'comment' => 'Amazing book!'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'rating' => 5,
        ]);
    }

    /** @test */
    public function user_cannot_review_if_book_not_finished() {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $user->readingList()->attach($book->id, [
            'status' => 'reading'
        ]);

        Passport::actingAs($user);

        $response = $this->postJson("/api/books/{$book->id}/reviews", [
            'rating' => 5,
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_review_same_book_twice() {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $user->readingList()->attach($book->id, [
            'status' => 'finished'
        ]);

        Passport::actingAs($user);

        $this->postJson("/api/books/{$book->id}/reviews", [
            'rating' => 5,
        ]);

        $response = $this->postJson("/api/books/{$book->id}/reviews", [
            'rating' => 4,
        ]);

        $response->assertStatus(409);
    }

    /** @test */
    public function guest_cannot_create_review() {
        $book = Book::factory()->create();

        $response = $this->postJson("/api/books/{$book->id}/reviews", [
            'rating' => 5,
        ]);

        $response->assertStatus(401);
    }
}
