<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'users_name' => 'Admin Utama',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567891',
            ]
        );

    }
}
