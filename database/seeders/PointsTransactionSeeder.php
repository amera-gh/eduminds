<?php

namespace Database\Seeders;

use App\Models\PointsTransaction;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PointsTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();

        if ($students->isEmpty()) {
            $this->call(StudentSeeder::class);
            $students = Student::all();
        }

        foreach ($students as $student) {
            // Create 5-10 transactions for each student
            PointsTransaction::factory(rand(5, 10))->create([
                'student_id' => $student->id,
            ]);
        }
    }
}
