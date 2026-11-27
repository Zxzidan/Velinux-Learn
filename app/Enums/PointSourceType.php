<?php

namespace App\Enums;

enum PointSourceType: string
{
    case Quiz = 'quiz';
    case Challenge = 'challenge';
    case Streak = 'streak';
    case Lab = 'lab';
    case Lesson = 'lesson';
    case Achievement = 'achievement';
}
