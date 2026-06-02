<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'users_name' => 'Test User',
                'email' => 'test@example.com', 
            ]);
        }

        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'users_name' => 'Admin Utama',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567891',
            ]
        );

        $this->call(PetugasSeeder::class);
    }
}
