<?php

namespace Tests\Feature;


use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use App\Models\Category;
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
        $category = Category::factory()->create();

        Passport::actingAs($admin);

        $response = $this->putJson("/api/books/{$book->id}", [
            'title' => 'New title',
            'author' => 'New author',
            'category_id' => $category->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                    'id' => $book->id,
                    'title' => 'New title',
                    'author' => 'New author',
                 ]);
    }
}
