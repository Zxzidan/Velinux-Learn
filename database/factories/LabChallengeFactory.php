<?php

namespace Database\Factories;

use App\Enums\DifficultyLevel;
use App\Models\LabChallenge;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LabChallenge>
 */
class LabChallengeFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true).' Lab';

        return [
            'title' => Str::title($title),
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('###'),
            'category' => fake()->randomElement([
                'Linux Command Line Lab',
                'Network Scanning Lab',
                'Web Security Lab',
                'Cryptography Lab',
                'Digital Forensics Lab',
            ]),
            'difficulty' => DifficultyLevel::Beginner,
            'scenario' => fake()->paragraph(),
            'instructions' => 'Hubungkan ke target container terisolasi, lakukan enumerasi, lalu temukan flag.',
            'target_host' => '10.10.24.'.fake()->numberBetween(10, 200),
            'terminal_prompt' => "velinux@cyberlab:~$ nmap -sV 10.10.24.15\n",
            'flag' => 'VELINUX{'.fake()->bothify('cyb3r_####_flag').'}',
            'xp_reward' => 100,
            'time_limit_minutes' => 45,
            'is_active' => true,
        ];
    }
}
