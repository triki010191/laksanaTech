@php
    $slides = app(\App\Services\Content\HeroSlideService::class)->activeSlides();
    $total = $slides->count();
@endphp

@if($total > 0)
<section
    class="relative overflow-hidden bg-white"
    x-data="{
        active: 0,
        total: {{ $total }},
        timer: null,
        go(i) {
            this.active = (i + this.total) % this.total;
        },
        next() { this.go(this.active + 1); },
        prev() { this.go(this.active - 1); },
        start() {
            this.stop();
            this.timer = setInterval(() => this.next(), 6000);
        },
        stop() {
            if (this.timer) clearInterval(this.timer);
        },
    }"
    x-init="start()"
    @mouseenter="stop()"
    @mouseleave="start()"
>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-8 py-10 sm:gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.15fr)] lg:gap-10 lg:py-16 xl:gap-12 xl:py-20">
            {{-- Teks slider — mobile: alur normal; desktop: tinggi tetap, slide ditumpuk absolut --}}
            <div class="order-2 lg:order-1">
                <div class="relative lg:min-h-[21rem]">
                    @foreach($slides as $index => $slide)
                        <div
                            x-show="active === {{ $index }}"
                            x-transition:enter="transition ease-out duration-400"
                            x-transition:enter-start="opacity-0 translate-y-3"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            @if($index > 0) x-cloak @endif
                            class="w-full lg:absolute lg:inset-x-0 lg:top-0"
                        >
                        @if(! empty($slide['badge']))
                        <span class="inline-flex items-center gap-2 rounded-full bg-brand-accent/15 px-4 py-1.5 text-hero-badge">
                            <span class="h-1.5 w-1.5 rounded-full bg-brand-accent"></span>
                            {{ $slide['badge'] }}
                        </span>
                        @endif

                        <h1 class="text-hero-title mt-5">
                            {{ $slide['title'] }}
                            @if(! empty($slide['emphasis']))
                            <span class="text-emphasis">{{ $slide['emphasis'] }}</span>
                            @endif
                        </h1>

                        @if(! empty($slide['description']))
                        <p class="text-hero-body mt-5 max-w-xl">
                            {{ $slide['description'] }}
                        </p>
                        @endif

                        <div class="mt-6 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:flex-wrap sm:items-center sm:gap-4">
                            @if(! empty($slide['cta_primary']['route']))
                                <a href="{{ route($slide['cta_primary']['route']) }}"
                                   class="text-btn inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 px-5 py-3 text-white shadow-lg transition hover:bg-slate-800 sm:w-auto sm:justify-start sm:px-6">
                                    {{ $slide['cta_primary']['label'] }}
                                    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            @endif
                            @if(! empty($slide['cta_secondary']['route']))
                                <a href="{{ route($slide['cta_secondary']['route']) }}"
                                   class="text-btn inline-flex w-full items-center justify-center gap-2 rounded-2xl border-2 border-green-500 bg-white px-5 py-3 text-green-700 transition hover:bg-green-50 sm:w-auto sm:justify-start sm:px-6">
                                    {{ $slide['cta_secondary']['label'] }}
                                </a>
                            @elseif(! empty($slide['cta_secondary']['url']))
                                <a href="{{ $slide['cta_secondary']['url'] }}" target="_blank" rel="noopener"
                                   class="text-btn inline-flex w-full items-center justify-center gap-2 rounded-2xl border-2 border-green-500 bg-white px-5 py-3 text-green-700 transition hover:bg-green-50 sm:w-auto sm:justify-start sm:px-6">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                                    {{ $slide['cta_secondary']['label'] }}
                                </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="hero-slide-controls relative mt-6 border-t border-slate-200/80 pt-5 sm:mt-8 lg:mt-10 lg:border-0 lg:pt-0">
                    <div class="flex flex-wrap items-center justify-between gap-3 sm:gap-4">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="flex gap-2">
                                @foreach($slides as $index => $slide)
                                    <button
                                        type="button"
                                        @click="go({{ $index }})"
                                        class="h-2.5 rounded-full transition-all"
                                        :class="active === {{ $index }} ? 'w-8 bg-brand-primary' : 'w-2.5 bg-slate-300 hover:bg-slate-400'"
                                        aria-label="Slide {{ $index + 1 }}"
                                    ></button>
                                @endforeach
                            </div>
                            <span class="text-caption font-medium tabular-nums text-slate-500">
                                <span x-text="String(active + 1).padStart(2, '0')">01</span>
                                / {{ str_pad((string) $total, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="prev()" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-sm hover:border-brand-primary hover:text-brand-primary" aria-label="Slide sebelumnya">‹</button>
                            <button type="button" @click="next()" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-sm hover:border-brand-primary hover:text-brand-primary" aria-label="Slide berikutnya">›</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gambar slider — src server-side agar tampil sebelum Alpine/JS siap --}}
            <div class="hero-slide-frame order-1 lg:order-2">
                @foreach($slides as $index => $slide)
                    <img
                        src="{{ $slide['image_url'] }}"
                        alt="{{ $slide['title'] ?? config('laksana.brand.name') }}"
                        class="hero-slide-img"
                        x-show="active === {{ $index }}"
                        @if($index > 0) x-cloak @endif
                        loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                        decoding="async"
                        fetchpriority="{{ $index === 0 ? 'high' : 'auto' }}"
                    >
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
