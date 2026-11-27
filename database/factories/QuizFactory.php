<?php

namespace Database\Factories;

use App\Enums\QuizType;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true).' Quiz';

        return [
            'title' => Str::title($title),
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('###'),
            'description' => fake()->sentence(),
            'quiz_type' => QuizType::CyberQuiz,
            'time_limit_seconds' => 600,
            'passing_score' => 70,
            'xp_reward' => 50,
            'is_daily' => false,
            'is_published' => true,
        ];
    }

    public function daily(): static
    {
        return $this->state(fn () => [
            'quiz_type' => QuizType::DailyChallenge,
            'is_daily' => true,
            'xp_reward' => 100,
        ]);
    }
}
