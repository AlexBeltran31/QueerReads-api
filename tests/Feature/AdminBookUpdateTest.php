<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminBookUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_update_a_book() {
        $admin = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create([
            'title' => 'Old title',
            'author' => 'Old author',
        ]);

        Passport::actingAs($admin);

        $response = $this->putJson("/api/admin/books/{$book->id}", [
            'title' => 'New title',
            'author' => 'New author',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                    'id' => $book->id,
                    'title' => 'New title',
                    'author' => 'New author',
                 ]);
    }
}
