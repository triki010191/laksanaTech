<?php

namespace App\Services\Staff;

use App\Enums\EmploymentType;
use App\Enums\RoleName;
use App\Enums\StaffSpecialization;
use App\Enums\UserStatus;
use App\Models\EmployeeProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeProfileService
{
    /** @param array<string, mixed> $userData @param array<string, mixed> $profileData */
    public function create(array $userData, array $profileData): User
    {
        return DB::transaction(function () use ($userData, $profileData) {
            $user = User::query()->create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'] ?? null,
                'password' => Hash::make($userData['password']),
                'status' => $userData['status'] ?? UserStatus::Active->value,
            ]);

            $user->assignRole($userData['role']);

            EmployeeProfile::query()->create([
                'user_id' => $user->id,
                'employee_code' => $profileData['employee_code'] ?? $this->generateEmployeeCode(),
                'employment_type' => $profileData['employment_type'],
                'specialization' => $profileData['specialization'],
                'job_title' => $profileData['job_title'] ?? null,
                'bio' => $profileData['bio'] ?? null,
                'skills' => $profileData['skills'] ?? [],
                'joined_at' => $profileData['joined_at'] ?? now()->toDateString(),
                'is_active' => $profileData['is_active'] ?? true,
                'internal_notes' => $profileData['internal_notes'] ?? null,
            ]);

            return $user->load(['employeeProfile', 'roles']);
        });
    }

    /** @param array<string, mixed> $userData @param array<string, mixed> $profileData */
    public function update(User $user, array $userData, array $profileData): User
    {
        return DB::transaction(function () use ($user, $userData, $profileData) {
            $updateUser = [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'] ?? null,
                'status' => $userData['status'],
            ];

            if (! empty($userData['password'])) {
                $updateUser['password'] = Hash::make($userData['password']);
            }

            $user->update($updateUser);
            $user->syncRoles([$userData['role']]);

            $user->employeeProfile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_code' => $profileData['employee_code'] ?? $this->generateEmployeeCode(),
                    'employment_type' => $profileData['employment_type'],
                    'specialization' => $profileData['specialization'],
                    'job_title' => $profileData['job_title'] ?? null,
                    'bio' => $profileData['bio'] ?? null,
                    'skills' => $profileData['skills'] ?? [],
                    'joined_at' => $profileData['joined_at'] ?? null,
                    'is_active' => $profileData['is_active'] ?? true,
                    'internal_notes' => $profileData['internal_notes'] ?? null,
                ],
            );

            return $user->fresh(['employeeProfile', 'roles']);
        });
    }

    public function suggestedRoleFor(EmploymentType $type, StaffSpecialization $specialization): string
    {
        return match ($specialization) {
            StaffSpecialization::Transaction => RoleName::TransactionAdmin->value,
            StaffSpecialization::Design, StaffSpecialization::Development => RoleName::Freelancer->value,
            StaffSpecialization::Marketing, StaffSpecialization::Sales, StaffSpecialization::CustomerService => RoleName::Sales->value,
            default => $type === EmploymentType::Freelancer
                ? RoleName::Freelancer->value
                : RoleName::Sales->value,
        };
    }

    /** @param list<string>|string $skills */
    public function parseSkills(array|string $skills): array
    {
        if (is_array($skills)) {
            return array_values(array_filter(array_map('trim', $skills)));
        }

        return array_values(array_filter(array_map('trim', preg_split('/[,\\n]+/', $skills) ?: [])));
    }

    public function generateEmployeeCode(): string
    {
        do {
            $code = 'EMP-'.now()->format('Y').'-'.strtoupper(Str::random(4));
        } while (EmployeeProfile::query()->where('employee_code', $code)->exists());

        return $code;
    }
}
