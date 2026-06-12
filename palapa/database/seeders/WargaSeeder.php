<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WargaSeeder extends Seeder
{
    public function run(): void
    {
        
        User::updateOrCreate(
            ['email' => 'warga@example.com'],
            [
                'users_name' => 'Warga Utama',
                'password' => Hash::make('password'),
                'role' => 'masyarakat',
                'phone' => '087812345600',
            ]
        );

        
        User::updateOrCreate(
            ['email' => 'warga.aktif@example.com'],
            [
                'users_name' => 'Budi Aktif',
                'password' => Hash::make('password'),
                'role' => 'masyarakat',
                'phone' => '087812345601',
            ]
        );

        
        User::updateOrCreate(
            ['email' => 'warga.baru@example.com'],
            [
                'users_name' => 'Ani Baru',
                'password' => Hash::make('password'),
                'role' => 'masyarakat',
                'phone' => '087812345602',
            ]
        );

        
        User::updateOrCreate(
            ['email' => 'warga.valid@example.com'],
            [
                'users_name' => 'Candra Valid',
                'password' => Hash::make('password'),
                'role' => 'masyarakat',
                'phone' => '087812345603',
            ]
        );

        
        User::updateOrCreate(
            ['email' => 'warga.proses@example.com'],
            [
                'users_name' => 'Dedi Proses',
                'password' => Hash::make('password'),
                'role' => 'masyarakat',
                'phone' => '087812345604',
            ]
        );

        
        User::updateOrCreate(
            ['email' => 'warga.selesai@example.com'],
            [
                'users_name' => 'Eka Selesai',
                'password' => Hash::make('password'),
                'role' => 'masyarakat',
                'phone' => '087812345605',
            ]
        );

        
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => 'warga' . $i . '@example.com'],
                [
                    'users_name' => 'Warga ' . $i,
                    'password' => Hash::make('password'),
                    'role' => 'masyarakat',
                    'phone' => '08781234561' . $i,
                ]
            );
        }
    }
}
