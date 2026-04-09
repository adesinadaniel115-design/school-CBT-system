<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\StudentPlan;
use App\Models\Plan;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'student_id',
        'profile_photo_path',
        'center_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Check if email is verified
     */
    public function hasVerifiedEmail()
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    public function examSessions()
    {
        return $this->hasMany(ExamSession::class, 'student_id');
    }

    /**
     * Plans that have been granted to the student (via token redemption).
     */
    public function studentPlans()
    {
        return $this->hasMany(StudentPlan::class, 'student_id');
    }

    /**
     * Return the active (non-expired, with remaining attempts) plan for the student.
     *
     * If the user has never redeemed a plan-bearing token this will return null, which
     * is interpreted by the gating logic as "legacy/unrestricted" behaviour.
     */
    public function activePlan()
    {
        // if the student is currently in the middle of an exam that was
        // started with a plan grant, honour that plan for the duration of
        // the session even if the underlying record has been decremented to
        // zero.  This ensures features such as explanations/leaderboard remain
        // available until the exam is finished.
        $ongoingSession = $this->examSessions()
            ->whereNull('completed_at')
            ->whereNotNull('student_plan_id')
            ->latest()
            ->first();

        if ($ongoingSession && $ongoingSession->studentPlan) {
            return $ongoingSession->studentPlan->plan;
        }

        // normal behaviour: return the most-recent, non-expired plan with
        // remaining attempts.
        $planRecord = $this->studentPlans()
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where('attempts_remaining', '>', 0)
            ->orderBy('expires_at', 'desc')
            ->first();

        return $planRecord ? $planRecord->plan : null;
    }

    /**
     * Helper to determine whether the current student has offline package access.
     */
    public function hasActivePackage(): bool
    {
        $planRecord = $this->studentPlans()
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where('attempts_remaining', '>', 0)
            ->orderBy('expires_at', 'desc')
            ->first();

        return (bool) $planRecord;
    }

    /**
     * Helper to determine whether the current student is allowed a named feature.
     *
     * Supported feature names correspond to the boolean columns on the `plans`
     * table (e.g. "explanations", "leaderboard", "streak").
     *
     * If the user has no active plan we treat them as having **no features**.
     * This makes the system operate strictly on a paid‑plan basis; legacy
     * behaviour can be restored by adjusting this method or adding a config
     * toggle in future.
     */
    public function hasFeature(string $feature): bool
    {
        $plan = $this->activePlan();
        if (!$plan) {
            return false;
        }

        $attribute = 'has_' . $feature;
        return (bool) ($plan->{$attribute} ?? false);
    }

    /**
     * Get the center this user (student) belongs to
     */
    public function center()
    {
        return $this->belongsTo(Center::class);
    }
}
