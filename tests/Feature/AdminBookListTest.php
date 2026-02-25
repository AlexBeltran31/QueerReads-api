<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminBookListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_list_books() {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Book::factory()->count(3)->create();

        Passport::actingAs($admin);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }
}
