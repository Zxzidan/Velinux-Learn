<?php

namespace App\Enums;

enum DifficultyLevel: string
{
    case Beginner = 'beginner';
    case Intermediate = 'intermediate';
    case Advanced = 'advanced';
    case Expert = 'expert';

    public function label(): string
    {
        return match ($this) {
            self::Beginner => 'Beginner',
            self::Intermediate => 'Intermediate',
            self::Advanced => 'Advanced',
            self::Expert => 'Expert',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Beginner => 'text-emerald-700 dark:text-[#4cc38a] bg-emerald-500/8 border border-emerald-600/15 dark:border-[#4cc38a]/20',
            self::Intermediate => 'text-slate-700 dark:text-[#a3a9b8] bg-slate-500/8 border border-slate-500/15 dark:border-white/10',
            self::Advanced => 'text-amber-700 dark:text-amber-300 bg-amber-500/8 border border-amber-500/20',
            self::Expert => 'text-rose-700 dark:text-rose-300 bg-rose-500/8 border border-rose-500/20',
        };
    }
}
