<?php

namespace App\Services\Staff;

use App\Enums\OrderStatus;
use App\Enums\RoleName;
use App\Enums\StaffSpecialization;
use App\Models\ContactLead;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Template;
use App\Models\User;
use Illuminate\Support\Collection;

class StaffDashboardService
{
    public function resolveView(User $user): string
    {
        if ($user->hasAnyRole([RoleName::SuperAdmin->value, RoleName::Admin->value])) {
            return 'livewire.admin.dashboards.admin';
        }

        $spec = $user->employeeProfile?->specialization;

        if ($user->hasRole(RoleName::TransactionAdmin->value) || $spec === StaffSpecialization::Transaction) {
            return 'livewire.admin.dashboards.transaction';
        }

        if ($spec === StaffSpecialization::CustomerService) {
            return 'livewire.admin.dashboards.cs';
        }

        if ($spec === StaffSpecialization::Marketing || $user->hasRole(RoleName::Sales->value)) {
            return 'livewire.admin.dashboards.marketing';
        }

        if ($user->hasRole(RoleName::Freelancer->value)
            || in_array($spec, [StaffSpecialization::Design, StaffSpecialization::Development], true)) {
            return 'livewire.admin.dashboards.freelancer';
        }

        return 'livewire.admin.dashboards.general';
    }

    /** @return array<string, mixed> */
    public function dataFor(User $user): array
    {
        if ($user->hasAnyRole([RoleName::SuperAdmin->value, RoleName::Admin->value])) {
            return $this->adminData();
        }

        $spec = $user->employeeProfile?->specialization;

        if ($user->hasRole(RoleName::TransactionAdmin->value) || $spec === StaffSpecialization::Transaction) {
            return $this->transactionData();
        }

        if ($spec === StaffSpecialization::CustomerService) {
            return $this->csData();
        }

        if ($spec === StaffSpecialization::Marketing || $user->hasRole(RoleName::Sales->value)) {
            return $this->marketingData();
        }

        if ($user->hasRole(RoleName::Freelancer->value)
            || in_array($spec, [StaffSpecialization::Design, StaffSpecialization::Development], true)) {
            return $this->freelancerData($user);
        }

        return $this->generalData($user);
    }

    /** @return array<string, mixed> */
    private function adminData(): array
    {
        $ordersBase = Order::query();

        return [
            'templateCount' => Template::query()->count(),
            'templateActive' => Template::query()->where('is_active', true)->count(),
            'ordersNew' => (clone $ordersBase)->where('status', OrderStatus::Pending->value)->count(),
            'ordersInProgress' => (clone $ordersBase)->whereIn('status', [
                OrderStatus::Confirmed->value,
                OrderStatus::InProgress->value,
            ])->count(),
            'ordersCompleted' => (clone $ordersBase)->where('status', OrderStatus::Completed->value)->count(),
            'ordersCancelled' => (clone $ordersBase)->whereIn('status', [
                OrderStatus::Cancelled->value,
                OrderStatus::Refunded->value,
            ])->count(),
            'userCount' => User::query()->count(),
            'customerCount' => User::role(RoleName::Customer->value)->count(),
            'leadsNew' => ContactLead::query()->where('status', 'new')->count(),
            'recentOrders' => Order::query()->with('user')->latest()->limit(5)->get(),
            'recentLeads' => ContactLead::query()->latest()->limit(5)->get(),
        ];
    }

    /** @return array<string, mixed> */
    private function marketingData(): array
    {
        return [
            'leadsNew' => ContactLead::query()->where('status', 'new')->count(),
            'leadsContacted' => ContactLead::query()->where('status', 'contacted')->count(),
            'leadsConverted' => ContactLead::query()->where('status', 'converted')->count(),
            'ordersNew' => Order::query()->where('status', OrderStatus::Pending->value)->count(),
            'recentLeads' => ContactLead::query()->latest()->limit(8)->get(),
            'recentOrders' => Order::query()->with('user')->latest()->limit(5)->get(),
        ];
    }

    /** @return array<string, mixed> */
    private function csData(): array
    {
        return [
            'leadsNew' => ContactLead::query()->where('status', 'new')->count(),
            'leadsOpen' => ContactLead::query()->whereIn('status', ['new', 'contacted', 'qualified'])->count(),
            'leadsToday' => ContactLead::query()->whereDate('created_at', today())->count(),
            'recentLeads' => ContactLead::query()->latest()->limit(10)->get(),
            'needsReply' => ContactLead::query()->where('status', 'new')->latest()->limit(5)->get(),
        ];
    }

    /** @return array<string, mixed> */
    private function transactionData(): array
    {
        return [
            'ordersPending' => Order::query()->where('status', OrderStatus::Pending->value)->count(),
            'ordersConfirmed' => Order::query()->where('status', OrderStatus::Confirmed->value)->count(),
            'invoicesUnpaid' => Invoice::query()->whereIn('status', ['sent', 'overdue'])->count(),
            'recentOrders' => Order::query()->with(['user', 'invoices'])->latest()->limit(8)->get(),
        ];
    }

    /** @return array<string, mixed> */
    private function freelancerData(User $user): array
    {
        $assigned = $this->assignedOrders($user);

        return [
            'assignedTotal' => $assigned->count(),
            'inProgress' => $assigned->filter(fn ($o) => $o->status === OrderStatus::InProgress)->count(),
            'completed' => $assigned->filter(fn ($o) => $o->status === OrderStatus::Completed)->count(),
            'assignedOrders' => $assigned->take(10),
            'profile' => $user->employeeProfile,
        ];
    }

    /** @return array<string, mixed> */
    private function generalData(User $user): array
    {
        return [
            'profile' => $user->employeeProfile,
            'recentOrders' => Order::query()->with('user')->latest()->limit(5)->get(),
            'leadsNew' => ContactLead::query()->where('status', 'new')->count(),
        ];
    }

    /** @return Collection<int, Order> */
    private function assignedOrders(User $user): Collection
    {
        return Order::query()
            ->with('user')
            ->where('metadata->assigned_to', $user->id)
            ->latest()
            ->get();
    }
}
