<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'explanation',
        'difficulty_level',
        'image',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function examAnswers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
