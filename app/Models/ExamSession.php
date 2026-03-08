<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'student_plan_id',
        'exam_mode',
        'total_questions',
        'duration_minutes',
        'score',
        'question_ids',
        'started_at',
        'completed_at',
        'hidden_by_student',
    ];

    protected $casts = [
        'question_ids' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'hidden_by_student' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }

    /**
     * The student plan that was granted when this session began, if any.
     */
    public function studentPlan()
    {
        return $this->belongsTo(StudentPlan::class);
    }

    /**
     * Determine whether this session was started under a plan that grants
     * a given feature (e.g. explanations, leaderboard, streak).
     *
     * This allows review/result pages to continue showing premium features
     * even if the user's currently active plan record has run out of
     * attempts.
     */
    public function hasFeature(string $feature): bool
    {
        if ($this->studentPlan && $this->studentPlan->plan) {
            $attribute = 'has_' . $feature;
            return (bool) ($this->studentPlan->plan->{$attribute} ?? false);
        }
        return false;
    }

    public function examSubjectScores()
    {
        return $this->hasMany(ExamSubjectScore::class);
    }
}
