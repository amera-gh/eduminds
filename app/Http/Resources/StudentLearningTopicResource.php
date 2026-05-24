<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentLearningTopicResource extends JsonResource
{
    public function toArray(Request $request): array|null
    {
        if ($this->resource === null) {
            return null;
        }

        return [
            'id' => $this->id,
            'priority' => $this->priority,
            'topic' => $this->topic ? new LearningTopicResource($this->topic) : null,
        ];
    }
}