<?php

namespace Database\Factories;

use App\Models\Skill;
use App\Models\Student;
use App\Models\StudentSkillProgress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentSkillProgress>
 */
class StudentSkillProgressFactory extends Factory
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
            'skill_id' => Skill::factory(),
            'status' => $this->faker->randomElement(['not_started', 'in_progress', 'completed']),
            'score' => $this->faker->numberBetween(0, 100),
            'attempts_count' => $this->faker->numberBetween(1, 10),
        ];
    }
}
