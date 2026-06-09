@props([
    'eyebrow' => null,
    'title',
    'subtitle' => null,
    'compact' => false,
    'border' => false,
])

<section @class([
    'page-header',
    'page-header--compact' => $compact,
    'border-b border-slate-100 bg-white' => $border,
])>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div @class(['grid gap-8 lg:items-start', 'lg:grid-cols-[1fr_auto]' => isset($aside), 'max-w-2xl' => $compact && ! isset($aside)])>
            <div>
                @if($eyebrow)
                    <span class="text-eyebrow">{{ $eyebrow }}</span>
                @endif
                <h1 @class(['text-page-title', 'mt-2' => $eyebrow])>{!! $title !!}</h1>
                @if($subtitle)
                    <p class="text-page-lead mt-4">{{ $subtitle }}</p>
                @endif
                {{ $slot }}
            </div>
            @isset($aside)
                <div class="hidden max-w-xs text-right lg:block">
                    {{ $aside }}
                </div>
            @endisset
        </div>
    </div>
</section>
