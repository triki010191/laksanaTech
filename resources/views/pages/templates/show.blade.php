@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">
    <a href="{{ route('designs') }}" class="text-link">← Kembali ke Cari Design</a>

    <div class="mt-6 grid gap-8 lg:grid-cols-2">
        <div class="overflow-hidden rounded-3xl border">
            <x-template-preview :template="$template" wrapper-class="aspect-[4/3]" :linkable="false" />
        </div>
        <div>
            <p class="text-eyebrow">{{ $template->category->name ?? 'Website' }}</p>
            <h1 class="text-page-title mt-2">{{ $template->name }}</h1>
            <p class="text-price mt-4 text-2xl">
                Rp {{ number_format($template->sale_price ?? $template->price, 0, ',', '.') }}
                @if($template->sale_price)
                    <span class="text-caption font-normal line-through">Rp {{ number_format($template->price, 0, ',', '.') }}</span>
                @endif
            </p>
            <p class="text-section-lead mt-4">{{ $template->description ?? $template->short_description }}</p>
            @if($template->tech_stack)
                <p class="mt-2 text-sm text-slate-500">Stack: {{ $template->tech_stack }}</p>
            @endif
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('checkout', ['flow' => 'template_first', 'template' => $template->id]) }}"
                   class="rounded-2xl bg-brand-primary px-6 py-3 text-sm font-semibold text-white">Pesan Template Ini</a>
                @if($template->live_demo_link)
                    <a href="{{ $template->live_demo_link }}" target="_blank" rel="noopener noreferrer"
                       class="rounded-2xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700">Lihat Demo</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
