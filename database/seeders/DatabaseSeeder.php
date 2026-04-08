<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Product::create([
            'name' => 'GREED OVERSIZED TEE V1',
            'slug' => 'greed-oversized-tee-v1',
            'image' => 'p1.png', 
            'price' => 349000,
            'description' => 'Heavyweight cotton 24s, high quality screen print.',
        ]);
        
        \App\Models\Product::create([
            'name' => 'TECHNICAL CARGO PANTS',
            'slug' => 'technical-cargo-pants',
            'image' => 'p2.png',
            'price' => 599000,
            'description' => 'Water repellent material with multi-pocket system.',
        ]);
    }
}
