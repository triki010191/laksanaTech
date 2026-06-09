<?php

namespace App\Models;

use App\Enums\EmploymentType;
use App\Enums\StaffSpecialization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeProfile extends Model
{
    protected $fillable = [
        'user_id',
        'employee_code',
        'employment_type',
        'specialization',
        'job_title',
        'bio',
        'skills',
        'joined_at',
        'is_active',
        'internal_notes',
    ];

    protected function casts(): array
    {
        return [
            'employment_type' => EmploymentType::class,
            'specialization' => StaffSpecialization::class,
            'skills' => 'array',
            'joined_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employmentLabel(): string
    {
        return $this->employment_type?->label() ?? '-';
    }

    public function specializationLabel(): string
    {
        return $this->specialization?->label() ?? '-';
    }
}
