<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $cleanser    = Category::where('slug', 'facial-cleanser')->first()->id;
        $serum       = Category::where('slug', 'serum-essence')->first()->id;
        $moisturizer = Category::where('slug', 'sunscreen-moisturizer')->first()->id;

        $products = [
            [
                'category_id' => $serum,
                'name'        => 'Vitamin C Brightening Serum 30ml',
                'slug'        => 'vitamin-c-brightening-serum-30ml',
                'description' => 'Serum vitamin C dengan formula brightening untuk kulit glowing.',
                'price'       => 189000,
                'stock'       => 100,
                'image'       => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?q=80&w=400',
                'is_active'   => true,
                'badge'       => 'best',
            ],
            [
                'category_id' => $cleanser,
                'name'        => 'Gentle Foam Cleanser pH 5.5',
                'slug'        => 'gentle-foam-cleanser-ph-55',
                'description' => 'Pembersih wajah lembut dengan pH seimbang untuk semua jenis kulit.',
                'price'       => 129000,
                'stock'       => 80,
                'image'       => 'https://images.unsplash.com/photo-1570194065650-d99fb4b38b17?q=80&w=400',
                'is_active'   => true,
                'badge'       => 'new',
            ],
            [
                'category_id' => $moisturizer,
                'name'        => 'Hyaluronic Acid Moisturizer Gel',
                'slug'        => 'hyaluronic-acid-moisturizer-gel',
                'description' => 'Moisturizer gel dengan hyaluronic acid untuk kulit lembap seharian.',
                'price'       => 159000,
                'stock'       => 60,
                'image'       => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?q=80&w=400',
                'is_active'   => true,
                'badge'       => 'sale',
            ],
            [
                'category_id' => $serum,
                'name'        => 'Niacinamide 10% + Zinc Serum',
                'slug'        => 'niacinamide-10-zinc-serum',
                'description' => 'Serum niacinamide untuk menyamarkan pori dan mencerahkan kulit.',
                'price'       => 175000,
                'stock'       => 120,
                'image'       => 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?q=80&w=400',
                'is_active'   => true,
                'badge'       => null,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
