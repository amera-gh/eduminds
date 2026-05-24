<?php

namespace Database\Factories;

use App\Models\Achievement;
use App\Models\AchievementStudent;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AchievementStudent>
 */
class AchievementStudentFactory extends Factory
{
    protected $model = AchievementStudent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'achievement_id' => Achievement::factory(),
            'earned_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
