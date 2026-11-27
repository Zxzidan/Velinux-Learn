<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Module>
 */
class ModuleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'course_id' => Course::factory(),
            'title' => Str::title($title),
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('###'),
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(1, 10),
            'estimated_minutes' => fake()->numberBetween(20, 60),
        ];
    }
}
