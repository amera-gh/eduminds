<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            //UserSeeder::class,
            //StudentSeeder::class,
            GradeSeeder::class,
            LevelSeeder::class,

            //StudentProfileSeeder::class,
            SkillSeeder::class,
            LearningTopicsSeeder::class,
            //AchievementSeeder::class,
            //GameSeeder::class,
            //GameSessionSeeder::class,
            //PointsTransactionSeeder::class,
            //StudentSkillProgressSeeder::class,
            //AchievementStudentSeeder::class,
        ]);
    }
}
