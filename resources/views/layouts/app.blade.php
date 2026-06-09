<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle ?? config('laksana.seo.default_title') }}</title>
    <meta name="description" content="{{ $pageDescription ?? config('laksana.seo.default_description') }}">
    <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">
    <meta property="og:title" content="{{ $ogTitle ?? ($pageTitle ?? config('laksana.seo.default_title')) }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($pageDescription ?? config('laksana.seo.default_description')) }}">
    <meta property="og:image" content="{{ $ogImage ?? asset(config('laksana.brand.logos.main')) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-white font-sans text-body antialiased">
    @include('partials.promo-bar')
    @include('partials.navbar')

    <main>
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    @include('partials.footer')

    @include('partials.whatsapp-float')

    @livewireScripts
</body>
</html>
