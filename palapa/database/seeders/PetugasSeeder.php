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
                'latitude' => -2.2083, // Kalimantan (Palangkaraya)
                'longitude' => 113.9161,
            ]
        );

        // Daftar Pos Pemadam Kebakaran yang tersebar di kota/kabupaten Kalimantan
        $posPemadam = [
            // Kalimantan Tengah
            ['name' => 'Pos Induk Palangkaraya', 'lat' => -2.2083, 'lng' => 113.9161],
            ['name' => 'Pos Jekan Raya (Palangkaraya)', 'lat' => -2.1850, 'lng' => 113.8950],
            ['name' => 'Pos Induk Sampit', 'lat' => -1.2384, 'lng' => 112.9463],
            ['name' => 'Pos Induk Pangkalan Bun', 'lat' => -2.6841, 'lng' => 111.6214],
            
            // Kalimantan Selatan
            ['name' => 'Pos Induk Banjarmasin', 'lat' => -3.3166, 'lng' => 114.5901],
            ['name' => 'Pos Banjarbaru', 'lat' => -3.4400, 'lng' => 114.8300],
            ['name' => 'Pos Amuntai', 'lat' => -2.3456, 'lng' => 115.4855],

            // Kalimantan Timur
            ['name' => 'Pos Induk Balikpapan', 'lat' => -1.2379, 'lng' => 116.8528],
            ['name' => 'Pos Balikpapan Utara', 'lat' => -1.1850, 'lng' => 116.8600],
            ['name' => 'Pos Induk Samarinda', 'lat' => -0.5022, 'lng' => 117.1536],
            ['name' => 'Pos Samarinda Seberang', 'lat' => -0.5400, 'lng' => 117.1400],
            ['name' => 'Pos Induk Bontang', 'lat' => 0.1322, 'lng' => 117.4721],

            // Kalimantan Barat
            ['name' => 'Pos Induk Pontianak', 'lat' => -0.0227, 'lng' => 109.3333],
            ['name' => 'Pos Pontianak Timur', 'lat' => -0.0350, 'lng' => 109.3600],
            ['name' => 'Pos Induk Sintang', 'lat' => 0.9083, 'lng' => 108.9744],
            ['name' => 'Pos Induk Ketapang', 'lat' => -1.8491, 'lng' => 109.9701],
            ['name' => 'Pos Melawi', 'lat' => -0.7893, 'lng' => 111.1444],

            // Kalimantan Utara
            ['name' => 'Pos Induk Tarakan', 'lat' => 3.3050, 'lng' => 117.6325],
            ['name' => 'Pos Nunukan', 'lat' => 3.5971, 'lng' => 116.5985],
            ['name' => 'Pos Malinau', 'lat' => 2.1673, 'lng' => 115.8078],
        ];

        for ($i = 1; $i <= 50; $i++) {
            // Pilih satu Pos secara acak
            $pos = $posPemadam[array_rand($posPemadam)];
            
            // Jitter sangat kecil (sekitar 10-50 meter) agar marker petugas 
            // tidak saling menumpuk 100% di peta pada satu titik yang persis sama,
            // namun secara visual terlihat bergerombol di pos yang sama.
            $jitterLat = mt_rand(-500, 500) / 1000000;
            $jitterLng = mt_rand(-500, 500) / 1000000;

            User::updateOrCreate(
                ['email' => 'petugas' . $i . '@example.com'],
                [
                    'users_name' => 'Petugas ' . $i . ' (' . $pos['name'] . ')',
                    'password' => Hash::make('password'),
                    'role' => 'petugas',
                    'phone' => '0812345678' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'latitude' => $pos['lat'] + $jitterLat,
                    'longitude' => $pos['lng'] + $jitterLng,
                ]
            );
        }
    }
}