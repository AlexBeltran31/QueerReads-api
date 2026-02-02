<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function admin_can_access_admin_route() {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Passport::actingAs($admin);

        $response = $this->getJson('/api/admin/test');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_cannot_access_admin_route() {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        Passport::actingAs($user);

        $response = $this->getJson('/api/admin/test');

        $response->assertStatus(403);
    }
}
