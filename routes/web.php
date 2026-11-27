<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LabChallengeController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::prefix('courses')->name('courses.')->group(function (): void {
    Route::get('/', [CourseController::class, 'index'])->name('index');
    Route::get('/{course:slug}', [CourseController::class, 'show'])->name('show');
    Route::post('/{course:slug}/lessons/{lesson:slug}/complete', [CourseController::class, 'completeLesson'])
        ->name('lessons.complete');
});

Route::prefix('quizzes')->name('quizzes.')->group(function (): void {
    Route::get('/', [QuizController::class, 'index'])->name('index');
    Route::post('/{quiz:slug}/submit', [QuizController::class, 'submit'])->name('submit');
});

Route::prefix('labs')->name('labs.')->group(function (): void {
    Route::get('/', [LabChallengeController::class, 'index'])->name('index');
    Route::post('/{labChallenge:slug}/start', [LabChallengeController::class, 'startSession'])->name('start');
    Route::post('/{labChallenge:slug}/submit', [LabChallengeController::class, 'submitFlag'])->name('submit');
});

Route::get('/leaderboard', LeaderboardController::class)->name('leaderboard.index');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::patch('/assessments/{assessment}/review', [AdminController::class, 'reviewAssessment'])
        ->name('assessments.review');
});
