<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $games = Game::all();

        if ($students->isEmpty()) {
            $this->call(StudentSeeder::class);
            $students = Student::all();
        }

        if ($games->isEmpty()) {
            $this->call(GameSeeder::class);
            $games = Game::all();
        }

        foreach ($students as $student) {
            // Each student plays 3-5 random games
            $randomGames = $games->random(rand(3, 5));

            foreach ($randomGames as $game) {
                GameSession::factory()->create([
                    'student_id' => $student->id,
                    'game_id' => $game->id,
                    'skill_id' => $game->skill_id,
                ]);
            }
        }
    }
}
