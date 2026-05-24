<?php

namespace Database\Seeders;

use App\Models\StudentProfile;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentProfileSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure dependencies exist
        if (Student::count() < 1) {
            $this->call([StudentSeeder::class]);
        }
        if (Grade::count() < 1) {
            $this->call([GradeSeeder::class]);
        }
        if (Level::count() < 1) {
            $this->call([LevelSeeder::class]);
        }

        $students = Student::all();
        $grades = Grade::all();
        $levels = Level::all();

        // Ensure we have data to work with
        if ($students->isEmpty() || $grades->isEmpty() || $levels->isEmpty()) {
            throw new \Exception('Dependencies for StudentProfileSeeder are not met. Please ensure Student, Grade, and Level seeders are run and populated.');
        }

        // Create a profile for each student
        foreach ($students as $student) {
            StudentProfile::factory()
                ->forStudent($student->id)
                ->forGrade($grades->random()->id)
                ->forLevel($levels->random()->id)
                ->create();
        }
    }
}
