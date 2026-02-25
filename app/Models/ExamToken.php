<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ExamToken extends Model
{
    protected $fillable = [
        'code',
        'max_uses',
        'used_count',
        'is_active',
        'created_by',
        'expires_at',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function usages()
    {
        return $this->hasMany(ExamTokenUsage::class);
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function remainingUses(): int
    {
        return max(0, $this->max_uses - $this->used_count);
    }

    public function use(User $user, $examSessionId = null): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        ExamTokenUsage::create([
            'exam_token_id' => $this->id,
            'user_id' => $user->id,
            'exam_session_id' => $examSessionId,
            'used_at' => now(),
        ]);

        $this->increment('used_count');

        return true;
    }

    public function hasBeenUsedBy(User $user): bool
    {
        return $this->usages()->where('user_id', $user->id)->exists();
    }

    public static function generateCode(): string
    {
        do {
            $code = strtoupper(Str::random(3) . '-' . Str::random(3) . '-' . Str::random(3));
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
