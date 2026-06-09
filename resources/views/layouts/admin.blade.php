<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin' }} — {{ config('laksana.brand.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="admin-body min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">
    <div class="flex min-h-screen">
        <aside class="admin-sidebar hidden w-64 shrink-0 flex-col bg-gradient-to-b from-slate-900 to-slate-800 p-4 text-slate-200 md:flex">
            <a href="{{ route('admin.dashboard') }}" class="admin-sidebar__brand mb-2 block border-b border-white/10 pb-4" aria-label="laksanatech admin">
                <x-brand-logo variant="dark" size="md" />
                <span class="admin-sidebar__brand-sub mt-2 block text-xs font-semibold uppercase tracking-wider text-slate-400">Admin Panel</span>
            </a>
            <nav class="admin-sidebar__nav flex-1 space-y-0.5 pt-2">
                <x-admin.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.profile')" :active="request()->routeIs('admin.profile')">Profil Saya</x-admin.nav-link>

                @hasanyrole('super_admin|admin|sales')
                <p class="admin-sidebar__section px-3 pt-4 text-[10px] font-bold uppercase tracking-widest text-slate-500">Produk</p>
                <x-admin.nav-link :href="route('admin.templates.index')" :active="request()->routeIs('admin.templates.*')">Produk Template</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.packages.index')" :active="request()->routeIs('admin.packages.*')">Paket Website</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.domain-extensions.index')" :active="request()->routeIs('admin.domain-extensions.*')">Ekstensi Domain</x-admin.nav-link>
                @endhasanyrole

                <x-admin.nav-link :href="route('admin.orders.index')" :active="request()->routeIs('admin.orders.*')">Pesanan</x-admin.nav-link>

                @hasanyrole('super_admin|admin|sales|transaction_admin')
                <x-admin.nav-link :href="route('admin.leads.index')" :active="request()->routeIs('admin.leads.*')">Pesan / Leads</x-admin.nav-link>
                @endhasanyrole

                @hasanyrole('super_admin|admin')
                <p class="admin-sidebar__section px-3 pt-4 text-[10px] font-bold uppercase tracking-widest text-slate-500">Tim & Pengaturan</p>
                <x-admin.nav-link :href="route('admin.employees.index')" :active="request()->routeIs('admin.employees.*')">Karyawan</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.settings.index')" :active="request()->routeIs('admin.settings.*')">Pengaturan CMS</x-admin.nav-link>
                @endhasanyrole

                @role('super_admin')
                <x-admin.nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">Kelola Pengguna</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.payment-settings.index')" :active="request()->routeIs('admin.payment-settings.*')">Payment Gateway</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.integrations.index')" :active="request()->routeIs('admin.integrations.*')">Integrasi API</x-admin.nav-link>
                @endrole

                @hasanyrole('super_admin|admin|sales')
                <p class="admin-sidebar__section px-3 pt-4 text-[10px] font-bold uppercase tracking-widest text-slate-500">Konten</p>
                <x-admin.nav-link :href="route('admin.hero-slides.index')" :active="request()->routeIs('admin.hero-slides.*')">Hero Slider</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.portfolios.index')" :active="request()->routeIs('admin.portfolios.*')">Portofolio</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.blog.index')" :active="request()->routeIs('admin.blog.*')">Blog</x-admin.nav-link>
                <x-admin.nav-link :href="route('admin.faqs.index')" :active="request()->routeIs('admin.faqs.*')">Pertanyaan</x-admin.nav-link>
                @endhasanyrole

                <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"
                   class="admin-nav-link admin-nav-link--external mt-6 block rounded-lg px-3 py-2.5 text-sm text-slate-400 hover:bg-white/5 hover:text-slate-200">
                    Ke Website ↗
                </a>
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="admin-header flex items-center justify-between border-b border-slate-200 bg-white px-6 py-4">
                <div>
                    <p class="text-sm font-medium text-slate-700">{{ auth()->user()->name }}</p>
                    @if(auth()->user()->employeeProfile)
                        <p class="text-xs text-slate-500">{{ auth()->user()->employeeProfile->specializationLabel() }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.profile') }}" class="text-sm text-brand-primary hover:underline">Profil</a>
                    @livewire('admin.admin-notification-bell')
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">Logout</button>
                    </form>
                </div>
            </header>
            <main class="admin-main flex-1 p-6">
                @if(session('status'))
                    <div class="admin-flash admin-flash--success mb-4 rounded-xl bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('status') }}</div>
                @endif
                @if(session('error'))
                    <div class="admin-flash admin-flash--error mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
                @endif
                @yield('content')
                {{ $slot ?? '' }}
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>
