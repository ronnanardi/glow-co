<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        Voucher::create([
            'code'         => 'GLOWNEW10',
            'type'         => 'percentage',
            'value'        => 10,
            'min_purchase' => 100000,
            'max_discount' => 50000,
            'usage_limit'  => 100,
            'is_active'    => true,
            'expires_at'   => now()->addMonths(3),
        ]);

        Voucher::create([
            'code'         => 'HEMAT20K',
            'type'         => 'fixed',
            'value'        => 20000,
            'min_purchase' => 150000,
            'usage_limit'  => 50,
            'is_active'    => true,
            'expires_at'   => now()->addMonths(1),
        ]);
    }
}
