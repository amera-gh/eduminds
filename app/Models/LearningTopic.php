<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LearningTopic extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
        'color',
        'order_index',
        'is_active',
    ];

    protected $casts = [
        'order_index' => 'integer',
        'is_active' => 'boolean',
    ];


    public function studentTopics(): HasMany
    {
        return $this->hasMany(StudentLearningTopic::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_learning_topics')
            ->withPivot('priority')
            ->withTimestamps();
    }
}