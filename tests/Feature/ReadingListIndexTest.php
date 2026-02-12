<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadingListIndexTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_view_reading_list() {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $user->readingList()->attach($book->id, [
            'status' => 'reading'
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/reading-list');

        $response->assertStatus(200)
                 ->assertJsonFragment([
                    'title' => $book->title
                 ]);
    }
}
