@include('partials.footer-cta')

<footer class="bg-slate-900 text-slate-300">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 lg:grid-cols-4 lg:px-8">
        <div class="min-w-0 lg:col-span-1">
            <a href="{{ route('home') }}" class="mb-4 block max-w-[140px]" aria-label="{{ config('laksana.brand.name') }}">
                <x-brand-logo variant="dark" size="sm" layout="stack" />
            </a>
            <p class="text-footer-link leading-relaxed">
                {{ $site['footer_tagline'] ?? config('laksana.brand.tagline') }}
            </p>
            @if(!empty($site['facebook_url']) || !empty($site['instagram_url']) || !empty($site['linkedin_url']))
            <div class="mt-5 flex gap-3">
                @if(!empty($site['facebook_url']))
                    <a href="{{ $site['facebook_url'] }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-700 text-slate-400 hover:border-brand-primary hover:text-white" aria-label="Facebook">f</a>
                @endif
                @if(!empty($site['instagram_url']))
                    <a href="{{ $site['instagram_url'] }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-700 text-slate-400 hover:border-brand-primary hover:text-white" aria-label="Instagram">ig</a>
                @endif
                @if(!empty($site['linkedin_url']))
                    <a href="{{ $site['linkedin_url'] }}" target="_blank" rel="noopener" class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-700 text-slate-400 hover:border-brand-primary hover:text-white" aria-label="LinkedIn">in</a>
                @endif
            </div>
            @endif
        </div>

        <div>
            <h3 class="text-footer-heading">Produk</h3>
            <ul class="mt-4 space-y-2.5">
                <li><a href="{{ route('designs') }}" class="text-footer-link hover:text-white">Cari Design</a></li>
                @foreach(config('laksana.layanan_menu.marketplace.items', []) as $item)
                    <li><a href="{{ route($item['route'], $item['params'] ?? []) }}" class="text-footer-link hover:text-white">{{ $item['title'] }}</a></li>
                @endforeach
                <li><a href="{{ route('services') }}" class="text-footer-link hover:text-white">Bidang Layanan</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-footer-heading">Sumber Daya</h3>
            <ul class="mt-4 space-y-2.5">
                <li><a href="{{ route('blog') }}" class="text-footer-link hover:text-white">Blog</a></li>
                <li><a href="{{ route('tutorial') }}" class="text-footer-link hover:text-white">Tutorial</a></li>
                <li><a href="{{ route('pertanyaan') }}" class="text-footer-link hover:text-white">Pertanyaan</a></li>
                <li><a href="{{ route('portfolio') }}" class="text-footer-link hover:text-white">Portofolio</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-footer-heading">Hubungi Kami</h3>
            <ul class="mt-4 space-y-3 text-footer-link">
                <li class="flex gap-2">
                    <span class="text-brand-primary">📍</span>
                    <span>{{ config('laksana.brand.address') }}</span>
                </li>
                <li class="flex gap-2">
                    <span class="text-brand-primary">📞</span>
                    @php $displayPhone = $site['contact_phone'] ?? config('laksana.brand.phone'); @endphp
                    <a href="https://wa.me/{{ app(\App\Services\Settings\SiteSettingsService::class)->normalizeWhatsappNumber($site['whatsapp_number'] ?? null) }}" class="hover:text-white">{{ $displayPhone }}</a>
                </li>
                <li class="flex gap-2">
                    <span class="text-brand-primary">✉</span>
                    <a href="mailto:{{ config('laksana.brand.email') }}" class="hover:text-white">{{ config('laksana.brand.email') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="border-t border-slate-800">
        <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-6 text-xs text-slate-500 sm:flex-row sm:px-6 lg:px-8">
            <p class="text-caption">&copy; {{ date('Y') }} {{ $site['footer_copyright'] ?? config('laksana.brand.company') }}. All rights reserved.</p>
            <div class="flex gap-4 text-caption">
                <a href="{{ route('about') }}" class="text-footer-link hover:text-slate-300">Tentang</a>
                <a href="{{ route('contact') }}" class="text-footer-link hover:text-slate-300">Kontak</a>
            </div>
        </div>
    </div>
</footer>
