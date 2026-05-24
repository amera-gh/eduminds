<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>|null
     */
    public function toArray(Request $request): array|null
    {
        if ($this->resource === null) {
            return null;
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'avatar' => $this->avatar ?? null,
            'grade' => $this->grade ? new GradeResource($this->grade) : null,
            'skill_progress' => $this->relationLoaded('skillProgress')
                ? StudentSkillProgressResource::collection($this->skillProgress)
                : [],
            'learning_topics' => $this->relationLoaded('learningTopics')
                ? StudentLearningTopicResource::collection($this->learningGoals)
                : [],
            'profile' => $this->studentprofile ? new StudentProfileResource($this->studentprofile) : null,
            
        ];
    }
}