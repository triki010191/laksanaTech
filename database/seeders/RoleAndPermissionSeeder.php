<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (RoleName::cases() as $role) {
            Role::findOrCreate($role->value);
        }

        $superAdmin = User::query()->firstOrCreate(
            ['email' => 'admin@laksanatech.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'status' => UserStatus::Active,
            ],
        );

        $superAdmin->assignRole(RoleName::SuperAdmin->value);

        \App\Models\EmployeeProfile::query()->firstOrCreate(
            ['user_id' => $superAdmin->id],
            [
                'employee_code' => 'EMP-'.now()->format('Y').'-ADMIN',
                'employment_type' => \App\Enums\EmploymentType::Employee->value,
                'specialization' => \App\Enums\StaffSpecialization::General->value,
                'job_title' => 'Super Administrator',
                'joined_at' => now()->toDateString(),
                'is_active' => true,
            ],
        );
    }
}
