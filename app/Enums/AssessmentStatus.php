<?php

namespace App\Enums;

enum AssessmentStatus: string
{
    case Pending = 'pending';
    case Evaluated = 'evaluated';
    case NeedsReview = 'needs_review';
    case Approved = 'approved';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Evaluated => 'AI Evaluated',
            self::NeedsReview => 'Needs Mentor Review',
            self::Approved => 'Mentor Approved',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Pending => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-500/30',
            self::Evaluated => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300 border border-emerald-500/30',
            self::NeedsReview => 'bg-amber-100 text-amber-800 dark:bg-amber-950/70 dark:text-amber-300 border border-amber-500/30',
            self::Approved => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-950/70 dark:text-cyan-300 border border-cyan-500/30',
        };
    }
}
