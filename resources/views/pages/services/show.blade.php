@extends('layouts.app')

@section('content')
    <x-page-header compact eyebrow="Layanan" :title="$service->name" :subtitle="$service->short_description" />

    <section class="mx-auto max-w-5xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-2">
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-slate-50">
                @if($service->cover_image_url)
                    <img src="{{ $service->cover_image_url }}" alt="{{ $service->name }}" class="aspect-[4/3] w-full object-cover object-top">
                @else
                    <div class="flex aspect-[4/3] items-center justify-center bg-gradient-to-br from-brand-primary/10 to-brand-secondary/10">
                        <span class="font-display text-6xl font-bold text-brand-primary/30">{{ strtoupper(substr($service->name, 0, 1)) }}</span>
                    </div>
                @endif
            </div>
            <div>
                @if($service->starting_price)
                    <p class="text-price text-2xl">Mulai Rp {{ number_format($service->starting_price, 0, ',', '.') }}</p>
                @endif
                @if($service->delivery_estimate)
                    <p class="text-caption mt-2">Estimasi: {{ $service->delivery_estimate }}</p>
                @endif
                <div class="prose-laksana mt-6">
                    <p>{{ $service->description ?? $service->short_description }}</p>
                </div>
                <div class="mt-8 flex flex-wrap gap-3">
                    @if($service->isExternalViewUrl())
                        <a href="{{ $service->view_url }}" target="_blank" rel="noopener"
                           class="text-btn inline-flex items-center gap-2 rounded-xl border border-slate-200 px-5 py-3 text-heading hover:bg-slate-50">
                            Lihat Demo
                        </a>
                    @endif
                    <a href="{{ route('contact') }}"
                       class="text-btn inline-flex items-center gap-2 rounded-xl bg-brand-accent px-5 py-3 text-white hover:opacity-90">
                        Konsultasi & Penawaran
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
