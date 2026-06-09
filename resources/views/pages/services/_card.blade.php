@php
    $gradients = [
        'umkm_website' => 'from-sky-100 via-brand-primary/20 to-brand-secondary/20',
        'company_profile' => 'from-slate-100 via-brand-primary/15 to-slate-200',
        'ecommerce' => 'from-amber-50 via-brand-accent/25 to-orange-100',
        'school' => 'from-emerald-50 via-brand-secondary/20 to-teal-100',
        'government' => 'from-blue-50 via-brand-primary/20 to-indigo-100',
        'custom_software' => 'from-violet-50 via-brand-primary/15 to-purple-100',
        'mobile_app' => 'from-cyan-50 via-brand-primary/20 to-sky-100',
    ];
    $gradient = $gradients[$service->type->value ?? ''] ?? 'from-brand-primary/10 to-brand-secondary/10';
@endphp

<article class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
        @if($service->cover_image_url)
            <img src="{{ $service->cover_image_url }}" alt="{{ $service->name }}" class="h-full w-full object-cover object-top">
        @else
            <div class="flex h-full w-full flex-col items-center justify-center bg-gradient-to-br {{ $gradient }} p-6 text-center">
                <span class="font-display text-4xl font-bold text-brand-primary/40">{{ strtoupper(substr($service->name, 0, 1)) }}</span>
                <span class="text-meta mt-2 text-brand-primary">{{ $service->category->name ?? 'Layanan' }}</span>
            </div>
        @endif
    </div>

    <div class="p-4">
        <h3 class="text-card-title line-clamp-2">{{ $service->name }}</h3>
        <p class="text-caption mt-1.5 italic">{{ $service->popularity_label }}</p>
        @if($service->starting_price)
            <p class="text-price mt-2">Mulai Rp {{ number_format($service->starting_price, 0, ',', '.') }}</p>
        @endif

        <div class="mt-4 flex gap-2">
            <a href="{{ $service->view_url }}"
               @if($service->isExternalViewUrl()) target="_blank" rel="noopener" @endif
               class="text-btn inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-heading transition hover:border-slate-300 hover:bg-slate-50">
                @if($service->isExternalViewUrl())
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                @else
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                @endif
                Lihat
            </a>
            <a href="{{ $service->buy_url }}"
               class="text-btn inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-brand-accent px-3 py-2.5 text-white shadow-sm transition hover:opacity-90">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Beli
            </a>
        </div>
    </div>
</article>
