@props([
    'variant' => 'light',
    'showText' => true,
    'size' => 'md',
    'layout' => 'inline',
])

@php
    $customLogo = $site['brand_logo_path'] ?? '';

    if ($customLogo) {
        $logoPath = asset('storage/'.$customLogo);
    } else {
        $logoPath = asset(config('laksana.brand.logos.main'));
    }

    $imgClass = 'brand-logo-img brand-logo-img--'.$size;
    $textClass = $variant === 'dark' ? 'text-white' : 'text-slate-900';
    $textSizeClass = $size === 'sm' ? 'brand-logo-text--sm' : 'brand-logo-text--md';
    $wrapperClass = $layout === 'stack'
        ? 'inline-flex flex-col items-start gap-1.5 max-w-full'
        : 'inline-flex items-center gap-2.5 max-w-full';
@endphp

<span {{ $attributes->merge(['class' => $wrapperClass]) }}>
    <img
        src="{{ $logoPath }}"
        alt="{{ config('laksana.brand.name') }}"
        class="{{ $imgClass }}"
        decoding="async"
    >
    @if($showText)
        <span class="font-display {{ $textSizeClass }} font-bold tracking-tight lowercase">
            <span class="{{ $textClass }}">laksana</span><span class="text-brand-primary">tech</span>
        </span>
    @endif
</span>
