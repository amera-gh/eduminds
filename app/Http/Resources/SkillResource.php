<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillResource extends JsonResource
{
    public function toArray(Request $request): array|null
    {
        if ($this->resource === null) {
            return null;
        }

        return [
            'id' => $this->id,
            'grade_id' => $this->grade_id,
            'title' => $this->title,
            'content' => $this->content,
            'order_index' => $this->order_index,
            'xp_reward' => $this->xp_reward,
        ];
    }
}