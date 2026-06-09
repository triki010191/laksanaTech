@extends('layouts.app')

@section('content')
@php
    $tutorial = config('laksana.tutorial');
@endphp

<section class="page-header border-b border-slate-100 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-start">
            <div>
                <h1 class="text-page-title">
                    Dua cara <span class="text-highlight">mudah</span> pesan website.
                </h1>
                <p class="text-page-lead mt-4 max-w-2xl">{{ $tutorial['hero_subtitle'] }}</p>
                <p class="text-caption mt-4 lg:hidden">
                    <span class="font-semibold text-brand-accent">{{ $tutorial['estimate_label'] }}:</span>
                    {{ $tutorial['estimate_text'] }}
                </p>
            </div>
            <div class="hidden max-w-xs text-right lg:block">
                <p class="text-sidebar-label">{{ $tutorial['estimate_label'] }}</p>
                <p class="text-sidebar-note mt-2">{{ $tutorial['estimate_text'] }}</p>
            </div>
        </div>
    </div>
</section>

@foreach($tutorial['methods'] as $index => $method)
    <section @class(['tutorial-section py-16 lg:py-20', 'bg-slate-50/80' => $index % 2 === 1])>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
                <div @class(['lg:order-2' => $index % 2 === 1])>
                    <div class="flex items-center gap-3">
                        <span class="text-step-number">{{ $method['number'] }}</span>
                        <span class="text-eyebrow text-muted">{{ $method['label'] }}</span>
                    </div>
                    <h2 class="text-section-title mt-4">{{ $method['title'] }}</h2>
                    <p class="text-section-lead mt-4">{{ $method['description'] }}</p>
                    <a href="{{ route('checkout', ['flow' => $method['flow']]) }}"
                       @class([
                           'text-btn mt-8 inline-flex items-center gap-2 rounded-2xl px-6 py-3 transition',
                           'bg-brand-primary text-white hover:opacity-90' => $index === 0,
                           'border-2 border-brand-primary text-brand-primary hover:bg-brand-primary/5' => $index === 1,
                       ])>
                        {{ $method['cta_label'] }}
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
                <div @class(['lg:order-1' => $index % 2 === 1])>
                    @include('partials.tutorial.steps-flow', ['method' => $method])
                </div>
            </div>
        </div>
    </section>
@endforeach

@include('partials.tutorial.cta-jalur')
@endsection
