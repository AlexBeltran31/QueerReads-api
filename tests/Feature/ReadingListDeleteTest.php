<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ReadingListDeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_remove_book_from_reading_list() {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $user->readingList()->attach($book->id, [
            'status' => 'reading'
        ]);

        Passport::actingAs($user);

        $response = $this->deleteJson("/api/reading-list/{$book->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('reading_list', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    /** @test */
    public function user_cannot_remove_book_not_in_reading_list() {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->deleteJson("/api/reading-list/{$book->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function guest_cannot_remove_book_from_reading_list() {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/reading-list/{$book->id}");

        $response->assertStatus(401);
    }
}
