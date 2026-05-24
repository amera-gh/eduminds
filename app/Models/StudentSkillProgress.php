<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSkillProgress extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'skill_id',
        'status',
        'score',
        'attempts_count'
    ];

    protected $casts = [
        'score' => 'integer',
        'attempts_count' => 'integer',
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
