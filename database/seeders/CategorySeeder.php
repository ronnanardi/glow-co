<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            [
                'name'  => 'Facial Cleanser',
                'slug'  => 'facial-cleanser',
                'image' => 'https://images.unsplash.com/photo-1570194065650-d99fb4b38b17?q=80&w=600',
            ],
            [
                'name'  => 'Serum & Essence',
                'slug'  => 'serum-essence',
                'image' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?q=80&w=600',
            ],
            [
                'name'  => 'Sunscreen & Moisturizer',
                'slug'  => 'sunscreen-moisturizer',
                'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=600',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
