<?php

namespace App\Enums;

enum QuizType: string
{
    case CyberQuiz = 'cyber_quiz';
    case DailyChallenge = 'daily_challenge';
    case QuizBattle = 'quiz_battle';
    case ModuleAssessment = 'module_assessment';

    public function label(): string
    {
        return match ($this) {
            self::CyberQuiz => 'Cyber Quiz',
            self::DailyChallenge => 'Daily Challenge',
            self::QuizBattle => 'Quiz Battle',
            self::ModuleAssessment => 'Module Assessment',
        };
    }
}
