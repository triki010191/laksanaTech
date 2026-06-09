<?php

namespace App\Enums;

enum StaffSpecialization: string
{
    case Marketing = 'marketing';
    case CustomerService = 'customer_service';
    case Sales = 'sales';
    case Design = 'design';
    case Development = 'development';
    case Transaction = 'transaction';
    case ProjectManager = 'project_manager';
    case General = 'general';

    public function label(): string
    {
        return match ($this) {
            self::Marketing => 'Marketing',
            self::CustomerService => 'Customer Service (CS)',
            self::Sales => 'Sales',
            self::Design => 'Desain',
            self::Development => 'Development / Koding',
            self::Transaction => 'Admin Transaksi',
            self::ProjectManager => 'Project Manager',
            self::General => 'Umum',
        };
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
