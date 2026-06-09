<div class="relative" wire:click.outside="$set('open', false)">
    <button type="button" wire:click="toggle" class="admin-notif-btn" aria-label="Notifikasi">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @if($unreadCount > 0)
            <span class="admin-notif-btn__badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
        @endif
    </button>

    @if($open)
        <div class="admin-notif-dropdown">
            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                <span class="text-sm font-semibold text-slate-800">Notifikasi</span>
                @if($unreadCount > 0)
                    <button type="button" wire:click="markAllRead" class="text-xs text-brand-primary hover:underline">Tandai semua dibaca</button>
                @endif
            </div>
            <ul class="max-h-80 overflow-y-auto">
                @forelse($items as $item)
                    <li wire:key="notif-{{ $item->id }}" @class(['admin-notif-item', 'admin-notif-item--unread' => $item->isUnread()])>
                        @if($item->link)
                            <a href="{{ $item->link }}" wire:click="markRead({{ $item->id }})" class="block px-4 py-3">
                                <p class="text-sm font-medium text-slate-800">{{ $item->title }}</p>
                                @if($item->body)<p class="mt-0.5 text-xs text-slate-500">{{ $item->body }}</p>@endif
                                <p class="mt-1 text-[10px] text-slate-400">{{ $item->created_at->diffForHumans() }}</p>
                            </a>
                        @else
                            <div class="px-4 py-3" wire:click="markRead({{ $item->id }})">
                                <p class="text-sm font-medium">{{ $item->title }}</p>
                            </div>
                        @endif
                    </li>
                @empty
                    <li class="px-4 py-8 text-center text-sm text-slate-500">Tidak ada notifikasi.</li>
                @endforelse
            </ul>
        </div>
    @endif
</div>
