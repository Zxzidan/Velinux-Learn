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
            self::Pending => 'text-slate-600 dark:text-[#a3a9b8] bg-slate-500/8 border border-slate-500/15 dark:border-white/10',
            self::Evaluated => 'text-emerald-700 dark:text-[#4cc38a] bg-emerald-500/8 border border-emerald-600/15 dark:border-[#4cc38a]/20',
            self::NeedsReview => 'text-amber-700 dark:text-amber-300 bg-amber-500/8 border border-amber-500/20',
            self::Approved => 'text-emerald-800 dark:text-[#4cc38a] bg-emerald-500/10 border border-emerald-600/20 dark:border-[#4cc38a]/25',
        };
    }
}
