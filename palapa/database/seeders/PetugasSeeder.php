<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'petugas@example.com'],
            [
                'users_name' => 'Petugas Utama',
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'phone' => '081234567890',
            ]
        );

        for ($i = 1; $i <= 10; $i++) {
            User::updateOrCreate(
                ['email' => 'petugas' . $i . '@example.com'],
                [
                    'users_name' => 'Petugas ' . $i,
                    'password' => Hash::make('password'),
                    'role' => 'petugas',
                    'phone' => '08123456789' . $i,
                ]
            );
        }
    }
}