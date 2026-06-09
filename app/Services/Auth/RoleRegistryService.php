<?php

namespace App\Services\Auth;

use App\Enums\RoleName;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class RoleRegistryService
{
    public function sync(): void
    {
        foreach (RoleName::cases() as $role) {
            Role::findOrCreate($role->value, 'web');
        }
    }

    /** @return array<string, string> */
    public function staffRoleLabels(): array
    {
        return [
            RoleName::SuperAdmin->value => 'Super Admin',
            RoleName::Admin->value => 'Admin',
            RoleName::TransactionAdmin->value => 'Admin Transaksi',
            RoleName::Sales->value => 'Sales',
            RoleName::Freelancer->value => 'Karyawan / Freelancer',
        ];
    }

    /** @return list<string> */
    public function staffRoleNames(): array
    {
        return array_keys($this->staffRoleLabels());
    }

    /** @return array<string, string> */
    public function assignableRoleLabels(bool $bySuperAdmin = true): array
    {
        $all = $this->staffRoleLabels();

        if ($bySuperAdmin) {
            return $all;
        }

        unset($all[RoleName::SuperAdmin->value]);

        return $all;
    }

    /** @return Collection<int, User> */
    public function orderAssignableUsers(): Collection
    {
        $this->sync();

        return User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('name', $this->staffRoleNames()))
            ->orderBy('name')
            ->get();
    }
}
