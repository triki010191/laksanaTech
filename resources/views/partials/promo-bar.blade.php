@if(($site['promo_enabled'] ?? '1') === '1')
<div class="bg-brand-accent text-caption font-medium text-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-center gap-x-4 gap-y-1 px-4 py-2 text-center sm:justify-between sm:text-left">
        <span>{{ $site['promo_text'] ?? 'CV. Laksana Solution — Konsultan IT & Pengadaan Teknologi' }}</span>
        <a href="https://wa.me/{{ $site['whatsapp_number'] ?? config('laksana.brand.whatsapp') }}" class="underline underline-offset-2 hover:opacity-90" target="_blank" rel="noopener">
            {{ $site['promo_link_label'] ?? 'Konsultasi gratis via WhatsApp →' }}
        </a>
    </div>
</div>
@endif
