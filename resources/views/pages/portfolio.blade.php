@extends('layouts.app')

@section('content')
    <x-page-header compact eyebrow="Portofolio" title="Proyek yang Telah Kami Kerjakan"
        subtitle="Website, aplikasi, sistem POS, dan instalasi IT untuk sekolah, pemerintahan, UMKM, dan perusahaan." />

    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($portfolios as $item)
                <article class="flex flex-col overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    @if($item->cover_image_url)
                        <img src="{{ $item->cover_image_url }}" alt="{{ $item->title }}" class="h-44 w-full object-cover object-top">
                    @else
                        <div class="h-44 bg-gradient-to-br from-brand-primary/10 to-brand-secondary/10"></div>
                    @endif
                    <div class="flex flex-1 flex-col p-6">
                        <div class="mb-3 flex items-center justify-between gap-2">
                            <span class="rounded-full bg-brand-primary/10 px-2.5 py-0.5 text-caption font-semibold text-brand-primary">
                                {{ $item->category->name ?? 'Proyek' }}
                            </span>
                            @if($item->is_featured)
                                <span class="text-caption font-semibold text-brand-accent">★ Unggulan</span>
                            @endif
                        </div>
                        <p class="text-meta">{{ $item->client_name }}</p>
                        <h2 class="text-card-title mt-1">{{ $item->title }}</h2>
                        <p class="text-card-body mt-2 flex-1 line-clamp-3">{{ $item->short_description }}</p>
                        @if($item->technologies)
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach(array_slice($item->technologies, 0, 3) as $tech)
                                    <span class="rounded-md bg-slate-100 px-2 py-0.5 text-caption">{{ $tech }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-8">{{ $portfolios->links() }}</div>
    </section>
@endsection
