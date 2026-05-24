<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Grade::factory()->create(['name' => 'First Grade']);
        Grade::factory()->create(['name' => 'Second Grade']);
        Grade::factory(5)->create(); // Create 5 additional random grades
    }
}
