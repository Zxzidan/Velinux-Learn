<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(4, true);

        return [
            'module_id' => Module::factory(),
            'title' => Str::title($title),
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('###'),
            'content_type' => 'article',
            'content' => fake()->paragraphs(3, true),
            'code_snippet' => "nmap -sV -Pn 10.10.14.5\n",
            'code_language' => 'bash',
            'sort_order' => fake()->numberBetween(1, 10),
            'xp_reward' => 25,
        ];
    }
}
