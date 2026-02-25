<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamTokenUsage extends Model
{
    public $timestamps = false;

    protected $table = 'exam_token_usage';

    protected $fillable = [
        'exam_token_id',
        'user_id',
        'exam_session_id',
        'used_at'
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function token()
    {
        return $this->belongsTo(ExamToken::class, 'exam_token_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function examSession()
    {
        return $this->belongsTo(ExamSession::class);
    }
}
