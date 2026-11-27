<?php

namespace App\Http\Controllers;

use App\Enums\AssessmentStatus;
use App\Enums\UserRole;
use App\Http\Requests\ReviewAssessmentRequest;
use App\Models\ActivityLog;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\LabChallenge;
use App\Models\Quiz;
use App\Models\Submission;
use App\Models\User;
use App\Support\ActiveLearnerResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(private readonly ActiveLearnerResolver $learnerResolver) {}

    public function index(): View
    {
        $learner = $this->learnerResolver->resolve();

        $assessmentsToReview = Assessment::query()
            ->with(['user', 'quiz', 'aiFeedback'])
            ->whereIn('status', [AssessmentStatus::NeedsReview, AssessmentStatus::Evaluated, AssessmentStatus::Approved])
            ->latest('id')
            ->limit(10)
            ->get();

        $recentLogs = ActivityLog::query()
            ->with('user:id,name')
            ->latest('created_at')
            ->limit(8)
            ->get();

        $stats = [
            'users' => User::query()->count(),
            'courses' => Course::query()->count(),
            'quizzes' => Quiz::query()->count(),
            'labs' => LabChallenge::query()->count(),
            'pending_reviews' => Assessment::query()->needsReview()->count(),
            'verified_submissions' => Submission::query()->where('is_correct', true)->count(),
        ];

        return view('admin.index', [
            'learner' => $learner,
            'assessmentsToReview' => $assessmentsToReview,
            'recentLogs' => $recentLogs,
            'stats' => $stats,
        ]);
    }

    public function reviewAssessment(ReviewAssessmentRequest $request, Assessment $assessment): RedirectResponse
    {
        $reviewer = User::query()
            ->whereHas('role', fn ($query) => $query->whereIn('slug', [UserRole::Mentor->value, UserRole::Admin->value]))
            ->first() ?? $this->learnerResolver->resolve();

        $validated = $request->validated();
        $assessment->loadMissing('aiFeedback');

        $assessment->update([
            'score' => $validated['score'],
            'status' => AssessmentStatus::from($validated['status']),
            'reviewed_by' => $reviewer?->id,
            'reviewed_at' => now(),
        ]);

        if ($assessment->aiFeedback && ! empty($validated['mentor_notes'])) {
            $assessment->aiFeedback->update([
                'mentor_notes' => $validated['mentor_notes'],
            ]);
        }

        ActivityLog::create([
            'user_id' => $reviewer?->id,
            'action' => 'assessment.mentor_reviewed',
            'subject_type' => Assessment::class,
            'subject_id' => $assessment->id,
            'metadata' => ['score' => $validated['score'], 'status' => $validated['status']],
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()
            ->route('admin.index')
            ->with('status', "Validasi mentor tersimpan untuk assessment #{$assessment->id}.");
    }
}
