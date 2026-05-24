<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->resource === null) {
            return [];
        }
        return [

            'id' => $this->id,

            'current_level_id' => $this->current_level_id,

            'current_points' => $this->current_points,

            'longest_streak' => $this->longest_streak,

            'total_games_played' => $this->total_games_played,

        ];
    }
}
