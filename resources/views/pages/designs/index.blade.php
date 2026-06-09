@extends('layouts.app')

@section('content')
<section class="page-header border-b border-slate-100 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-start">
            <div>
                <span class="text-eyebrow-pill">{{ $site['design_badge'] ?? 'KOLEKSI · DESAIN' }}</span>
                <h1 class="text-page-title mt-4">
                    {!! preg_replace('/\bdesain\b/i', '<span class="text-highlight">desain</span>', e($site['design_title'] ?? 'Pilih desain yang siap kamu pakai.')) !!}
                </h1>
                <p class="text-page-lead mt-4 max-w-2xl">{{ $site['design_subtitle'] ?? '' }}</p>
            </div>
            <div class="hidden max-w-xs text-right lg:block">
                <p class="text-sidebar-label">{{ $site['design_sidebar_note'] ?? 'BISA GANTI KAPAN SAJA' }}</p>
                <p class="text-sidebar-note mt-2">{{ $site['design_sidebar_text'] ?? '' }}</p>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-wrap gap-1 border-b border-slate-200 pb-1">
            <a href="{{ route('designs', array_filter(['q' => $searchQuery ?: null])) }}"
               @class(['text-nav px-4 py-2 transition', 'border-b-2 border-brand-accent text-heading' => $activeCategory === 'all', 'text-muted hover:text-heading' => $activeCategory !== 'all'])>
                Semua
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('designs', array_filter(['category' => $cat->slug, 'q' => $searchQuery ?: null])) }}"
                   @class(['text-nav px-4 py-2 transition', 'border-b-2 border-brand-accent text-heading' => $activeCategory === $cat->slug, 'text-muted hover:text-heading' => $activeCategory !== $cat->slug])>
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('designs') }}" class="relative w-full lg:w-72">
            @if($activeCategory !== 'all')
                <input type="hidden" name="category" value="{{ $activeCategory }}">
            @endif
            <input type="search" name="q" value="{{ $searchQuery }}" placeholder="Cari desain website..."
                   class="w-full rounded-full border border-slate-200 py-2.5 pl-4 pr-10 text-card-body focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-subtle hover:text-brand-primary" aria-label="Cari">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
        </form>
    </div>

    <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($templates as $template)
            @include('pages.designs._card', ['template' => $template])
        @empty
            <p class="col-span-full py-16 text-center text-muted">Desain tidak ditemukan. Coba kata kunci atau kategori lain.</p>
        @endforelse
    </div>

    @if($templates->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $templates->links() }}
        </div>
    @endif
</section>
@endsection
