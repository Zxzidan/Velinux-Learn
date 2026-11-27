<?php

namespace App\Http\Controllers;

use App\Enums\PointSourceType;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Video;
use App\Support\ActiveLearnerResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(private readonly ActiveLearnerResolver $learnerResolver) {}

    public function index(Request $request): View
    {
        $learner = $this->learnerResolver->resolve();
        $search = $request->string('q')->trim()->toString();
        $difficulty = $request->string('difficulty')->trim()->toString();

        $courses = Course::query()
            ->published()
            ->withCount('modules')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nested) use ($search): void {
                    $nested->where('title', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($difficulty !== '', function ($query) use ($difficulty): void {
                $query->where('difficulty', $difficulty);
            })
            ->orderBy('id')
            ->get();

        $enrollmentsByCourse = $learner
            ? Enrollment::query()->where('user_id', $learner->id)->get()->keyBy('course_id')
            : collect();

        $videos = Video::query()
            ->with('module.course:id,title,slug')
            ->orderBy('id')
            ->limit(6)
            ->get();

        return view('courses.index', [
            'learner' => $learner,
            'courses' => $courses,
            'enrollmentsByCourse' => $enrollmentsByCourse,
            'videos' => $videos,
            'search' => $search,
            'selectedDifficulty' => $difficulty,
        ]);
    }

    public function show(Request $request, Course $course): View
    {
        abort_unless($course->is_published, 404);

        $learner = $this->learnerResolver->resolve();

        $course->load([
            'modules.lessons',
            'modules.videos',
            'quizzes' => fn ($q) => $q->published()->withCount('questions'),
        ]);

        $allLessons = $course->modules->flatMap->lessons->values();
        $requestedLessonSlug = $request->string('lesson')->toString();
        $currentLesson = $allLessons->firstWhere('slug', $requestedLessonSlug) ?? $allLessons->first();

        $currentIndex = $currentLesson ? $allLessons->search(fn (Lesson $item) => $item->id === $currentLesson->id) : false;
        $previousLesson = $currentIndex !== false && $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex !== false && $currentIndex < ($allLessons->count() - 1) ? $allLessons[$currentIndex + 1] : null;

        $completedLessonIds = $learner
            ? $learner->completedLessons()->pluck('lessons.id')->all()
            : [];

        $enrollment = $learner
            ? Enrollment::query()->where('user_id', $learner->id)->where('course_id', $course->id)->first()
            : null;

        return view('courses.show', [
            'learner' => $learner,
            'course' => $course,
            'currentLesson' => $currentLesson,
            'previousLesson' => $previousLesson,
            'nextLesson' => $nextLesson,
            'completedLessonIds' => $completedLessonIds,
            'enrollment' => $enrollment,
            'totalLessonsCount' => $allLessons->count(),
        ]);
    }

    public function completeLesson(Course $course, Lesson $lesson): RedirectResponse
    {
        $learner = $this->learnerResolver->resolve();

        if (! $learner) {
            return back()->with('error', 'Learner aktif tidak ditemukan.');
        }

        $course->load('modules.lessons');
        $courseLessonIds = $course->modules->flatMap->lessons->pluck('id');

        abort_unless($courseLessonIds->contains($lesson->id), 404);

        $alreadyCompleted = $learner->completedLessons()->where('lessons.id', $lesson->id)->exists();

        if (! $alreadyCompleted) {
            $learner->completedLessons()->attach($lesson->id, ['completed_at' => now()]);
            $this->learnerResolver->awardXp(
                user: $learner,
                sourceType: PointSourceType::Lesson,
                points: $lesson->xp_reward,
                description: 'Completed Lesson: '.$lesson->title,
                sourceId: $lesson->id,
                action: 'lesson.completed',
                subjectType: Lesson::class,
                ipAddress: request()->ip(),
            );
        }

        $completedInCourseCount = $learner->completedLessons()
            ->whereIn('lessons.id', $courseLessonIds)
            ->count();

        $progress = $courseLessonIds->isNotEmpty()
            ? (int) round(($completedInCourseCount / $courseLessonIds->count()) * 100)
            : 100;

        Enrollment::updateOrCreate(
            ['user_id' => $learner->id, 'course_id' => $course->id],
            [
                'progress_percentage' => $progress,
                'status' => $progress >= 100 ? 'completed' : 'active',
                'enrolled_at' => now(),
                'completed_at' => $progress >= 100 ? now() : null,
            ]
        );

        return redirect()
            ->route('courses.show', ['course' => $course, 'lesson' => $lesson->slug])
            ->with('status', $alreadyCompleted
                ? 'Materi ini sudah Anda selesaikan sebelumnya.'
                : "Materi selesai. Anda memperoleh +{$lesson->xp_reward} XP.");
    }
}
