@props(['mobile' => false])

@php
    $menu = config('laksana.layanan_menu', []);
    $marketplace = $menu['marketplace'] ?? ['label' => '', 'items' => []];
    $bidangLabel = $menu['bidang']['label'] ?? 'Bidang Layanan';
    $navServices = $navServices ?? collect();
@endphp

@if($mobile)
    <div x-data="{ marketplaceOpen: false, bidangOpen: false }" class="space-y-1">
        <div>
            <button type="button" @click="marketplaceOpen = !marketplaceOpen" class="flex w-full items-center justify-between py-1">
                <span>{{ $marketplace['label'] ?: 'Website Siap Pakai' }}</span>
                <svg class="h-4 w-4 transition" :class="marketplaceOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="marketplaceOpen" x-cloak class="ml-2 mt-2 space-y-1 border-l-2 border-slate-100 pl-3">
                @foreach($marketplace['items'] as $item)
                    <a href="{{ route($item['route'], $item['params'] ?? []) }}" class="block rounded-lg py-2">
                        <span class="text-card-title">{{ $item['title'] }}</span>
                        @if(!empty($item['description']))
                            <span class="text-caption mt-0.5 block">{{ $item['description'] }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
        <div>
            <button type="button" @click="bidangOpen = !bidangOpen" class="flex w-full items-center justify-between py-1">
                <span>{{ $bidangLabel }}</span>
                <svg class="h-4 w-4 transition" :class="bidangOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="bidangOpen" x-cloak class="ml-2 mt-2 space-y-1 border-l-2 border-slate-100 pl-3">
                @foreach($navServices as $service)
                    <a href="{{ route('services.show', $service->slug) }}" class="block rounded-lg py-2 text-card-title">{{ $service->name }}</a>
                @endforeach
                <a href="{{ route('services') }}" class="block py-2 text-sm text-brand-primary">Semua bidang layanan →</a>
            </div>
        </div>
    </div>
@else
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <p class="text-sidebar-label px-3 pb-2">{{ $marketplace['label'] }}</p>
            @foreach($marketplace['items'] as $item)
                <a href="{{ route($item['route'], $item['params'] ?? []) }}"
                   class="group flex items-start gap-3 rounded-xl px-3 py-2.5 transition hover:bg-slate-50">
                    <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl border border-slate-100 bg-white text-brand-accent transition group-hover:border-brand-accent group-hover:bg-brand-accent group-hover:text-white">
                        @include('partials.icons.service-icon', ['name' => $item['icon']])
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="text-card-title text-sm group-hover:text-brand-primary">{{ $item['title'] }}</span>
                        @if(!empty($item['description']))
                            <span class="text-caption mt-0.5 block leading-snug">{{ $item['description'] }}</span>
                        @endif
                    </span>
                </a>
            @endforeach
        </div>
        <div>
            <p class="text-sidebar-label px-3 pb-2">{{ $bidangLabel }}</p>
            <div class="max-h-64 space-y-0.5 overflow-y-auto px-1">
                @foreach($navServices as $service)
                    <a href="{{ route('services.show', $service->slug) }}"
                       class="group flex items-center gap-2 rounded-lg px-2 py-2 text-sm transition hover:bg-slate-50">
                        <span class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-lg bg-brand-accent/10 text-brand-accent">
                            @include('partials.icons.service-icon', ['name' => $service->category?->icon ?? 'app'])
                        </span>
                        <span class="text-card-title text-sm leading-snug group-hover:text-brand-primary">{{ $service->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endif
