<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\Student;
use App\Models\StudentSkillProgress;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSkillProgressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $skills = Skill::all();

        if ($students->isEmpty()) {
            $this->call(StudentSeeder::class);
            $students = Student::all();
        }

        if ($skills->isEmpty()) {
            $this->call(SkillSeeder::class);
            $skills = Skill::all();
        }

        foreach ($students as $student) {
            // Each student has progress in 5-10 random skills
            $randomSkills = $skills->random(rand(5, 10));

            foreach ($randomSkills as $skill) {
                StudentSkillProgress::factory()->create([
                    'student_id' => $student->id,
                    'skill_id' => $skill->id,
                ]);
            }
        }
    }
}
