<?php

namespace App\Livewire\Admin\Employees;

use App\Enums\EmploymentType;
use App\Enums\RoleName;
use App\Enums\StaffSpecialization;
use App\Enums\UserStatus;
use App\Models\EmployeeProfile;
use App\Models\User;
use App\Services\Auth\RoleRegistryService;
use App\Services\Staff\EmployeeProfileService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Kelola Karyawan')]
class ManageEmployees extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingUserId = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $password = '';

    public string $role = RoleName::Sales->value;

    public string $status = UserStatus::Active->value;

    public string $employment_type = EmploymentType::Employee->value;

    public string $specialization = StaffSpecialization::General->value;

    public string $job_title = '';

    public string $bio = '';

    public string $skillsText = '';

    public string $joined_at = '';

    public bool $profile_active = true;

    public string $internal_notes = '';

    public string $employee_code = '';

    public string $filterType = '';

    public string $filterSpec = '';

    public function mount(RoleRegistryService $roles): void
    {
        $roles->sync();
        $this->joined_at = now()->format('Y-m-d');
    }

    public function updatedSpecialization(EmployeeProfileService $service): void
    {
        $this->role = $service->suggestedRoleFor(
            EmploymentType::from($this->employment_type),
            StaffSpecialization::from($this->specialization),
        );
    }

    public function updatedEmploymentType(EmployeeProfileService $service): void
    {
        $this->updatedSpecialization($service);
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $userId): void
    {
        $user = User::query()->with(['employeeProfile', 'roles'])->findOrFail($userId);
        $profile = $user->employeeProfile;

        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->role = $user->roles->first()?->name ?? RoleName::Sales->value;
        $this->status = $user->status->value ?? UserStatus::Active->value;
        $this->employment_type = $profile?->employment_type->value ?? EmploymentType::Employee->value;
        $this->specialization = $profile?->specialization->value ?? StaffSpecialization::General->value;
        $this->job_title = $profile?->job_title ?? '';
        $this->bio = $profile?->bio ?? '';
        $this->skillsText = implode(', ', $profile?->skills ?? []);
        $this->joined_at = $profile?->joined_at?->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->profile_active = $profile?->is_active ?? true;
        $this->internal_notes = $profile?->internal_notes ?? '';
        $this->employee_code = $profile?->employee_code ?? '';
        $this->password = '';
        $this->showForm = true;
    }

    public function save(EmployeeProfileService $service, RoleRegistryService $roles): void
    {
        $assignable = array_keys($roles->assignableRoleLabels());

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingUserId)],
            'phone' => 'nullable|string|max:20',
            'role' => ['required', Rule::in($assignable)],
            'status' => ['required', Rule::in(array_column(UserStatus::cases(), 'value'))],
            'employment_type' => ['required', Rule::in(array_column(EmploymentType::cases(), 'value'))],
            'specialization' => ['required', Rule::in(array_column(StaffSpecialization::cases(), 'value'))],
            'job_title' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:2000',
            'joined_at' => 'nullable|date',
            'employee_code' => ['nullable', 'string', 'max:30', Rule::unique('employee_profiles', 'employee_code')->ignore(
                EmployeeProfile::query()->where('user_id', $this->editingUserId)->value('id')
            )],
        ];

        $rules['password'] = $this->editingUserId ? 'nullable|string|min:8' : 'required|string|min:8';

        $this->validate($rules);

        $userData = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => $this->password,
            'role' => $this->role,
            'status' => $this->status,
        ];

        $profileData = [
            'employee_code' => $this->employee_code ?: null,
            'employment_type' => $this->employment_type,
            'specialization' => $this->specialization,
            'job_title' => $this->job_title ?: null,
            'bio' => $this->bio ?: null,
            'skills' => $service->parseSkills($this->skillsText),
            'joined_at' => $this->joined_at ?: null,
            'is_active' => $this->profile_active,
            'internal_notes' => $this->internal_notes ?: null,
        ];

        if ($this->editingUserId) {
            $user = User::query()->findOrFail($this->editingUserId);
            $service->update($user, $userData, $profileData);
            session()->flash('status', 'Profil karyawan diperbarui.');
        } else {
            $service->create($userData, $profileData);
            session()->flash('status', 'Karyawan baru ditambahkan & terhubung ke akun user.');
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function delete(int $userId): void
    {
        if ($userId === auth()->id()) {
            session()->flash('error', 'Tidak bisa menghapus akun sendiri.');

            return;
        }

        User::query()->findOrFail($userId)->delete();
        session()->flash('status', 'Karyawan dihapus.');
    }

    public function render(RoleRegistryService $roles)
    {
        $query = User::query()
            ->with(['employeeProfile', 'roles'])
            ->whereHas('roles', fn ($q) => $q->whereIn('name', $roles->staffRoleNames()))
            ->whereHas('employeeProfile');

        if ($this->filterType) {
            $query->whereHas('employeeProfile', fn ($q) => $q->where('employment_type', $this->filterType));
        }

        if ($this->filterSpec) {
            $query->whereHas('employeeProfile', fn ($q) => $q->where('specialization', $this->filterSpec));
        }

        return view('livewire.admin.employees.manage-employees', [
            'employees' => $query->latest()->paginate(12),
            'roleLabels' => $roles->staffRoleLabels(),
            'assignableRoles' => $roles->assignableRoleLabels(),
            'employmentTypes' => EmploymentType::cases(),
            'specializations' => StaffSpecialization::cases(),
            'statuses' => UserStatus::cases(),
        ]);
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingUserId', 'name', 'email', 'phone', 'password', 'job_title', 'bio',
            'skillsText', 'internal_notes', 'employee_code',
        ]);
        $this->role = RoleName::Sales->value;
        $this->status = UserStatus::Active->value;
        $this->employment_type = EmploymentType::Employee->value;
        $this->specialization = StaffSpecialization::General->value;
        $this->joined_at = now()->format('Y-m-d');
        $this->profile_active = true;
    }
}
