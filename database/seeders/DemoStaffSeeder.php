<?php

namespace Database\Seeders;

use App\Enums\EmploymentType;
use App\Enums\RoleName;
use App\Enums\StaffSpecialization;
use App\Enums\UserStatus;
use App\Models\EmployeeProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoStaffSeeder extends Seeder
{
    /** @return list<array<string, mixed>> */
    private function accounts(): array
    {
        return [
            [
                'email' => 'admin@laksanatech.id',
                'name' => 'Super Admin',
                'role' => RoleName::SuperAdmin->value,
                'employment_type' => EmploymentType::Employee->value,
                'specialization' => StaffSpecialization::General->value,
                'job_title' => 'Super Administrator',
                'code' => 'EMP-DEMO-SUPER',
            ],
            [
                'email' => 'admin.staff@laksanatech.id',
                'name' => 'Admin Staff',
                'role' => RoleName::Admin->value,
                'employment_type' => EmploymentType::Employee->value,
                'specialization' => StaffSpecialization::General->value,
                'job_title' => 'Administrator',
                'code' => 'EMP-DEMO-ADMIN',
            ],
            [
                'email' => 'transaksi@laksanatech.id',
                'name' => 'Admin Transaksi',
                'role' => RoleName::TransactionAdmin->value,
                'employment_type' => EmploymentType::Employee->value,
                'specialization' => StaffSpecialization::Transaction->value,
                'job_title' => 'Finance & Payment',
                'code' => 'EMP-DEMO-TRX',
            ],
            [
                'email' => 'marketing@laksanatech.id',
                'name' => 'Staff Marketing',
                'role' => RoleName::Sales->value,
                'employment_type' => EmploymentType::Employee->value,
                'specialization' => StaffSpecialization::Marketing->value,
                'job_title' => 'Marketing Executive',
                'code' => 'EMP-DEMO-MKT',
            ],
            [
                'email' => 'cs@laksanatech.id',
                'name' => 'Staff Customer Service',
                'role' => RoleName::Sales->value,
                'employment_type' => EmploymentType::Employee->value,
                'specialization' => StaffSpecialization::CustomerService->value,
                'job_title' => 'Customer Service',
                'code' => 'EMP-DEMO-CS',
            ],
            [
                'email' => 'sales@laksanatech.id',
                'name' => 'Staff Sales',
                'role' => RoleName::Sales->value,
                'employment_type' => EmploymentType::Employee->value,
                'specialization' => StaffSpecialization::Sales->value,
                'job_title' => 'Sales Representative',
                'code' => 'EMP-DEMO-SLS',
            ],
            [
                'email' => 'desain@laksanatech.id',
                'name' => 'Freelancer Desain',
                'role' => RoleName::Freelancer->value,
                'employment_type' => EmploymentType::Freelancer->value,
                'specialization' => StaffSpecialization::Design->value,
                'job_title' => 'UI/UX Designer',
                'code' => 'EMP-DEMO-DES',
                'skills' => ['Figma', 'Adobe XD', 'Tailwind CSS'],
            ],
            [
                'email' => 'dev@laksanatech.id',
                'name' => 'Freelancer Developer',
                'role' => RoleName::Freelancer->value,
                'employment_type' => EmploymentType::Freelancer->value,
                'specialization' => StaffSpecialization::Development->value,
                'job_title' => 'Full-stack Developer',
                'code' => 'EMP-DEMO-DEV',
                'skills' => ['PHP', 'Laravel', 'MySQL'],
            ],
            [
                'email' => 'customer@laksanatech.id',
                'name' => 'Demo Customer',
                'role' => RoleName::Customer->value,
                'profile' => false,
            ],
        ];
    }

    public function run(): void
    {
        $password = Hash::make('password');

        foreach ($this->accounts() as $account) {
            $user = User::query()->updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => $password,
                    'phone' => '085212455505',
                    'status' => UserStatus::Active,
                ],
            );

            $user->syncRoles([$account['role']]);

            if (($account['profile'] ?? true) === false) {
                continue;
            }

            EmployeeProfile::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'employee_code' => $account['code'],
                    'employment_type' => $account['employment_type'],
                    'specialization' => $account['specialization'],
                    'job_title' => $account['job_title'] ?? null,
                    'bio' => 'Akun demo untuk testing role '.$account['role'].'.',
                    'skills' => $account['skills'] ?? [],
                    'joined_at' => now()->toDateString(),
                    'is_active' => true,
                ],
            );
        }
    }
}
