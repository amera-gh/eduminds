<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\AchievementStudent;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $achievements = Achievement::all();

        if ($students->isEmpty()) {
            $this->call(StudentSeeder::class);
            $students = Student::all();
        }

        if ($achievements->isEmpty()) {
            $this->call(AchievementSeeder::class);
            $achievements = Achievement::all();
        }

        foreach ($students as $student) {
            // Each student earned 2-5 random achievements
            $randomAchievements = $achievements->random(rand(2, 5));

            foreach ($randomAchievements as $achievement) {
                AchievementStudent::factory()->create([
                    'student_id' => $student->id,
                    'achievement_id' => $achievement->id,
                ]);
            }
        }
    }
}
