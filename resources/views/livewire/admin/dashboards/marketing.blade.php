<div>
    <div class="mb-8">
        <h1 class="font-display text-2xl font-bold text-slate-900">Dashboard Marketing & Sales</h1>
        <p class="mt-1 text-sm text-slate-500">Leads, konversi, dan pesanan masuk</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="admin-stat-card admin-stat-card--rose">
            <p class="admin-stat-card__label">Leads Baru</p>
            <p class="admin-stat-card__value">{{ $leadsNew }}</p>
            <p class="admin-stat-card__sub">perlu follow-up</p>
        </div>
        <div class="admin-stat-card admin-stat-card--blue">
            <p class="admin-stat-card__label">Dihubungi</p>
            <p class="admin-stat-card__value">{{ $leadsContacted }}</p>
            <p class="admin-stat-card__sub">dalam proses</p>
        </div>
        <div class="admin-stat-card admin-stat-card--green">
            <p class="admin-stat-card__label">Konversi</p>
            <p class="admin-stat-card__value">{{ $leadsConverted }}</p>
            <p class="admin-stat-card__sub">jadi pelanggan</p>
        </div>
        <div class="admin-stat-card admin-stat-card--amber">
            <p class="admin-stat-card__label">Pesanan Baru</p>
            <p class="admin-stat-card__value">{{ $ordersNew }}</p>
            <p class="admin-stat-card__sub">menunggu konfirmasi</p>
        </div>
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <div class="admin-panel">
            <div class="admin-panel__head">
                <h2 class="admin-panel__title">Leads Terbaru</h2>
                <a href="{{ route('admin.leads.index') }}" class="admin-link">Kelola leads</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentLeads as $lead)
                    <div class="px-5 py-3">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-medium">{{ $lead->name }}</p>
                            <x-admin.status-badge :status="$lead->status" type="lead" />
                        </div>
                        <p class="mt-1 text-xs text-slate-500">{{ $lead->email }}</p>
                        <p class="mt-1 line-clamp-2 text-xs text-slate-600">{{ $lead->message }}</p>
                    </div>
                @empty
                    <p class="px-5 py-6 text-sm text-slate-500">Belum ada leads.</p>
                @endforelse
            </div>
        </div>

        <div class="admin-panel">
            <div class="admin-panel__head">
                <h2 class="admin-panel__title">Pesanan Terbaru</h2>
                <a href="{{ route('admin.orders.index') }}" class="admin-link">Lihat pesanan</a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentOrders as $order)
                    <div class="flex items-center justify-between gap-3 px-5 py-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium">{{ $order->order_number }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $order->user->name ?? '-' }}</p>
                        </div>
                        <x-admin.status-badge :status="$order->status->value" type="order" />
                    </div>
                @empty
                    <p class="px-5 py-6 text-sm text-slate-500">Belum ada pesanan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
