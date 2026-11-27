<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserPoint;
use App\Support\ActiveLearnerResolver;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    public function __construct(private readonly ActiveLearnerResolver $learnerResolver) {}

    public function __invoke(): View
    {
        $learner = $this->learnerResolver->resolve();

        $leaders = User::query()
            ->with('role')
            ->withCount('achievements')
            ->orderByDesc('xp_points')
            ->limit(20)
            ->get();

        $achievements = Achievement::query()
            ->withCount('users')
            ->orderBy('id')
            ->get();

        $earnedAchievementIds = $learner
            ? $learner->achievements()->pluck('achievements.id')->all()
            : [];

        $pointHistory = $learner
            ? UserPoint::query()
                ->where('user_id', $learner->id)
                ->latest('awarded_at')
                ->limit(10)
                ->get()
            : collect();

        return view('leaderboard.index', [
            'learner' => $learner,
            'leaders' => $leaders,
            'achievements' => $achievements,
            'earnedAchievementIds' => $earnedAchievementIds,
            'pointHistory' => $pointHistory,
        ]);
    }
}
