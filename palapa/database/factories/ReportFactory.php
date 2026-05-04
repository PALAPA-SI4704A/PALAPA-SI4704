<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'masyarakat'])->users_id,
            'admin_id' => User::factory()->create(['role' => 'admin'])->users_id,
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'photo' => fake()->imageUrl(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'address' => fake()->address(),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'assigned']),
            'rejection_reason' => fake()->optional()->sentence(),
        ];
    }
}