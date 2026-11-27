<?php

namespace Database\Factories;

use App\Enums\PointSourceType;
use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserPoint>
 */
class UserPointFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'source_type' => PointSourceType::Quiz,
            'points' => 50,
            'description' => 'Complete Cyber Quiz',
            'awarded_at' => now(),
        ];
    }
}
