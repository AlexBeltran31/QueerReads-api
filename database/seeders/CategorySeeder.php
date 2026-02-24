<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Lesbian Fiction', 'slug' => 'lesbian-fiction'],
            ['name' => 'Trans Studies', 'slug' => 'trans-studies'],
            ['name' => 'Queer Poetry', 'slug' => 'queer-poetry'],
            ['name' => 'Gay Fiction', 'slug' => 'gay-fiction'],
            ['name' => 'Queer Autobiography', 'slug' => 'queer-autobiography'],
            ['name' => 'LGBTQ+ History', 'slug' => 'lgtbq-history'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}