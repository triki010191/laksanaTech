@if(($site['cta_enabled'] ?? '1') === '1')
<section class="bg-brand-primary py-12">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 px-4 sm:flex-row sm:px-6 lg:px-8">
        <div class="text-center sm:text-left">
            <h2 class="text-section-title text-white">{{ $site['cta_title'] ?? 'Siap Bikin Website Tanpa Ribet?' }}</h2>
            <p class="text-page-lead mt-2 max-w-xl text-white/90">{{ $site['cta_subtitle'] ?? '' }}</p>
        </div>
        @php
            $ctaUrl = $site['cta_button_url'] ?? route('designs');
            $ctaHref = str_starts_with($ctaUrl, 'http') ? $ctaUrl : url($ctaUrl);
        @endphp
        <a href="{{ $ctaHref }}"
           class="text-btn flex-shrink-0 rounded-2xl bg-white px-8 py-3.5 text-brand-primary shadow-lg transition hover:bg-slate-50">
            {{ $site['cta_button_label'] ?? 'Buat Website Sekarang' }}
        </a>
    </div>
</section>
@endif
