<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'store_name'        => 'GLOW&CO Skincare',
            'store_tagline'     => 'Skincare premium Indonesia dengan bahan alami terbaik.',
            'store_whatsapp'    => '6281234567890',
            'store_email'       => 'hello@glowandco.id',
            'store_address'     => 'Jl. Skincare Boulevard No. 12, Bandung',
            'rajaongkir_key'    => '',
            'rajaongkir_origin' => '',
            'bank_accounts'     => json_encode([
                ['bank' => 'BCA', 'number' => '1234567890', 'name' => 'GLOW&CO Skincare'],
                ['bank' => 'Mandiri', 'number' => '0987654321', 'name' => 'GLOW&CO Skincare'],
            ]),
        ];

        foreach ($defaults as $key => $value) {
            Setting::create(['key' => $key, 'value' => $value]);
        }
    }
}