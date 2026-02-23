<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSubjectScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_session_id',
        'subject_id',
        'correct_count',
        'score_over_100',
    ];

    protected $casts = [
        'score_over_100' => 'decimal:2',
    ];

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
