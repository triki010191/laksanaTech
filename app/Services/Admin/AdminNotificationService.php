<?php

namespace App\Services\Admin;

use App\Models\AdminNotification;
use App\Models\ContactLead;
use App\Models\Order;
use App\Models\User;

class AdminNotificationService
{
    /** @var list<string> */
    private array $staffRoles = [
        'super_admin',
        'admin',
        'transaction_admin',
        'sales',
    ];

    public function notifyStaff(string $type, string $title, ?string $body = null, ?string $link = null): void
    {
        $userIds = User::query()
            ->whereHas('roles', fn ($q) => $q->whereIn('name', $this->staffRoles))
            ->pluck('id');

        foreach ($userIds as $userId) {
            AdminNotification::query()->create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'body' => $body,
                'link' => $link,
            ]);
        }
    }

    public function notifyOrderCreated(Order $order): void
    {
        $domain = $order->metadata['full_domain'] ?? '-';

        $this->notifyStaff(
            type: 'order',
            title: 'Pesanan baru: '.$order->order_number,
            body: ($order->user->name ?? 'Pelanggan').' · '.$domain.' · Rp '.number_format($order->total, 0, ',', '.'),
            link: route('admin.orders.index').'?view='.$order->id,
        );
    }

    public function notifyLeadCreated(ContactLead $lead): void
    {
        $this->notifyStaff(
            type: 'lead',
            title: 'Pesan baru dari '.$lead->name,
            body: \Illuminate\Support\Str::limit($lead->message, 120),
            link: route('admin.leads.index').'?view='.$lead->id,
        );
    }

    public function unreadCountFor(User $user): int
    {
        return AdminNotification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, AdminNotification> */
    public function recentFor(User $user, int $limit = 8)
    {
        return AdminNotification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function markRead(int $id, User $user): void
    {
        AdminNotification::query()
            ->where('user_id', $user->id)
            ->whereKey($id)
            ->update(['read_at' => now()]);
    }

    public function markAllRead(User $user): void
    {
        AdminNotification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
