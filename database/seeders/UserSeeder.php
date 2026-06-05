<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'name'     => 'Admin Glow',
            'email'    => 'admin@glowco.id',
            'password' => Hash::make('password'),
            'phone'    => '081234567890',
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Ronnan Ardi Robiantono',
            'email'    => 'Ronnan@gmail.com',
            'password' => Hash::make('password'),
            'phone'    => '081298765432',
            'role'     => 'customer',
        ]);
    }
}
