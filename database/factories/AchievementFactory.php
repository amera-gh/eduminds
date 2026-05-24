<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Achievement>
 */
class AchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word() . ' Achievement',
            'description' => fake()->sentence(),
            'badge_icon' => fake()->imageUrl(64, 64, 'abstract'),
            'condition_value' => fake()->numberBetween(1, 100),
            'xp_reward' => fake()->numberBetween(50, 500),
        ];
    }
}
