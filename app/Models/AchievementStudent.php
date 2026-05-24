<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AchievementStudent extends Pivot
{
    use HasFactory;

    protected $table = 'achievement_student';

    protected $fillable = [
        'student_id',
        'achievement_id',
        'earned_at',
    ];

    public $timestamps = true;
}
