<?php

namespace Database\Factories;

use App\Models\StudentProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<StudentProfile>
 */
class StudentProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'current_points' => fake()->numberBetween(0, 10000),
            'current_streak' => fake()->numberBetween(0, 50),
            'longest_streak' => fake()->numberBetween(0, 100),
            'total_games_played' => fake()->numberBetween(0, 500),
            // foreign keys student_id, current_grade_id, current_level_id will be set by the seeder
        ];
    }

    /**
     * Configure the factory to associate with a specific student.
     */
    public function forStudent($studentId)
    {
        return $this->state(fn (array $attributes) => [
            'student_id' => $studentId,
        ]);
    }

    /**
     * Configure the factory to associate with a specific grade.
     */
    public function forGrade($gradeId)
    {
        return $this->state(fn (array $attributes) => [
            'current_grade_id' => $gradeId,
        ]);
    }

    /**
     * Configure the factory to associate with a specific level.
     */
    public function forLevel($levelId)
    {
        return $this->state(fn (array $attributes) => [
            'current_level_id' => $levelId,
        ]);
    }
}
