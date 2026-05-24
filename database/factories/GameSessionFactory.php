<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\Skill;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameSession>
 */
class GameSessionFactory extends Factory
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
            'game_id' => Game::factory(),
            'skill_id' => Skill::factory(),
            'status' => $this->faker->randomElement(['not_started', 'failed', 'completed']),
            'score' => $this->faker->numberBetween(0, 100),
            'mistakes' => $this->faker->numberBetween(0, 20),
            'hints_used' => json_encode(['hint1', 'hint2']),
            'attempts_count' => $this->faker->numberBetween(1, 5),
        ];
    }
}
