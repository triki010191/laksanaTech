<?php

namespace App\Livewire\Admin\Users;

use App\Enums\RoleName;
use App\Enums\UserStatus;
use App\Models\User;
use App\Services\Auth\RoleRegistryService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Kelola Pengguna')]
class ManageUsers extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $password = '';

    public string $role = RoleName::Sales->value;

    public string $status = UserStatus::Active->value;

    public string $roleFilter = 'staff';

    public function mount(RoleRegistryService $roles): void
    {
        $roles->sync();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('scroll-to-form');
    }

    public function edit(int $id): void
    {
        $user = User::query()->with('roles')->findOrFail($id);
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->role = $user->roles->first()?->name ?? RoleName::Sales->value;
        $this->status = $user->status?->value ?? UserStatus::Active->value;
        $this->password = '';
        $this->showForm = true;
        $this->dispatch('scroll-to-form');
    }

    public function save(RoleRegistryService $roles): void
    {
        $assignable = array_keys($roles->assignableRoleLabels());

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'phone' => 'nullable|string|max:20',
            'role' => ['required', Rule::in($assignable)],
            'status' => ['required', Rule::in(array_column(UserStatus::cases(), 'value'))],
        ];

        if ($this->editingId) {
            $rules['password'] = 'nullable|string|min:8';
        } else {
            $rules['password'] = 'required|string|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'status' => $this->status,
        ];

        if ($this->password !== '') {
            $data['password'] = $this->password;
        }

        if ($this->editingId) {
            $user = User::query()->findOrFail($this->editingId);
            $user->update($data);
            $user->syncRoles([$this->role]);
            session()->flash('status', 'Pengguna diperbarui.');
        } else {
            $user = User::query()->create($data);
            $user->assignRole($this->role);
            session()->flash('status', 'Pengguna baru ditambahkan.');
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        if ($id === auth()->id()) {
            session()->flash('error', 'Tidak bisa menghapus akun sendiri.');

            return;
        }

        User::query()->findOrFail($id)->delete();
        session()->flash('status', 'Pengguna dihapus.');
    }

    public function render(RoleRegistryService $roles)
    {
        $query = User::query()->with('roles')->latest();

        if ($this->roleFilter === 'staff') {
            $query->whereHas('roles', fn ($q) => $q->whereIn('name', $roles->staffRoleNames()));
        } elseif ($this->roleFilter === 'customer') {
            $query->whereHas('roles', fn ($q) => $q->where('name', RoleName::Customer->value));
        }

        return view('livewire.admin.users.manage-users', [
            'users' => $query->paginate(12),
            'roleLabels' => $roles->staffRoleLabels(),
            'assignableRoles' => $roles->assignableRoleLabels(),
            'statuses' => UserStatus::cases(),
        ]);
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'email', 'phone', 'password']);
        $this->role = RoleName::Sales->value;
        $this->status = UserStatus::Active->value;
    }
}
