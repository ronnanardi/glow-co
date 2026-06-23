<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TierDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\TierDiscount::insert([
            ['tier' => 'silver', 'value' => 2,  'created_at' => now(), 'updated_at' => now()],
            ['tier' => 'gold',   'value' => 5,  'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
