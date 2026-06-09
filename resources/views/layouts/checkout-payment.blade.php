<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Metode Pembayaran — '.config('laksana.brand.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-50 font-sans text-body antialiased">
    @include('partials.promo-bar')

    <header class="border-b border-slate-100 bg-white py-4">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 sm:px-6">
            <a href="{{ route('home') }}">
                <x-brand-logo variant="light" />
            </a>
            <span class="text-caption font-semibold uppercase text-muted">Pembayaran Aman</span>
        </div>
    </header>

    <main class="py-8">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
