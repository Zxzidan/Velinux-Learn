<?php

namespace Database\Factories;

use App\Enums\LabSessionStatus;
use App\Models\LabChallenge;
use App\Models\LabSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LabSession>
 */
class LabSessionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'lab_challenge_id' => LabChallenge::factory(),
            'container_id' => 'vlx-'.Str::lower(Str::random(10)),
            'status' => LabSessionStatus::Running,
            'started_at' => now()->subMinutes(10),
            'expires_at' => now()->addMinutes(35),
        ];
    }
}
