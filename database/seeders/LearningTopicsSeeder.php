<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LearningTopic;

class LearningTopicsSeeder extends Seeder
{
    public function run(): void
    {
        $topics = [
            [
                'name' => 'التعلّم البصري',
                'description' => 'التعلّم باستخدام الصور والمخططات والفيديوهات',
                'icon' => 'eye',
                'color' => '#3B82F6',
                'order_index' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'التعلّم السمعي',
                'description' => 'التعلّم من خلال الاستماع والشرح الصوتي',
                'icon' => 'headphones',
                'color' => '#10B981',
                'order_index' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'التعلّم العملي',
                'description' => 'التعلّم بالتجربة والتطبيق العملي',
                'icon' => 'tool',
                'color' => '#F59E0B',
                'order_index' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'التعلّم بالقراءة',
                'description' => 'التعلّم من خلال القراءة والكتابة',
                'icon' => 'book-open',
                'color' => '#EF4444',
                'order_index' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'التعلّم التفاعلي',
                'description' => 'التعلّم باستخدام الألعاب والأنشطة التفاعلية',
                'icon' => 'gamepad',
                'color' => '#8B5CF6',
                'order_index' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($topics as $topic) {
            LearningTopic::create($topic);
        }
    }
}