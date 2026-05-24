<?php

namespace Database\Factories;

use App\Models\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Level>
 */
class LevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $minPoints = 0;
        $maxPoints = $minPoints + fake()->numberBetween(100, 500);

        $level = [
            'name' => 'Level ' . fake()->unique()->numberBetween(1, 20),
            'min_points' => $minPoints,
            'max_points' => $maxPoints,
            'icon' => fake()->imageUrl(64, 64, 'abstract'),
        ];

        $minPoints = $maxPoints + 1;

        return $level;
    }
}
