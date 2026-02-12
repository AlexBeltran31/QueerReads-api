<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadingListUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_update_reading_status() {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $user->readingList()->attach($book->id, [
            'status' => 'to_read'
        ]);

        Passport::actingAs($user);

        $response = $this->putJson("/api/reading-list/{$book->id}", [
            'status' => 'finished'
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('reading_list', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'finished'
        ]);
    }

    /** @test */
    public function user_cannot_update_book_not_in_reading_list() {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->putJson("/api/reading-list/{$book->id}", [
            'status' => 'reading'
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function status_must_be_valid() {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $user->readingList()->attach($book->id, [
            'status' => 'to_read'
        ]);

        Passport::actingAs($user);

        $response = $this->putJson("/api/reading-list/{$book->id}", [
            'status' => 'invalid_status'
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function guest_cannot_update_reading_list()
    {
        $book = Book::factory()->create();

        $response = $this->putJson("/api/reading-list/{$book->id}", [
            'status' => 'reading'
        ]);

        $response->assertStatus(401);
    }
}
