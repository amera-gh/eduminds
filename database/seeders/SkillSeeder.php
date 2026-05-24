<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;
use App\Models\Grade;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $grade7 = Grade::where('name', 'السابع')->first();

        if (!$grade7) {
            return;
        }

        $skills = [
            [
                'title' => 'الرياضة',
                'content' => 'الاهتمام بالأنشطة الرياضية',
            ],
            [
                'title' => 'الألعاب',
                'content' => 'الاهتمام بالألعاب الإلكترونية والذكاء',
            ],
            [
                'title' => 'الرسم',
                'content' => 'حب الرسم والتلوين والتصميم',
            ],
            [
                'title' => 'الموسيقى',
                'content' => 'الاهتمام بالموسيقى والعزف',
            ],
            [
                'title' => 'القراءة',
                'content' => 'حب قراءة القصص والكتب',
            ],
            [
                'title' => 'البرمجة',
                'content' => 'الاهتمام بالتقنية والبرمجة',
            ],
            [
                'title' => 'العلوم',
                'content' => 'حب التجارب والاستكشاف العلمي',
            ],
            [
                'title' => 'التصوير',
                'content' => 'الاهتمام بالتصوير وصناعة المحتوى',
            ],
        ];

        foreach ($skills as $skill) {
            Skill::create([
                'grade_id' => $grade7->id,
                'title' => $skill['title'],
                'content' => $skill['content'],
                'xp_reward'=>100
            ]);
        }
    }
}