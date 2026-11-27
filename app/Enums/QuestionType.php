<?php

namespace App\Enums;

enum QuestionType: string
{
    case MultipleChoice = 'multiple_choice';
    case Essay = 'essay';
    case CodeVulnerability = 'code_vulnerability';
    case NetworkScenario = 'network_scenario';

    public function label(): string
    {
        return match ($this) {
            self::MultipleChoice => 'Multiple Choice',
            self::Essay => 'Technical Essay',
            self::CodeVulnerability => 'Find the Vulnerability',
            self::NetworkScenario => 'Network Detective',
        };
    }
}
