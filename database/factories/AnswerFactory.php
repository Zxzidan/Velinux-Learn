<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Assessment;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Answer>
 */
class AnswerFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assessment_id' => Assessment::factory(),
            'question_id' => Question::factory(),
            'user_id' => User::factory(),
            'answer_text' => 'Gunakan prepared statements untuk memisahkan query SQL dari input pengguna.',
            'is_correct' => true,
            'score_awarded' => 20,
        ];
    }
}
