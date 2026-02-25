<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicBookShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anyone_can_view_a_single_book() {
        $book = Book::factory()->create([
            'title' => 'Stone Butch Blues',
            'author' => 'Leslie Feinberg',
        ]);

        $response = $this->getJson("/api/books/{$book->id}");

        $response->assertStatus(200)
                 ->assertJson([
                    'id' => $book->id,
                    'title' => 'Stone Butch Blues',
                    'author' => 'Leslie Feinberg',
                    ]);
    }
}