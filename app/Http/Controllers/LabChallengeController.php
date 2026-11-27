<?php

namespace App\Http\Controllers;

use App\Enums\LabSessionStatus;
use App\Enums\PointSourceType;
use App\Http\Requests\SubmitLabFlagRequest;
use App\Models\ActivityLog;
use App\Models\LabChallenge;
use App\Models\LabSession;
use App\Models\Submission;
use App\Support\ActiveLearnerResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LabChallengeController extends Controller
{
    public function __construct(private readonly ActiveLearnerResolver $learnerResolver) {}

    public function index(Request $request): View
    {
        $learner = $this->learnerResolver->resolve();
        $category = $request->string('category')->trim()->toString();

        $labs = LabChallenge::query()
            ->active()
            ->with('course:id,title,slug')
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->orderBy('id')
            ->get();

        $categories = LabChallenge::query()
            ->active()
            ->distinct()
            ->pluck('category');

        $solvedLabIds = [];
        $activeSessions = collect();
        $recentSubmissions = collect();

        if ($learner) {
            $solvedLabIds = Submission::query()
                ->where('user_id', $learner->id)
                ->where('is_correct', true)
                ->pluck('lab_challenge_id')
                ->unique()
                ->all();

            $activeSessions = LabSession::query()
                ->where('user_id', $learner->id)
                ->where('status', LabSessionStatus::Running)
                ->get()
                ->keyBy('lab_challenge_id');

            $recentSubmissions = Submission::query()
                ->with('labChallenge:id,title,category')
                ->where('user_id', $learner->id)
                ->latest('id')
                ->limit(8)
                ->get();
        }

        return view('labs.index', [
            'learner' => $learner,
            'labs' => $labs,
            'categories' => $categories,
            'selectedCategory' => $category,
            'solvedLabIds' => $solvedLabIds,
            'activeSessions' => $activeSessions,
            'recentSubmissions' => $recentSubmissions,
        ]);
    }

    public function startSession(LabChallenge $labChallenge): RedirectResponse
    {
        $learner = $this->learnerResolver->resolve();

        if (! $learner) {
            return back()->with('error', 'Learner aktif tidak ditemukan.');
        }

        $session = LabSession::firstOrCreate(
            [
                'user_id' => $learner->id,
                'lab_challenge_id' => $labChallenge->id,
                'status' => LabSessionStatus::Running,
            ],
            [
                'container_id' => 'vlx-'.Str::lower(Str::random(8)),
                'started_at' => now(),
                'expires_at' => now()->addMinutes($labChallenge->time_limit_minutes),
            ]
        );

        ActivityLog::create([
            'user_id' => $learner->id,
            'action' => 'lab.session_started',
            'subject_type' => LabSession::class,
            'subject_id' => $session->id,
            'metadata' => ['container_id' => $session->container_id],
            'ip_address' => request()->ip(),
            'created_at' => now(),
        ]);

        return redirect()
            ->route('labs.index')
            ->with('status', "Sandbox container {$session->container_id} aktif untuk {$labChallenge->title}.");
    }

    public function submitFlag(SubmitLabFlagRequest $request, LabChallenge $labChallenge): RedirectResponse
    {
        $learner = $this->learnerResolver->resolve();

        if (! $learner) {
            return back()->with('error', 'Learner aktif tidak ditemukan.');
        }

        $submittedFlag = trim($request->validated('submitted_flag'));
        $isCorrect = hash_equals(trim($labChallenge->flag), $submittedFlag);
        $alreadySolved = Submission::query()
            ->where('user_id', $learner->id)
            ->where('lab_challenge_id', $labChallenge->id)
            ->where('is_correct', true)
            ->exists();

        $activeSession = LabSession::query()
            ->where('user_id', $learner->id)
            ->where('lab_challenge_id', $labChallenge->id)
            ->where('status', LabSessionStatus::Running)
            ->first();

        Submission::create([
            'user_id' => $learner->id,
            'lab_challenge_id' => $labChallenge->id,
            'lab_session_id' => $activeSession?->id,
            'submitted_flag' => $submittedFlag,
            'notes' => $request->validated('notes'),
            'is_correct' => $isCorrect,
            'score' => $isCorrect ? 100 : 0,
            'status' => $isCorrect ? 'verified' : 'rejected',
            'evaluated_at' => now(),
        ]);

        if (! $isCorrect) {
            return redirect()
                ->route('labs.index')
                ->with('error', 'Flag tidak valid. Periksa kembali output terminal di lingkungan sandbox.');
        }

        if ($activeSession) {
            $activeSession->update([
                'status' => LabSessionStatus::Completed,
                'completed_at' => now(),
            ]);
        }

        if (! $alreadySolved) {
            $this->learnerResolver->awardXp(
                user: $learner,
                sourceType: PointSourceType::Lab,
                points: $labChallenge->xp_reward,
                description: 'Solved Cyber Lab: '.$labChallenge->title,
                sourceId: $labChallenge->id,
                action: 'lab.solved',
                subjectType: LabChallenge::class,
                ipAddress: $request->ip(),
            );
        }

        return redirect()
            ->route('labs.index')
            ->with('status', $alreadySolved
                ? "Flag valid! Challenge {$labChallenge->title} sudah terselesaikan sebelumnya."
                : "Flag valid! +{$labChallenge->xp_reward} XP ditambahkan ke akun Anda.");
    }
}
