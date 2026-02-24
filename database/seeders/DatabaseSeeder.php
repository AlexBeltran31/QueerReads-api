<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin fijo
        User::create([
            'name' => 'Admin',
            'email' => 'admin@queerreads.test',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        // Usuario normal
        User::create([
            'name' => 'Test User',
            'email' => 'user@queerreads.test',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $this->call([
            CategorySeeder::class,
            BookSeeder::class,
        ]);
    }
}