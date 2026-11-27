<?php

namespace Database\Factories;

use App\Models\AiFeedback;
use App\Models\Assessment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AiFeedback>
 */
class AiFeedbackFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assessment_id' => Assessment::factory(),
            'summary' => 'Jawaban Anda sudah menjelaskan konsep SQL Injection dengan cukup baik. Namun, penjelasan mengenai prepared statements dan parameterized queries masih perlu ditingkatkan.',
            'strengths' => 'Identifikasi celah concatenation query sudah tepat.',
            'improvements' => 'Tambahkan contoh binding parameter pada PDO atau Eloquent.',
            'recommended_topic' => 'SQL Injection Prevention — Web Application Security',
            'confidence_score' => 0.91,
            'model_name' => 'velinux-eval-v1',
        ];
    }
}
