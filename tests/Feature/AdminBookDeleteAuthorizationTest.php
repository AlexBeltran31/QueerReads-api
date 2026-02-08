<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminBookDeleteAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_cannot_delete_a_book() {
        $user = User::factory()->create(['role' => 'user']);
        $book = Book::factory()->create();

        Passport::actingAs($user);

        $response = $this->deleteJson("/api/admin/books/{$book->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
        ]);
    }
}
