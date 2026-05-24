<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class AchievementSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a few specific achievements
        Achievement::factory()->create([
            'name' => 'First Steps',
            'description' => 'Complete your first skill.',
            'condition_value' => 1,
            'xp_reward' => 25,
        ]);

        Achievement::factory()->create([
            'name' => 'Mastermind',
            'description' => 'Achieve 100% in 10 skills.',
            'condition_value' => 10,
            'xp_reward' => 200,
        ]);

        // Create 30 more random achievements
        Achievement::factory(30)->create();
    }
}
