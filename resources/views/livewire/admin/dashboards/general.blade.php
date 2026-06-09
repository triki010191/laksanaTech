<div>
    <div class="mb-8">
        <h1 class="font-display text-2xl font-bold text-slate-900">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">Selamat datang, {{ auth()->user()->name }}</p>
    </div>

    @if($profile ?? null)
        <div class="admin-panel mb-6 p-5">
            <p class="text-sm text-slate-500">{{ $profile->employee_code }} · {{ $profile->employmentLabel() }} · {{ $profile->specializationLabel() }}</p>
            @if($profile->bio)<p class="mt-2 text-sm text-slate-700">{{ $profile->bio }}</p>@endif
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="admin-stat-card admin-stat-card--rose">
            <p class="admin-stat-card__label">Pesan Baru</p>
            <p class="admin-stat-card__value">{{ $leadsNew }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="admin-stat-card admin-stat-card--link admin-stat-card--cyan">
            <p class="admin-stat-card__label">Lihat Pesanan</p>
            <p class="admin-stat-card__value text-lg">→</p>
        </a>
    </div>
</div>
