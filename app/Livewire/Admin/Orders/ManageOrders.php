<?php

namespace App\Livewire\Admin\Orders;

use App\Services\Auth\RoleRegistryService;
use App\Enums\OrderStatus;
use App\Enums\WorkStage;
use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Kelola Pesanan')]
class ManageOrders extends Component
{
    use WithPagination;

    #[Url(as: 'view')]
    public ?int $viewingId = null;

    public string $statusFilter = '';

    public string $workStage = 'design';

    public int $workProgress = 0;

    public string $workNotes = '';

    public string $revisionNotes = '';

    public ?int $assignedTo = null;

    public function mount(): void
    {
        if ($this->viewingId) {
            $this->loadWorkFields();
        }
    }

    public function view(int $id): void
    {
        $this->viewingId = $id;
        $this->loadWorkFields();
    }

    public function closeView(): void
    {
        $this->viewingId = null;
    }

    public function acceptOrder(int $id): void
    {
        $this->updateStatus($id, OrderStatus::Confirmed->value);
    }

    public function startWork(int $id): void
    {
        $this->updateStatus($id, OrderStatus::InProgress->value);
    }

    public function updateStatus(int $id, string $status): void
    {
        if (! in_array($status, array_column(OrderStatus::cases(), 'value'), true)) {
            return;
        }

        $order = $this->findOrder($id);
        $updates = ['status' => $status];
        $meta = $order->metadata ?? [];

        if ($status === OrderStatus::Confirmed->value) {
            $meta['fulfillment_status'] = 'confirmed';
            $meta['accepted_at'] = now()->toIso8601String();
        }

        if ($status === OrderStatus::InProgress->value) {
            if (! $order->estimated_ready_at) {
                $updates['estimated_ready_at'] = now()->addHours(48);
            }
            $meta['fulfillment_status'] = 'in_progress';
            $meta['work_stage'] = $meta['work_stage'] ?? WorkStage::Design->value;
            $meta['work_progress'] = $meta['work_progress'] ?? 0;
            $meta['started_at'] = $meta['started_at'] ?? now()->toIso8601String();
        }

        if ($status === OrderStatus::Completed->value) {
            $meta['fulfillment_status'] = 'ready';
            $meta['work_progress'] = 100;
            $meta['work_stage'] = WorkStage::Delivery->value;
            $meta['completed_at'] = now()->toIso8601String();
        }

        if ($status === OrderStatus::Cancelled->value) {
            $meta['fulfillment_status'] = 'cancelled';
        }

        $updates['metadata'] = $meta;
        $order->update($updates);
        session()->flash('status', 'Status pesanan diperbarui.');
        $this->loadWorkFields();
    }

    public function saveWorkProgress(): void
    {
        if (! $this->viewingId) {
            return;
        }

        $this->validate([
            'workStage' => 'required|in:'.implode(',', array_column(WorkStage::cases(), 'value')),
            'workProgress' => 'required|integer|min:0|max:100',
            'assignedTo' => 'nullable|exists:users,id',
        ]);

        $order = $this->findOrder($this->viewingId);
        $meta = $order->metadata ?? [];
        $meta['work_stage'] = $this->workStage;
        $meta['work_progress'] = $this->workProgress;
        $meta['work_notes'] = $this->workNotes;
        $meta['revision_notes'] = $this->revisionNotes;

        if ($this->assignedTo) {
            $meta['assigned_to'] = $this->assignedTo;
        }

        $order->update(['metadata' => $meta]);

        if ($order->status === OrderStatus::Confirmed) {
            $this->updateStatus($order->id, OrderStatus::InProgress->value);
        }

        session()->flash('status', 'Progres pengerjaan disimpan.');
    }

    private function loadWorkFields(): void
    {
        if (! $this->viewingId) {
            return;
        }

        $order = Order::query()->find($this->viewingId);
        if (! $order) {
            return;
        }

        $meta = $order->metadata ?? [];
        $this->workStage = $meta['work_stage'] ?? WorkStage::Design->value;
        $this->workProgress = (int) ($meta['work_progress'] ?? 0);
        $this->workNotes = $meta['work_notes'] ?? '';
        $this->revisionNotes = $meta['revision_notes'] ?? '';
        $this->assignedTo = isset($meta['assigned_to']) ? (int) $meta['assigned_to'] : null;
    }

    private function findOrder(int $id): Order
    {
        $query = Order::query()->with('user');

        if (auth()->user()->hasRole('freelancer') && ! auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            $query->where('metadata->assigned_to', auth()->id());
        }

        return $query->findOrFail($id);
    }

    public function render(RoleRegistryService $roles)
    {
        $query = Order::query()->with(['user', 'items'])->latest();

        if (auth()->user()->hasRole('freelancer') && ! auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            $query->where('metadata->assigned_to', auth()->id());
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $viewing = null;
        if ($this->viewingId) {
            try {
                $viewing = $this->findOrder($this->viewingId)->load(['items', 'invoices']);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
                $this->viewingId = null;
            }
        }

        return view('livewire.admin.orders.manage-orders', [
            'orders' => $query->paginate(15),
            'viewing' => $viewing,
            'statuses' => OrderStatus::cases(),
            'workStages' => WorkStage::cases(),
            'freelancers' => $roles->orderAssignableUsers(),
        ]);
    }
}
