<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminBookDeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_delete_a_book() {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create();

        Passport::actingAs($admin);

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
        ]);
    }
}
