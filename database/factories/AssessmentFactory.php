<?php

namespace Database\Factories;

use App\Enums\AssessmentStatus;
use App\Models\Assessment;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assessment>
 */
class AssessmentFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'quiz_id' => Quiz::factory(),
            'score' => 85,
            'max_score' => 100,
            'status' => AssessmentStatus::Evaluated,
            'ai_evaluated_at' => now()->subMinutes(15),
            'started_at' => now()->subMinutes(25),
            'completed_at' => now()->subMinutes(15),
        ];
    }

    public function needsReview(): static
    {
        return $this->state(fn () => [
            'status' => AssessmentStatus::NeedsReview,
            'score' => 72,
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'status' => AssessmentStatus::Approved,
            'reviewed_at' => now(),
        ]);
    }
}
