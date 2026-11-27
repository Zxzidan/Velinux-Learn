<?php

use App\Enums\AssessmentStatus;
use App\Enums\LabSessionStatus;
use App\Enums\QuestionType;
use App\Enums\UserRole;
use App\Models\Achievement;
use App\Models\AiFeedback;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\LabChallenge;
use App\Models\LabSession;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Database\Seeders\VelinuxSeeder;

it('renders the velinux dashboard with seeded lms, ai assessment, cyber lab, and leaderboard data', function (): void {
    $this->seed(VelinuxSeeder::class);

    $response = $this->get(route('dashboard'));

    $response->assertOk()
        ->assertSee('VELINUX')
        ->assertSee('images/velinux-logo.png', false)
        ->assertSee('Learn. Practice.')
        ->assertSee('data-theme-toggle', false)
        ->assertSee('Web Application Security')
        ->assertSee('AI Assessment Result')
        ->assertSee('Cyber Leaderboard');

    $course = Course::query()->where('slug', 'web-application-security')->firstOrFail();

    $this->get(route('courses.show', $course))->assertOk()->assertSee($course->title);
    $this->get(route('quizzes.index'))->assertOk()->assertSee('Daily Challenge: Find the SQLi Vulnerability');
    $this->get(route('labs.index'))->assertOk()->assertSee('Cyber Lab & CTF Sandbox');
    $this->get(route('admin.index'))->assertOk()->assertSee('Review AI Assessments');
});

it('filters courses by difficulty and search query on the catalog page', function (): void {
    $matchingCourse = Course::factory()->create([
        'title' => 'Linux Kernel Hardening',
        'category' => 'Linux Administration',
        'difficulty' => 'beginner',
    ]);
    $otherCourse = Course::factory()->create([
        'title' => 'Malware Reverse Engineering',
        'category' => 'Malware Analysis',
        'difficulty' => 'advanced',
    ]);

    $response = $this->get(route('courses.index', [
        'difficulty' => 'beginner',
        'q' => 'Kernel',
    ]));

    $response->assertOk()
        ->assertSee($matchingCourse->title)
        ->assertDontSee($otherCourse->title);
});

it('marks a lesson complete, awards xp once, and updates course enrollment progress', function (): void {
    $user = User::factory()->withRole(UserRole::Student)->create(['xp_points' => 100]);
    $course = Course::factory()->create();
    $module = Module::factory()->for($course)->create();
    $firstLesson = Lesson::factory()->for($module)->create(['xp_reward' => 30, 'sort_order' => 1]);
    Lesson::factory()->for($module)->create(['xp_reward' => 30, 'sort_order' => 2]);

    $this->actingAs($user)
        ->post(route('courses.lessons.complete', [$course, $firstLesson]))
        ->assertRedirect(route('courses.show', ['course' => $course, 'lesson' => $firstLesson->slug]))
        ->assertSessionHas('status');

    expect($user->fresh()->xp_points)->toBe(130);
    $this->assertDatabaseHas('enrollments', [
        'user_id' => $user->id,
        'course_id' => $course->id,
        'progress_percentage' => 50,
    ]);

    $this->actingAs($user)
        ->post(route('courses.lessons.complete', [$course, $firstLesson]))
        ->assertRedirect();

    expect($user->fresh()->xp_points)->toBe(130);
});

it('evaluates submitted quiz answers, stores ai feedback, and awards xp when passing', function (): void {
    $user = User::factory()->withRole(UserRole::Student)->create(['xp_points' => 200]);
    $course = Course::factory()->create();
    $quiz = Quiz::factory()->for($course)->create([
        'passing_score' => 70,
        'xp_reward' => 50,
    ]);
    $mcq = Question::factory()->for($quiz)->create([
        'question_type' => QuestionType::MultipleChoice,
        'correct_answer' => 'A',
        'points' => 100,
    ]);

    $this->actingAs($user)
        ->post(route('quizzes.submit', $quiz), [
            'answers' => [
                $mcq->id => 'A',
            ],
        ])
        ->assertRedirect(route('quizzes.index'))
        ->assertSessionHas('status');

    expect($user->fresh()->xp_points)->toBe(250);
    $this->assertDatabaseHas('assessments', [
        'user_id' => $user->id,
        'quiz_id' => $quiz->id,
        'score' => 100,
        'status' => AssessmentStatus::Evaluated->value,
    ]);
    $this->assertDatabaseHas('ai_feedback', [
        'recommended_course_id' => $course->id,
    ]);
});

