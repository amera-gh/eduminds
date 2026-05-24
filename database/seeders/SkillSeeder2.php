<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there are grades available for skills
        if (Grade::count() < 1) {
            $this->call([GradeSeeder::class]);
        }

        $grades = Grade::all();
        // if ($grades->isEmpty()) {
        //     // Fallback if GradeSeeder failed or is not registered
        //     // GradeSeeder::run();
        //     $grades = Grade::all();
        // }

        // Create 50 skills, associating each with a random grade.
        for ($i = 0; $i < 50; $i++) {
            $randomGrade = $grades->random();
            Skill::factory()->forGrade($randomGrade->id)->create();
        }
    }
}
