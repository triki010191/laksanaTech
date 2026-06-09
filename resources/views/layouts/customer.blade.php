<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }} — {{ config('laksana.brand.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6">
            <a href="{{ route('customer.dashboard') }}" aria-label="laksanatech">
                <x-brand-logo size="sm" />
            </a>
            <nav class="flex items-center gap-4 text-sm">
                <a href="{{ route('customer.dashboard') }}" class="hover:text-brand-primary">Dashboard</a>
                <a href="{{ route('customer.orders') }}" class="hover:text-brand-primary">Pesanan</a>
                <a href="{{ route('customer.invoices') }}" class="hover:text-brand-primary">Invoice</a>
                <a href="{{ route('customer.projects') }}" class="hover:text-brand-primary">Proyek</a>
                <a href="{{ route('customer.tickets') }}" class="hover:text-brand-primary">Support</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-slate-500 hover:text-red-600">Logout</button>
                </form>
            </nav>
        </div>
    </header>
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6">
        @if(session('status'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('status') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
        @endif
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    @include('partials.whatsapp-float')

    @livewireScripts
</body>
</html>
