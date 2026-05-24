<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'birth_date',
        'current_grade_id',
        'avatar'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'current_grade_id');
    }

    public function studentprofile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function progresses()
    {
        return $this->hasMany(StudentSkillProgress::class);
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class)
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    public function PointTransaction()
    {
        return $this->hasMany(PointsTransaction::class);
    }

    public function skillProgress()
    {
        return $this->hasMany(StudentSkillProgress::class);
    }

    public function learningGoals()
    {
        return $this->hasMany(StudentLearningTopic::class);
    }

    public function learningTopics()
    {
        return $this->belongsToMany(LearningTopic::class, 'student_learning_topics')
            ->withPivot('priority')
            ->withTimestamps()
            ->orderBy('priority');
    }

}
