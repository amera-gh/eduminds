<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentLearningTopic extends Model
{
    protected $table = 'student_learning_topics';

    protected $fillable = [
        'student_id',
        'learning_topic_id',
        'priority',
    ];

    protected $casts = [
        'priority' => 'integer',
    ];


    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function topic()
    {
        return $this->belongsTo(LearningTopic::class, 'learning_topic_id');
    }
}