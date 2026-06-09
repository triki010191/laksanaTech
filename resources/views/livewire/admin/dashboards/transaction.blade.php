<div>
    <div class="mb-8">
        <h1 class="font-display text-2xl font-bold text-slate-900">Dashboard Transaksi</h1>
        <p class="mt-1 text-sm text-slate-500">Pesanan, konfirmasi pembayaran & invoice</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="admin-stat-card admin-stat-card--amber">
            <p class="admin-stat-card__label">Pesanan Pending</p>
            <p class="admin-stat-card__value">{{ $ordersPending }}</p>
            <p class="admin-stat-card__sub">menunggu bayar</p>
        </div>
        <div class="admin-stat-card admin-stat-card--blue">
            <p class="admin-stat-card__label">Dikonfirmasi</p>
            <p class="admin-stat-card__value">{{ $ordersConfirmed }}</p>
            <p class="admin-stat-card__sub">siap diproses</p>
        </div>
        <div class="admin-stat-card admin-stat-card--rose">
            <p class="admin-stat-card__label">Invoice Belum Lunas</p>
            <p class="admin-stat-card__value">{{ $invoicesUnpaid }}</p>
            <p class="admin-stat-card__sub">sent + overdue</p>
        </div>
    </div>

    <div class="admin-panel mt-8">
        <div class="admin-panel__head">
            <h2 class="admin-panel__title">Pesanan & Invoice</h2>
            <a href="{{ route('admin.orders.index') }}" class="admin-link">Kelola pesanan</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentOrders as $order)
                <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3">
                    <div>
                        <p class="text-sm font-medium">{{ $order->order_number }}</p>
                        <p class="text-xs text-slate-500">{{ $order->user->name ?? '-' }} · Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <x-admin.status-badge :status="$order->status->value" type="order" />
                        @if($order->invoices->first())
                            <span class="admin-badge admin-badge--slate">{{ $order->invoices->first()->status }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="px-5 py-6 text-sm text-slate-500">Belum ada pesanan.</p>
            @endforelse
        </div>
    </div>
</div>
