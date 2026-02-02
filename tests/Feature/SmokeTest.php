<?php

namespace Tests\Feature;

use Tests\TestCase;

class SmokeTest extends TestCase
{
    public function test_api_ping_works()
    {
        $response = $this->getJson('/api/ping');

        $response->assertStatus(200);
    }
}
