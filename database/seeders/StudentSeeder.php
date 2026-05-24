<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure there are enough users to associate with students
        // If not, create some users first.
        if (User::count() < 20) {
            User::factory(20)->create();
        }

        // Create 15 students, each associated with a random existing user.
        $users = User::inRandomOrder()->take(15)->get();

        foreach ($users as $user) {
            Student::factory()->withUser($user->id)->create();
        }

        // Create additional students without explicit user association (if user_id is nullable)
        // or for testing purposes if needed.
        Student::factory(5)->create(); 
    }
}
