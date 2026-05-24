<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'source_type',
        'source_id',
        'xp_points',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