it('rejects empty quiz submissions with a validation error', function (): void {
    $user = User::factory()->withRole(UserRole::Student)->create();
    $quiz = Quiz::factory()->create();

    $this->actingAs($user)
        ->from(route('quizzes.index'))
        ->post(route('quizzes.submit', $quiz), [])
        ->assertRedirect(route('quizzes.index'))
        ->assertSessionHasErrors([
            'answers' => 'Jawab minimal satu pertanyaan sebelum mengirim quiz.',
        ]);
});

it('starts a cyber lab session, verifies valid flags once, and rejects invalid flags', function (): void {
    $user = User::factory()->withRole(UserRole::Student)->create(['xp_points' => 500]);
    $lab = LabChallenge::factory()->create([
        'flag' => 'VELINUX{t3st_fl4g_ok}',
        'xp_reward' => 150,
    ]);

    $this->actingAs($user)
        ->post(route('labs.start', $lab))
        ->assertRedirect(route('labs.index'));

    $session = LabSession::query()->where('user_id', $user->id)->where('lab_challenge_id', $lab->id)->first();
    expect($session)->not->toBeNull()
        ->and($session->status)->toBe(LabSessionStatus::Running);

    $this->actingAs($user)
        ->post(route('labs.submit', $lab), ['submitted_flag' => 'VELINUX{wrong}'])
        ->assertRedirect(route('labs.index'))
        ->assertSessionHas('error');

    expect($user->fresh()->xp_points)->toBe(500);

    $this->actingAs($user)
        ->post(route('labs.submit', $lab), ['submitted_flag' => 'VELINUX{t3st_fl4g_ok}'])
        ->assertRedirect(route('labs.index'))
        ->assertSessionHas('status');

    expect($user->fresh()->xp_points)->toBe(650)
        ->and($session->fresh()->status)->toBe(LabSessionStatus::Completed);

    $this->actingAs($user)
        ->post(route('labs.submit', $lab), ['submitted_flag' => 'VELINUX{t3st_fl4g_ok}'])
        ->assertRedirect(route('labs.index'));

    expect($user->fresh()->xp_points)->toBe(650);
});

it('renders leaderboard rankings and achievement status', function (): void {
    $user = User::factory()->withRole(UserRole::Student)->create(['name' => 'Top Cyber Learner', 'xp_points' => 4500]);
    $achievement = Achievement::factory()->create(['name' => 'SQLi Hunter']);
    $user->achievements()->attach($achievement->id, ['earned_at' => now()]);

    $this->actingAs($user)
        ->get(route('leaderboard.index'))
        ->assertOk()
        ->assertSee('Top Cyber Learner')
        ->assertSee('SQLi Hunter')
        ->assertSee('Unlocked');
});

it('allows mentors to review ai assessments and persists validation notes', function (): void {
    $mentor = User::factory()->withRole(UserRole::Mentor)->create();
    $assessment = Assessment::factory()->needsReview()->create();
    AiFeedback::factory()->for($assessment)->create();

    $this->actingAs($mentor)
        ->patch(route('admin.assessments.review', $assessment), [
            'score' => 92,
            'status' => AssessmentStatus::Approved->value,
            'mentor_notes' => 'Penjelasan prepared statements sudah akurat dan lengkap.',
        ])
        ->assertRedirect(route('admin.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseHas('assessments', [
        'id' => $assessment->id,
        'score' => 92,
        'status' => AssessmentStatus::Approved->value,
        'reviewed_by' => $mentor->id,
    ]);
    $this->assertDatabaseHas('ai_feedback', [
        'assessment_id' => $assessment->id,
        'mentor_notes' => 'Penjelasan prepared statements sudah akurat dan lengkap.',
    ]);
});
