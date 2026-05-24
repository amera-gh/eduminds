<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'game_id',
        'skill_id',
        'status',
        'score',
        'mistakes',
        'hints_used',
        'attempts_count',

    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
