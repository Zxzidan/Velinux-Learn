<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'role_id',
    'name',
    'username',
    'email',
    'cohort',
    'xp_points',
    'current_streak',
    'last_activity_date',
    'avatar_url',
    'bio',
    'password',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_activity_date' => 'date',
            'xp_points' => 'integer',
            'current_streak' => 'integer',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function createdCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'created_by');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function completedLessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_completions')
            ->withPivot('completed_at')
            ->withTimestamps();
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(Assessment::class);
    }

    public function labSessions(): HasMany
    {
        return $this->hasMany(LabSession::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class)
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    public function points(): HasMany
    {
        return $this->hasMany(UserPoint::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function hasRole(UserRole $role): bool
    {
        return $this->role?->slug === $role->value;
    }

    /**
     * Compute rank title from accumulated XP points.
     */
    public function cyberRank(): string
    {
        return match (true) {
            $this->xp_points >= 3000 => 'Red Team Lead',
            $this->xp_points >= 1800 => 'Security Analyst',
            $this->xp_points >= 900 => 'Cyber Operator',
            $this->xp_points >= 300 => 'Linux Explorer',
            default => 'Cadet',
        };
    }
}
