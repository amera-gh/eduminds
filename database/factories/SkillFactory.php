<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word() . ' Skill',
            'content' => fake()->paragraph(),
            'order_index' => fake()->numberBetween(0, 100),
            'xp_reward' => fake()->numberBetween(10, 100),
            // grade_id will be set by the seeder
        ];
    }

    /**
     * Configure the factory to associate with a specific grade.
     */
    public function forGrade($gradeId)
    {
        return $this->state(fn (array $attributes) => [
            'grade_id' => $gradeId,
        ]);
    }
}
