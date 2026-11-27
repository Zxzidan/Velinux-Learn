<?php

namespace Database\Factories;

use App\Models\Module;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Video>
 */
class VideoFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'module_id' => Module::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->sentence(),
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'duration_seconds' => fake()->numberBetween(240, 1200),
            'sort_order' => 1,
        ];
    }
}
