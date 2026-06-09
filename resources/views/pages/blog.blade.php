@extends('layouts.app')

@section('content')
    <x-page-header compact eyebrow="Blog" title="Berita & Artikel"
        subtitle="Tips website, domain, hosting, UMKM, dan transformasi digital dari tim LaksanaTech." />

    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        @if($categories->isNotEmpty())
            <div class="mb-8 flex flex-wrap gap-2">
                <a href="{{ route('blog') }}"
                   @class(['rounded-full px-4 py-1.5 text-sm font-medium transition', 'bg-brand-primary text-white' => ! $activeCategory, 'bg-slate-100 text-slate-600 hover:bg-slate-200' => $activeCategory])>
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('blog', ['kategori' => $cat->slug]) }}"
                       @class(['rounded-full px-4 py-1.5 text-sm font-medium transition', 'bg-brand-primary text-white' => $activeCategory === $cat->slug, 'bg-slate-100 text-slate-600 hover:bg-slate-200' => $activeCategory !== $cat->slug])>
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        @endif

        @if($posts->isNotEmpty())
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    <article class="flex flex-col overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="relative h-44 overflow-hidden bg-gradient-to-br from-brand-primary/15 via-brand-secondary/10 to-brand-accent/10">
                            @if($post->featured_image)
                                <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
                            @endif
                        </div>
                        <div class="flex flex-1 flex-col p-6">
                            <div class="mb-2 flex flex-wrap items-center gap-2 text-caption text-muted">
                                @if($post->category)
                                    <span class="rounded-full bg-brand-primary/10 px-2.5 py-0.5 font-semibold text-brand-primary">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                                <time datetime="{{ $post->published_at?->toDateString() }}">
                                    {{ $post->published_at?->format('d M Y') }}
                                </time>
                            </div>
                            <h2 class="text-card-title line-clamp-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-brand-primary">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            <p class="text-card-body mt-2 flex-1 line-clamp-3">{{ $post->excerpt }}</p>
                            <a href="{{ route('blog.show', $post->slug) }}"
                               class="mt-4 inline-flex text-sm font-semibold text-brand-primary hover:underline">
                                Baca selengkapnya →
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-10">{{ $posts->links() }}</div>
        @else
            <div class="rounded-3xl border border-dashed border-slate-200 bg-white px-6 py-16 text-center">
                <p class="text-card-title text-slate-700">Belum ada artikel</p>
                <p class="text-card-body mt-2 text-muted">Artikel blog akan segera dipublikasikan.</p>
            </div>
        @endif
    </section>
@endsection
