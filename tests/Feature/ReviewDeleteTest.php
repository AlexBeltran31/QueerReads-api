<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Review;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReviewDeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_delete_own_review() {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $review = Review::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'rating' => 5,
        ]);

        Passport::actingAs($user);

        $response = $this->deleteJson("/api/reviews/{$review->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id
        ]);
    }

    /** @test */
    public function user_cannot_delete_other_users_review() {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $book = Book::factory()->create();

        $review = Review::create([
            'user_id' => $otherUser->id,
            'book_id' => $book->id,
            'rating' => 4,
        ]);

        Passport::actingAs($user);

        $response = $this->deleteJson("/api/reviews/{$review->id}");

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_delete_any_review() {
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);

        $user = User::factory()->create();
        $book = Book::factory()->create();

        $review = Review::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'rating' => 3,
        ]);

        Passport::actingAs($admin);

        $response = $this->deleteJson("/api/reviews/{$review->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id
        ]);
    }

    /** @test */
    public function guest_cannot_delete_review() {
        $review = Review::factory()->create();

        $response = $this->deleteJson("/api/reviews/{$review->id}");

        $response->assertStatus(401);
    }
}
