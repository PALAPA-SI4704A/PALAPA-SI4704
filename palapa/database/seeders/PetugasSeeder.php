<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'users_name' => 'Petugas Utama',
            'email' => 'petugas@example.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'phone' => '081234567890',
        ]);

        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'users_name' => 'Petugas ' . $i,
                'email' => 'petugas' . $i . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'phone' => '08123456789' . $i,
            ]);
        }
    }
}