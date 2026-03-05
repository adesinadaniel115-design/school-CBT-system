<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'attempts_allowed',
        'duration_days',
        'has_explanations',
        'has_leaderboard',
        'has_streak',
        // new fields for question counts (nullable so old plans keep defaults)
        'school_questions',
        'jamb_questions_per_subject',
        'jamb_english_questions',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'has_explanations' => 'boolean',
        'has_leaderboard' => 'boolean',
        'has_streak' => 'boolean',
        'school_questions' => 'integer',
        'jamb_questions_per_subject' => 'integer',
        'jamb_english_questions' => 'integer',
    ];

    public function tokens()
    {
        return $this->hasMany(ExamToken::class);
    }

    public function studentPlans()
    {
        return $this->hasMany(StudentPlan::class);
    }
}
