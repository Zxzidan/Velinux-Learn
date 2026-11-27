<?php

namespace App\Http\Controllers;

use App\Enums\AssessmentStatus;
use App\Enums\PointSourceType;
use App\Enums\QuestionType;
use App\Http\Requests\SubmitQuizRequest;
use App\Models\AiFeedback;
use App\Models\Answer;
use App\Models\Assessment;
use App\Models\Quiz;
use App\Support\ActiveLearnerResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function __construct(private readonly ActiveLearnerResolver $learnerResolver) {}

    public function index(): View
    {
        $learner = $this->learnerResolver->resolve();

        $quizzes = Quiz::query()
            ->published()
            ->with(['course:id,title,slug', 'questions'])
            ->withCount('questions')
            ->orderByDesc('is_daily')
            ->orderBy('id')
            ->get();

        $assessments = $learner
            ? Assessment::query()
                ->with(['user', 'quiz', 'aiFeedback.recommendedCourse'])
                ->where('user_id', $learner->id)
                ->latest('id')
                ->limit(5)
                ->get()
            : collect();

        return view('quizzes.index', [
            'learner' => $learner,
            'quizzes' => $quizzes,
            'assessments' => $assessments,
        ]);
    }

    public function submit(SubmitQuizRequest $request, Quiz $quiz): RedirectResponse
    {
        $learner = $this->learnerResolver->resolve();

        if (! $learner) {
            return back()->with('error', 'Learner aktif tidak ditemukan.');
        }

        $quiz->load('questions');
        $submittedAnswers = $request->validated('answers');

        $assessment = DB::transaction(function () use ($learner, $quiz, $submittedAnswers): Assessment {
            $maxScore = max(1, (int) $quiz->questions->sum('points'));
            $earnedScore = 0;
            $hasEssay = false;

            $assessment = Assessment::create([
                'user_id' => $learner->id,
                'quiz_id' => $quiz->id,
                'score' => 0,
                'max_score' => 100,
                'status' => AssessmentStatus::Pending,
                'started_at' => now()->subMinutes(5),
                'completed_at' => now(),
            ]);

            foreach ($quiz->questions as $question) {
                $rawAnswer = trim((string) ($submittedAnswers[$question->id] ?? ''));
                $isCorrect = false;
                $questionScore = 0;

                if ($question->question_type === QuestionType::Essay) {
                    $hasEssay = true;
                    $keywords = ['prepared', 'parameter', 'binding', 'sanitize', 'validasi', 'sql', 'escape'];
                    $matched = collect($keywords)->filter(fn (string $word) => Str::contains(Str::lower($rawAnswer), $word))->count();
                    $ratio = min(1, max(0.4, $matched / 3));
                    $questionScore = (int) round($question->points * $ratio);
                    $isCorrect = $ratio >= 0.7;
                } else {
                    $isCorrect = strcasecmp($rawAnswer, trim($question->correct_answer)) === 0;
                    $questionScore = $isCorrect ? $question->points : 0;
                }

                $earnedScore += $questionScore;

                Answer::create([
                    'assessment_id' => $assessment->id,
                    'question_id' => $question->id,
                    'user_id' => $learner->id,
                    'answer_text' => $rawAnswer !== '' ? $rawAnswer : '-',
                    'is_correct' => $isCorrect,
                    'score_awarded' => $questionScore,
                ]);
            }

            $normalizedScore = (int) round(($earnedScore / $maxScore) * 100);
            $status = $hasEssay && $normalizedScore < 85
                ? AssessmentStatus::NeedsReview
                : AssessmentStatus::Evaluated;

            $assessment->update([
                'score' => $normalizedScore,
                'max_score' => 100,
                'status' => $status,
                'ai_evaluated_at' => now(),
            ]);

            $summary = $normalizedScore >= $quiz->passing_score
                ? "Evaluasi otomatis mencatat skor {$normalizedScore}/100. Pemahaman konsep utama sudah kuat."
                : "Skor sementara {$normalizedScore}/100. Beberapa konsep kunci masih perlu diperdalam sebelum penilaian final.";

            AiFeedback::create([
                'assessment_id' => $assessment->id,
                'recommended_course_id' => $quiz->course_id,
                'summary' => $summary,
                'strengths' => 'Penyelesaian tepat waktu dengan struktur jawaban terarah.',
                'improvements' => $normalizedScore >= 85
                    ? 'Lanjutkan ke tantangan Cyber Lab untuk praktik langsung.'
                    : 'Tinjau kembali rubrik mitigasi kerentanan dan parameter binding.',
                'recommended_topic' => $quiz->title,
                'confidence_score' => $hasEssay ? 0.78 : 0.98,
                'model_name' => 'velinux-ai-evaluator-v1',
            ]);

            return $assessment;
        });

        if ($assessment->score >= $quiz->passing_score) {
            $this->learnerResolver->awardXp(
                user: $learner,
                sourceType: PointSourceType::Quiz,
                points: $quiz->xp_reward,
                description: 'Complete Quiz: '.$quiz->title,
                sourceId: $assessment->id,
                action: 'assessment.completed',
                subjectType: Assessment::class,
                ipAddress: $request->ip(),
            );
        }

        return redirect()
            ->route('quizzes.index')
            ->with('status', "Quiz selesai dievaluasi AI dengan skor {$assessment->score}/100 ({$assessment->status->label()}).");
    }
}
