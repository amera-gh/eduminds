<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'badge_icon',
        'condition_value',
        'xp_reward',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class)
            ->withPivot('earned_at')
            ->withTimestamps();
    }
}
