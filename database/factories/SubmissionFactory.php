<?php

namespace Database\Factories;

use App\Models\LabChallenge;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'lab_challenge_id' => LabChallenge::factory(),
            'submitted_flag' => 'VELINUX{sample_flag}',
            'is_correct' => true,
            'score' => 100,
            'status' => 'verified',
            'evaluated_at' => now(),
        ];
    }
}
