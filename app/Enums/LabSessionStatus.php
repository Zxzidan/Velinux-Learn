<?php

namespace App\Enums;

enum LabSessionStatus: string
{
    case Running = 'running';
    case Completed = 'completed';
    case Expired = 'expired';
    case Stopped = 'stopped';

    public function label(): string
    {
        return match ($this) {
            self::Running => 'Running',
            self::Completed => 'Completed',
            self::Expired => 'Expired',
            self::Stopped => 'Stopped',
        };
    }
}
