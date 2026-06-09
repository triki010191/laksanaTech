<?php

namespace App\Livewire\Admin;

use App\Enums\EmploymentType;
use App\Enums\StaffSpecialization;
use App\Services\Staff\EmployeeProfileService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Profil Saya')]
class StaffProfile extends Component
{
    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $password = '';

    public string $job_title = '';

    public string $bio = '';

    public string $skillsText = '';

    public function mount(): void
    {
        $user = auth()->user()->load('employeeProfile');
        $profile = $user->employeeProfile;

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->job_title = $profile?->job_title ?? '';
        $this->bio = $profile?->bio ?? '';
        $this->skillsText = implode(', ', $profile?->skills ?? []);
    }

    public function save(EmployeeProfileService $service): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
            'job_title' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
        ]);

        $user = auth()->user();
        $profile = $user->employeeProfile;

        $userData = [
            'name' => $this->name,
            'email' => $user->email,
            'phone' => $this->phone,
            'password' => $this->password,
            'role' => $user->roles->first()?->name ?? 'sales',
            'status' => $user->status->value ?? 'active',
        ];

        $profileData = [
            'employee_code' => $profile?->employee_code,
            'employment_type' => $profile?->employment_type->value ?? EmploymentType::Employee->value,
            'specialization' => $profile?->specialization->value ?? StaffSpecialization::General->value,
            'job_title' => $this->job_title ?: null,
            'bio' => $this->bio ?: null,
            'skills' => $service->parseSkills($this->skillsText),
            'joined_at' => $profile?->joined_at?->format('Y-m-d'),
            'is_active' => $profile?->is_active ?? true,
            'internal_notes' => $profile?->internal_notes,
        ];

        $service->update($user, $userData, $profileData);
        $this->password = '';

        session()->flash('status', 'Profil berhasil diperbarui.');
    }

    public function render()
    {
        $user = auth()->user()->load(['employeeProfile', 'roles']);

        return view('livewire.admin.staff-profile', [
            'user' => $user,
            'profile' => $user->employeeProfile,
        ]);
    }
}
