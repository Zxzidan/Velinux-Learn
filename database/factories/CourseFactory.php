<?php

namespace Database\Factories;

use App\Enums\DifficultyLevel;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title' => Str::title($title),
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('###'),
            'category' => fake()->randomElement([
                'Cybersecurity Fundamentals',
                'Linux Administration',
                'Web Application Security',
                'Networking Fundamentals',
                'Digital Forensics',
                'Cryptography',
            ]),
            'difficulty' => fake()->randomElement(DifficultyLevel::cases()),
            'description' => fake()->paragraph(),
            'estimated_hours' => fake()->numberBetween(3, 12),
            'xp_reward' => fake()->numberBetween(150, 500),
            'is_published' => true,
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn () => ['is_published' => false]);
    }
}
