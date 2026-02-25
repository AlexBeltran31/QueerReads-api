<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PublicBookListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anyone_can_list_books() {
        Book::factory()->count(5)->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
                 ->assertJsonCount(5);
    }
}
