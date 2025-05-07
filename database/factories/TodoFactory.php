<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'status' => $this->faker->randomElement(['pending', 'in-progress', 'completed']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
