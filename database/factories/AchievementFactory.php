<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Achievement>
 */
class AchievementFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('###'),
            'name' => Str::title($name),
            'description' => fake()->sentence(),
            'icon' => 'shield',
            'badge_color' => 'emerald',
            'xp_bonus' => 50,
            'criteria_type' => 'labs_completed',
            'criteria_value' => 1,
        ];
    }
}
