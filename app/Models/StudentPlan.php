<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class StudentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'plan_id',
        'attempts_remaining',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function isExpired(): bool
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return true;
        }
        return false;
    }

    public function isActive(): bool
    {
        return !$this->isExpired() && $this->attempts_remaining > 0;
    }
}
