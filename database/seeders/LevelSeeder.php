<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levelsData = [
            ['name' => 'Beginner', 'min_points' => 0, 'max_points' => 99, 'icon' => null],
            ['name' => 'Apprentice', 'min_points' => 100, 'max_points' => 299, 'icon' => null],
            ['name' => 'Master', 'min_points' => 300, 'max_points' => 599, 'icon' => null],
        ];

        foreach ($levelsData as $data) {
            Level::factory()->create($data);
        }

        // // Create more random levels
        // Level::factory(7)->create();
    }
}
