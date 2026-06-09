<div>
    <div class="mb-8">
        <h1 class="font-display text-2xl font-bold text-slate-900">Dashboard Admin</h1>
        <p class="mt-1 text-sm text-slate-500">Ringkasan marketplace & aktivitas terbaru</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="admin-stat-card admin-stat-card--blue">
            <p class="admin-stat-card__label">Produk Template Aktif</p>
            <p class="admin-stat-card__value">{{ $templateActive }}</p>
            <p class="admin-stat-card__sub">dari {{ $templateCount }} total</p>
        </div>
        <div class="admin-stat-card admin-stat-card--amber">
            <p class="admin-stat-card__label">Pesanan Masuk</p>
            <p class="admin-stat-card__value">{{ $ordersNew }}</p>
            <p class="admin-stat-card__sub">menunggu konfirmasi</p>
        </div>
        <div class="admin-stat-card admin-stat-card--purple">
            <p class="admin-stat-card__label">Dalam Progres</p>
            <p class="admin-stat-card__value">{{ $ordersInProgress }}</p>
            <p class="admin-stat-card__sub">dikerjakan tim</p>
        </div>
        <div class="admin-stat-card admin-stat-card--green">
            <p class="admin-stat-card__label">Selesai</p>
            <p class="admin-stat-card__value">{{ $ordersCompleted }}</p>
            <p class="admin-stat-card__sub">{{ $ordersCancelled }} batal/refund</p>
        </div>
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <div class="admin-stat-card admin-stat-card--slate">
            <p class="admin-stat-card__label">Akun Terdaftar</p>
            <p class="admin-stat-card__value">{{ $userCount }}</p>
            <p class="admin-stat-card__sub">{{ $customerCount }} pelanggan</p>
        </div>
        <div class="admin-stat-card admin-stat-card--rose">
            <p class="admin-stat-card__label">Pesan Baru</p>
            <p class="admin-stat-card__value">{{ $leadsNew }}</p>
            <p class="admin-stat-card__sub">belum dibalas</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="admin-stat-card admin-stat-card--link admin-stat-card--cyan">
            <p class="admin-stat-card__label">Kelola Pesanan</p>
            <p class="admin-stat-card__value text-lg">→</p>
            <p class="admin-stat-card__sub">Alur dari masuk sampai selesai</p>
        </a>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <div class="admin-panel">
            <div class="admin-panel__head">
                <h2 class="admin-panel__title">Pesanan Terbaru</h2>
                <a href="{{ route('admin.orders.index') }}" class="admin-link">Lihat semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentOrders as $order)
                    <div class="flex items-center justify-between gap-3 px-5 py-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium">{{ $order->order_number }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $order->user->name ?? '-' }} · {{ $order->metadata['full_domain'] ?? '-' }}</p>
                        </div>
                        <x-admin.status-badge :status="$order->status->value ?? $order->status" type="order" />
                    </div>
                @empty
                    <p class="px-5 py-6 text-sm text-slate-500">Belum ada pesanan.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-panel">
            <div class="admin-panel__head">
                <h2 class="admin-panel__title">Pesan / Leads Terbaru</h2>
                <a href="{{ route('admin.leads.index') }}" class="admin-link">Lihat semua</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentLeads as $lead)
                    <div class="px-5 py-3">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-medium">{{ $lead->name }}</p>
                            <x-admin.status-badge :status="$lead->status" type="lead" />
                        </div>
                        <p class="mt-1 line-clamp-2 text-xs text-slate-600">{{ $lead->message }}</p>
                    </div>
                @empty
                    <p class="px-5 py-6 text-sm text-slate-500">Belum ada pesan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
