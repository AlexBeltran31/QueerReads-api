<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RandomBookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_get_random_book_to_read() {
        $user = User::factory()->create();

        $book1 = Book::factory()->create();
        $book2 = Book::factory()->create();

        $user->readingList()->attach($book1->id, [
            'status' => 'to_read'
        ]);

        $user->readingList()->attach($book2->id, [
            'status' => 'to_read'
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/books/random-to-read');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'id',
                    'title',
                 ]);
    }

    /** @test */
    public function returns_404_if_no_books_to_read() {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $response = $this->getJson('/api/books/random-to-read');

        $response->assertStatus(404);
    }

    /** @test */
    public function guest_cannot_access_random_to_read() {
        $response = $this->getJson('/api/books/random-to-read');

        $response->assertStatus(401);
    }
}
