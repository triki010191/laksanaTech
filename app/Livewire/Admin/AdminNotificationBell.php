<?php

namespace App\Livewire\Admin;

use App\Services\Admin\AdminNotificationService;
use Livewire\Component;

class AdminNotificationBell extends Component
{
    public bool $open = false;

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function markRead(int $id, AdminNotificationService $notifications): void
    {
        $notifications->markRead($id, auth()->user());
    }

    public function markAllRead(AdminNotificationService $notifications): void
    {
        $notifications->markAllRead(auth()->user());
        $this->open = false;
    }

    public function render(AdminNotificationService $notifications)
    {
        $user = auth()->user();

        return view('livewire.admin.admin-notification-bell', [
            'unreadCount' => $notifications->unreadCountFor($user),
            'items' => $notifications->recentFor($user, 10),
        ]);
    }
}
