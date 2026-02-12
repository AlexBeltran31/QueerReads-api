<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadingListStoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_add_book_to_reading_list() {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->postJson("/api/reading-list/{$book->id}");

        $response->assertStatus(201);

        $this->assertDatabaseHas('reading_list', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'to_read'
        ]);
    }

    /** @test */
    public function guest_cannot_add_book_to_reading_list() {
        $book = Book::factory()->create();

        $response = $this->postJson("/api/reading-list/{$book->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function user_cannot_add_same_book_twice() {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $user->readingList()->attach($book->id, [
            'status' => 'to_read'
        ]);

        $response = $this->postJson("/api/reading-list/{$book->id}");

        $response->assertStatus(409);
    }
}
