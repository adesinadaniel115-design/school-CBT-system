<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Plan;
use App\Models\StudentPlan;

class ExamToken extends Model
{
    protected $fillable = [
        'code',
        'max_uses',
        'used_count',
        'is_active',
        'created_by',
        'expires_at',
        'notes',
        'center_id',
        'plan_id',
        'bound_user_id',
        'sharing_detected',
        'first_used_ip',
        'first_used_device',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'sharing_detected' => 'boolean',
    ];

    /**
     * Optional plan associated with this token (nullable for backwards compatibility).
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Token center (optional)
     */
    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function usages()
    {
        return $this->hasMany(ExamTokenUsage::class);
    }

    public function boundUser()
    {
        return $this->belongsTo(User::class, 'bound_user_id');
    }

    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        // plan-bearing tokens are allowed unlimited uses (access controlled separately)
        if (!$this->plan_id && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function remainingUses(): int
    {
        return max(0, $this->max_uses - $this->used_count);
    }

    /**
     * Determine whether a token is being shared (bound to a different student).
     */
    public function isBeingShared(User $currentUser): bool
    {
        return $this->bound_user_id && $this->bound_user_id !== $currentUser->id;
    }

    /**
     * Use the token, binding it on first use and preventing sharing.
     * Additional metadata (IP/user agent) may be supplied for later review.
     */
    public function use(User $user, $examSessionId = null, $ipAddress = null, $userAgent = null): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        // if previously bound to someone else, mark sharing and deny
        if ($this->isBeingShared($user)) {
            $this->update(['sharing_detected' => true]);
            return false;
        }

        // bind token to first user
        if (!$this->bound_user_id) {
            $this->update([
                'bound_user_id' => $user->id,
                'first_used_ip' => $ipAddress,
                'first_used_device' => $userAgent ? substr($userAgent, 0, 255) : null,
            ]);
        }

        ExamTokenUsage::create([
            'exam_token_id' => $this->id,
            'user_id' => $user->id,
            'exam_session_id' => $examSessionId,
            'used_at' => now(),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);

        $this->increment('used_count');

        // plan handling: if this token is tied to a plan and the plans table exists
        if ($this->plan_id && \Schema::hasTable('plans')) {
            /** @var \App\Models\Plan $plan */
            $plan = $this->plan;

            if ($plan) {
                $expiresAt = null;
                if ($plan->duration_days !== null) {
                    $expiresAt = now()->addDays($plan->duration_days);
                }

                StudentPlan::create([
                    'student_id' => $user->id,
                    'plan_id' => $plan->id,
                    'attempts_remaining' => $plan->attempts_allowed,
                    'expires_at' => $expiresAt,
                ]);
            }
        }

        return true;
    }

    public function hasBeenUsedBy(User $user): bool
    {
        return $this->usages()->where('user_id', $user->id)->exists();
    }

    public static function generateCode(): string
    {
        // Use database-level uniqueness with proper retry logic
        $maxAttempts = 500; // Much higher limit for reliability
        $attempt = 0;

        while ($attempt < $maxAttempts) {
            $code = strtoupper(Str::random(3) . '-' . Str::random(3) . '-' . Str::random(3));

            // Simple existence check
            $exists = DB::table('exam_tokens')
                ->where('code', $code)
                ->exists();

            if (!$exists) {
                return $code;
            }

            $attempt++;
        }

        throw new \Exception('Failed to generate unique token code after ' . $maxAttempts . ' attempts. The token table appears to be completely full.');
    }
}
