<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $grades = [
            ['name' => 'الأول', 'is_enable' => false],
            ['name' => 'الثاني', 'is_enable' => false],
            ['name' => 'الثالث', 'is_enable' => false],
            ['name' => 'الرابع', 'is_enable' => false],
            ['name' => 'الخامس', 'is_enable' => false],
            ['name' => 'السادس', 'is_enable' => false],
            ['name' => 'السابع', 'is_enable' => true],
            ['name' => 'الثامن', 'is_enable' => false],
            ['name' => 'التاسع', 'is_enable' => false],
            ['name' => 'العاشر', 'is_enable' => false],
            ['name' => 'الحادي عشر', 'is_enable' => false],
            ['name' => 'الثاني عشر', 'is_enable' => false],
        ];

        foreach ($grades as $grade) {
            Grade::create($grade);
        }
    }
}