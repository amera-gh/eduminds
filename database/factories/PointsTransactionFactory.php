<?php

namespace Database\Factories;

use App\Models\PointsTransaction;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PointsTransaction>
 */
class PointsTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'source_type' => $this->faker->randomElement(['game_session', 'achievement', 'daily_login']),
            'source_id' => $this->faker->numberBetween(1, 100),
            'xp_points' => $this->faker->numberBetween(10, 500),
        ];
    }
}
