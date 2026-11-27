<?php

namespace Database\Factories;

use App\Enums\QuestionType;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'question_type' => QuestionType::MultipleChoice,
            'prompt' => fake()->sentence().'?',
            'options' => [
                'A' => 'Prepared statements / parameterized queries',
                'B' => 'String concatenation with user input',
                'C' => 'Disabling HTTPS enforcement',
                'D' => 'Exposing debug stack traces',
            ],
            'correct_answer' => 'A',
            'rubric' => 'Jelaskan mekanisme mitigasi sesuai OWASP Top 10.',
            'points' => 20,
            'sort_order' => 1,
        ];
    }
}
