<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['skill_id'];

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function sessions()
    {
        return $this->hasMany(GameSession::class);
    }
}
