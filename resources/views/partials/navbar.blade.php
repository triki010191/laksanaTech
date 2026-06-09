<header class="sticky top-0 z-50 border-b border-slate-100 bg-white/90 backdrop-blur-md" x-data="{ open: false, servicesOpen: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center" aria-label="{{ config('laksana.brand.name') }}">
            <x-brand-logo variant="light" />
        </a>

        <nav class="hidden items-center gap-8 text-nav md:flex">
            <a href="{{ route('designs') }}" class="transition hover:text-brand-primary">Cari Design</a>

            {{-- Layanan dropdown — pt-3 pada wrapper = jembatan hover agar tidak hilang saat pindah ke submenu --}}
            <div class="relative" @mouseenter="servicesOpen = true" @mouseleave="servicesOpen = false">
                <button type="button"
                    @click="servicesOpen = !servicesOpen"
                    class="inline-flex items-center gap-1 py-1 transition hover:text-brand-primary"
                    :class="servicesOpen && 'text-brand-primary'"
                    aria-haspopup="true"
                    :aria-expanded="servicesOpen">
                    Layanan
                    <svg class="h-4 w-4 transition" :class="servicesOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="servicesOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak
                     class="absolute left-1/2 top-full z-50 w-[36rem] -translate-x-1/2 pt-2">
                    <div class="rounded-2xl border border-slate-100 bg-white p-3 shadow-xl shadow-slate-200/60">
                        @include('partials.navbar-services-menu')
                        <div class="mt-1 border-t border-slate-100 pt-1">
                            <a href="{{ route('services') }}" class="block rounded-xl px-3 py-2.5 text-center text-xs font-semibold text-brand-primary hover:bg-slate-50">
                                Lihat semua layanan →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ route('portfolio') }}" class="hover:text-brand-primary transition">Portofolio</a>
            <a href="{{ route('tutorial') }}" @class(['transition hover:text-brand-primary', 'text-brand-primary' => request()->routeIs('tutorial')])>Tutorial</a>
            <a href="{{ route('blog') }}" class="hover:text-brand-primary transition">Blog</a>
            <a href="{{ route('pertanyaan') }}" @class(['transition hover:text-brand-primary', 'text-brand-primary' => request()->routeIs('pertanyaan')])>Pertanyaan</a>
            <a href="{{ route('about') }}" class="hover:text-brand-primary transition">Tentang</a>
        </nav>

        <div class="hidden items-center gap-3 md:flex">
            @auth
                @if(auth()->user()->hasRole('customer'))
                    <a href="{{ route('customer.dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-brand-primary">Dashboard</a>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-brand-primary">Admin</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-brand-primary">Masuk</a>
            @endauth
            <a href="{{ route('contact') }}" class="text-btn rounded-2xl bg-brand-primary px-5 py-2.5 text-white shadow-sm transition hover:opacity-90">
                Konsultasi Gratis
            </a>
        </div>

        <button type="button" class="md:hidden rounded-xl border border-slate-200 p-2" @click="open = !open" aria-label="Menu">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>

    <div class="border-t border-slate-100 px-4 py-4 md:hidden" x-show="open" x-cloak>
        <nav class="flex flex-col gap-3 text-nav">
            <a href="{{ route('designs') }}">Cari Design</a>
            @include('partials.navbar-services-menu', ['mobile' => true])
            <a href="{{ route('portfolio') }}">Portofolio</a>
            <a href="{{ route('tutorial') }}">Tutorial</a>
            <a href="{{ route('blog') }}">Blog</a>
            <a href="{{ route('pertanyaan') }}">Pertanyaan</a>
            <a href="{{ route('about') }}">Tentang</a>
            <a href="{{ route('contact') }}" class="mt-2 rounded-2xl bg-brand-primary px-4 py-2 text-center text-white">Konsultasi</a>
        </nav>
    </div>
</header>
