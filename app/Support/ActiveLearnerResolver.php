<?php

namespace App\Support;

use App\Enums\PointSourceType;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActiveLearnerResolver
{
    /**
     * Resolve the currently active learner (authenticated user or primary demo student).
     */
    public function resolve(): ?User
    {
        $authenticated = Auth::user();

        if ($authenticated instanceof User) {
            return $authenticated->loadMissing('role');
        }

        return User::query()
            ->with('role')
            ->where('email', 'raka@velinux.test')
            ->first()
            ?? User::query()->with('role')->oldest('id')->first();
    }

    /**
     * Award XP points to a learner inside a database transaction and record an activity entry.
     */
    public function awardXp(
        User $user,
        PointSourceType $sourceType,
        int $points,
        string $description,
        ?int $sourceId = null,
        string $action = 'points.awarded',
        ?string $subjectType = null,
        ?string $ipAddress = null,
    ): void {
        if ($points <= 0) {
            return;
        }

        DB::transaction(function () use ($user, $sourceType, $points, $description, $sourceId, $action, $subjectType, $ipAddress): void {
            $user->increment('xp_points', $points);
            $user->forceFill(['last_activity_date' => now()->toDateString()])->save();

            UserPoint::create([
                'user_id' => $user->id,
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'points' => $points,
                'description' => $description,
                'awarded_at' => now(),
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'subject_type' => $subjectType,
                'subject_id' => $sourceId,
                'metadata' => ['points' => $points, 'description' => $description],
                'ip_address' => $ipAddress,
                'created_at' => now(),
            ]);
        });
    }
}
