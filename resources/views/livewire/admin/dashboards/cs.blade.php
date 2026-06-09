<div>
    <div class="mb-8">
        <h1 class="font-display text-2xl font-bold text-slate-900">Dashboard Customer Service</h1>
        <p class="mt-1 text-sm text-slate-500">Pesan masuk & tanggapan pelanggan</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="admin-stat-card admin-stat-card--rose">
            <p class="admin-stat-card__label">Pesan Baru</p>
            <p class="admin-stat-card__value">{{ $leadsNew }}</p>
            <p class="admin-stat-card__sub">belum dibalas</p>
        </div>
        <div class="admin-stat-card admin-stat-card--purple">
            <p class="admin-stat-card__label">Tiket Aktif</p>
            <p class="admin-stat-card__value">{{ $leadsOpen }}</p>
            <p class="admin-stat-card__sub">new + contacted + qualified</p>
        </div>
        <div class="admin-stat-card admin-stat-card--blue">
            <p class="admin-stat-card__label">Hari Ini</p>
            <p class="admin-stat-card__value">{{ $leadsToday }}</p>
            <p class="admin-stat-card__sub">pesan masuk</p>
        </div>
    </div>

    @if($needsReply->isNotEmpty())
        <div class="admin-panel mt-8">
            <div class="admin-panel__head">
                <h2 class="admin-panel__title">Perlu Dibalas Segera</h2>
                <a href="{{ route('admin.leads.index') }}" class="admin-link">Buka semua pesan</a>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($needsReply as $lead)
                    <div class="px-5 py-3">
                        <p class="font-medium text-slate-900">{{ $lead->name }} · {{ $lead->email }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($lead->message, 150) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="admin-panel mt-6">
        <div class="admin-panel__head">
            <h2 class="admin-panel__title">Semua Pesan Terbaru</h2>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentLeads as $lead)
                <div class="flex items-start justify-between gap-3 px-5 py-3">
                    <div>
                        <p class="text-sm font-medium">{{ $lead->name }}</p>
                        <p class="text-xs text-slate-500">{{ $lead->created_at->format('d M Y H:i') }}</p>
                        <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $lead->message }}</p>
                    </div>
                    <x-admin.status-badge :status="$lead->status" type="lead" />
                </div>
            @empty
                <p class="px-5 py-6 text-sm text-slate-500">Belum ada pesan.</p>
            @endforelse
        </div>
    </div>
</div>
