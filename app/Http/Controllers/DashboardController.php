<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LabChallenge;
use App\Models\LabSession;
use App\Models\Quiz;
use App\Models\Submission;
use App\Models\User;
use App\Models\UserPoint;
use App\Support\ActiveLearnerResolver;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly ActiveLearnerResolver $learnerResolver) {}

    public function __invoke(): View
    {
        $learner = $this->learnerResolver->resolve();

        $courses = Course::query()
            ->published()
            ->withCount('modules')
            ->oldest('id')
            ->limit(3)
            ->get();

        $enrollmentsByCourse = collect();
        $latestAssessment = null;
        $solvedLabIds = [];
        $activeLabSessions = collect();
        $recentPoints = collect();
        $achievementsCount = 0;

        if ($learner) {
            $learner->loadCount('achievements');
            $achievementsCount = $learner->achievements_count;

            $enrollmentsByCourse = Enrollment::query()
                ->where('user_id', $learner->id)
                ->get()
                ->keyBy('course_id');

            $latestAssessment = Assessment::query()
                ->with(['user', 'quiz', 'aiFeedback'])
                ->where('user_id', $learner->id)
                ->latest('id')
                ->first();

            $solvedLabIds = Submission::query()
                ->where('user_id', $learner->id)
                ->where('is_correct', true)
                ->pluck('lab_challenge_id')
                ->unique()
                ->all();

            $activeLabSessions = LabSession::query()
                ->where('user_id', $learner->id)
                ->where('status', 'running')
                ->get()
                ->keyBy('lab_challenge_id');

            $recentPoints = UserPoint::query()
                ->where('user_id', $learner->id)
                ->latest('awarded_at')
                ->limit(4)
                ->get();
        }

        $dailyQuizzes = Quiz::query()
            ->published()
            ->withCount('questions')
            ->orderByDesc('is_daily')
            ->limit(1)
            ->get();

        $featuredLabs = LabChallenge::query()
            ->active()
            ->limit(2)
            ->get();

        $leaders = User::query()
            ->with('role')
            ->orderByDesc('xp_points')
            ->limit(5)
            ->get();

        $averageProgress = $enrollmentsByCourse->isNotEmpty()
            ? (int) round($enrollmentsByCourse->avg('progress_percentage'))
            : 0;

        return view('dashboard', [
            'learner' => $learner,
            'courses' => $courses,
            'enrollmentsByCourse' => $enrollmentsByCourse,
            'averageProgress' => $averageProgress,
            'latestAssessment' => $latestAssessment,
            'dailyQuizzes' => $dailyQuizzes,
            'featuredLabs' => $featuredLabs,
            'solvedLabIds' => $solvedLabIds,
            'activeLabSessions' => $activeLabSessions,
            'leaders' => $leaders,
            'recentPoints' => $recentPoints,
            'achievementsCount' => $achievementsCount,
            'publishedCourseCount' => Course::query()->published()->count(),
            'activeLabCount' => LabChallenge::query()->active()->count(),
        ]);
    }
}
