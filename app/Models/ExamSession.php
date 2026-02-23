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

    public function examSubjectScores()
    {
        return $this->hasMany(ExamSubjectScore::class);
    }
}
