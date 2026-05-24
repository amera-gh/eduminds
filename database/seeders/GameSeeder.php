<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = Skill::all();

        if ($skills->isEmpty()) {
            $this->call(SkillSeeder::class);
            $skills = Skill::all();
        }

        foreach ($skills as $skill) {
            // Create a game for each skill
            Game::factory()->create([
                'skill_id' => $skill->id,
            ]);
        }
    }
}
