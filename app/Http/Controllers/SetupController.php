<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Skill;
use App\Models\LearningTopic;
use App\Http\Resources\GradeResource;
use App\Http\Resources\SkillResource;
use App\Http\Resources\LearningTopicResource;

class SetupController extends Controller
{
    /**
     * Get all grades
     */
    public function getGrades()
    {
        $grades = Grade::orderBy('id')->get();

        return response()->json([
            'success' => true,
            'data' => GradeResource::collection($grades),
        ]);
    }

    /**
     * Get all skills
     */
    public function getSkills()
    {
        $skills = Skill::with('grade')
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => SkillResource::collection($skills),
        ]);
    }

    /**
     * Get all learning topics
     */
    public function GetLearningTopics()
    {
        $topics = LearningTopic::orderBy('order_index')->get();

        return response()->json([
            'success' => true,
            'data' => LearningTopicResource::collection($topics),
        ]);
    }
}