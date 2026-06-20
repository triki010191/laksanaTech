@props([
    'template',
    'imgClass' => 'h-full w-full object-cover object-top',
    'wrapperClass' => '',
    'linkable' => true,
])

@php
    $demoLink = $template->live_demo_link;
    $sources = $template->thumbnail_sources;
    $categoryName = $template->category->name ?? 'Website';
@endphp

<div {{ $attributes->class(['relative overflow-hidden bg-slate-100', $wrapperClass]) }}>
    @if(count($sources) > 0)
        @if($linkable && $demoLink)
            <a href="{{ $demoLink }}" target="_blank" rel="noopener noreferrer"
               class="block h-full w-full"
               aria-label="Lihat demo {{ $template->name }}">
        @endif

        <div class="h-full w-full"
             x-data="{
                 sources: @js($sources),
                 index: 0,
                 get currentSrc() { return this.sources[this.index] ?? '' },
                 onError() {
                     if (this.index < this.sources.length - 1) {
                         this.index++;
                     } else {
                         this.index = -1;
                     }
                 }
             }">
            <img x-show="index >= 0"
                 :src="currentSrc"
                 alt="{{ $template->name }}"
                 loading="lazy"
                 decoding="async"
                 @class([$imgClass])
                 x-on:error="onError()">
            <div x-show="index < 0"
                 x-cloak
                 class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-primary/10 to-brand-secondary/10">
                <span class="text-meta text-brand-primary">{{ $categoryName }}</span>
            </div>
        </div>

        @if($linkable && $demoLink)
            </a>
        @endif
    @else
        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-primary/10 to-brand-secondary/10">
            <span class="text-meta text-brand-primary">{{ $categoryName }}</span>
        </div>
    @endif
</div>
