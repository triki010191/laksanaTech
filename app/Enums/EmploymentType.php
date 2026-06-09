<?php

namespace App\Enums;

enum EmploymentType: string
{
    case Employee = 'employee';
    case Freelancer = 'freelancer';

    public function label(): string
    {
        return match ($this) {
            self::Employee => 'Karyawan',
            self::Freelancer => 'Freelancer',
        };
    }
}
