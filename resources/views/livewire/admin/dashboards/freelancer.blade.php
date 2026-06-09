<div>
    <div class="mb-8 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Dashboard Pekerjaan Saya</h1>
            <p class="mt-1 text-sm text-slate-500">
                @if($profile)
                    {{ $profile->employmentLabel() }} · {{ $profile->specializationLabel() }}
                    @if($profile->job_title) · {{ $profile->job_title }}@endif
                @else
                    Tugas yang ditugaskan kepada Anda
                @endif
            </p>
        </div>
        <a href="{{ route('admin.profile') }}" class="admin-btn admin-btn--ghost inline-flex rounded-lg bg-slate-100 px-4 py-2 text-sm">Edit Profil</a>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="admin-stat-card admin-stat-card--blue">
            <p class="admin-stat-card__label">Total Tugas</p>
            <p class="admin-stat-card__value">{{ $assignedTotal }}</p>
        </div>
        <div class="admin-stat-card admin-stat-card--purple">
            <p class="admin-stat-card__label">Sedang Dikerjakan</p>
            <p class="admin-stat-card__value">{{ $inProgress }}</p>
        </div>
        <div class="admin-stat-card admin-stat-card--green">
            <p class="admin-stat-card__label">Selesai</p>
            <p class="admin-stat-card__value">{{ $completed }}</p>
        </div>
    </div>

    <div class="admin-panel mt-8">
        <div class="admin-panel__head">
            <h2 class="admin-panel__title">Pesanan Ditugaskan</h2>
            <a href="{{ route('admin.orders.index') }}" class="admin-link">Buka pesanan</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($assignedOrders as $order)
                <div class="px-5 py-4">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <p class="font-medium text-slate-900">{{ $order->order_number }}</p>
                        <x-admin.status-badge :status="$order->status->value" type="order" />
                    </div>
                    <p class="mt-1 text-sm text-slate-600">{{ $order->metadata['full_domain'] ?? '-' }} · {{ $order->user->name ?? '-' }}</p>
                    @if(isset($order->metadata['work_progress']))
                        <div class="mt-3">
                            <div class="flex justify-between text-xs text-slate-500">
                                <span>Tahap: {{ ucfirst(str_replace('_', ' ', $order->metadata['work_stage'] ?? '-')) }}</span>
                                <span>{{ $order->metadata['work_progress'] }}%</span>
                            </div>
                            <div class="admin-progress-bar mt-1">
                                <div class="admin-progress-bar__fill" style="width: {{ $order->metadata['work_progress'] }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <p class="px-5 py-8 text-center text-sm text-slate-500">Belum ada pesanan ditugaskan. Admin akan assign dari menu Pesanan.</p>
            @endforelse
        </div>
    </div>
</div>
