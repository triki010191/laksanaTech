@php
    $cta = config('laksana.tutorial.cta');
    $wa = app(\App\Services\Settings\SiteSettingsService::class)->normalizeWhatsappNumber($site['whatsapp_number'] ?? config('laksana.brand.whatsapp'));
@endphp

<section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
    <div class="tutorial-cta-box">
        <div class="max-w-xl">
            <span class="text-eyebrow">{{ $cta['badge'] }}</span>
            <h2 class="text-section-title mt-3 text-white">
                Pilih jalur dan
                <span class="relative inline-block">
                    <span class="relative z-10 text-highlight">bikin website</span>
                    <span class="absolute bottom-1 left-0 -z-0 h-2 w-full bg-brand-accent/70"></span>
                </span>
                kamu sekarang.
            </h2>
        </div>
        <div class="flex w-full max-w-sm flex-col gap-3">
            <a href="{{ route('checkout', ['flow' => 'domain_first']) }}"
               class="text-btn inline-flex items-center justify-between gap-3 rounded-2xl bg-brand-accent px-5 py-3.5 text-white transition hover:opacity-90">
                <span class="inline-flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9V3m0 18a9 9 0 009-9m-9 9a9 9 0 009 9"/></svg>
                    Mulai dari domain
                </span>
                <span>→</span>
            </a>
            <a href="{{ route('checkout', ['flow' => 'template_first']) }}"
               class="text-btn inline-flex items-center gap-2 rounded-2xl border border-white/20 bg-white/5 px-5 py-3.5 text-white transition hover:bg-white/10">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                Mulai dari template
            </a>
            <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener"
               class="text-btn inline-flex items-center gap-2 rounded-2xl border border-white/20 bg-white/5 px-5 py-3.5 text-white transition hover:bg-white/10">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                Konsultasi dulu via WhatsApp
            </a>
        </div>
    </div>
</section>
