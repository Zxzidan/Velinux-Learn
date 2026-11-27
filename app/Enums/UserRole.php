<?php

namespace App\Enums;

enum UserRole: string
{
    case Student = 'student';
    case Mentor = 'mentor';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::Student => 'Mahasiswa',
            self::Mentor => 'Mentor',
            self::Admin => 'Admin',
        };
    }
}
