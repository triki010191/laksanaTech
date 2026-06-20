@extends('layouts.app')

@section('content')
    <article class="mx-auto max-w-3xl px-4 pb-16 pt-8 sm:px-6 lg:px-8">
        <a href="{{ route('blog') }}" class="text-sm font-medium text-brand-primary hover:underline">← Kembali ke Blog</a>

        <header class="mt-6">
            <div class="flex flex-wrap items-center gap-2 text-caption text-muted">
                @if($post->category)
                    <span class="rounded-full bg-brand-primary/10 px-2.5 py-0.5 font-semibold text-brand-primary">
                        {{ $post->category->name }}
                    </span>
                @endif
                <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('d F Y') }}</time>
                @if($post->author)
                    <span>· {{ $post->author->name }}</span>
                @endif
                <span>· {{ number_format($post->views_count) }} views</span>
            </div>
            <h1 class="font-display mt-4 text-3xl font-bold leading-tight text-heading sm:text-4xl">{{ $post->title }}</h1>
            @if($post->excerpt)
                <p class="mt-4 text-lg text-muted">{{ $post->excerpt }}</p>
            @endif
        </header>

        @if($post->featured_image)
            <div class="mt-8 overflow-hidden rounded-3xl border border-slate-100 shadow-sm">
                <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full object-cover">
            </div>
        @endif

        <div class="prose prose-slate mt-10 max-w-none text-body">
            {!! $post->content !!}
        </div>

        @if($post->tags->isNotEmpty())
            <div class="mt-10 flex flex-wrap gap-2 border-t border-slate-100 pt-6">
                @foreach($post->tags as $tag)
                    <span class="rounded-md bg-slate-100 px-3 py-1 text-caption text-slate-600">#{{ $tag->name }}</span>
                @endforeach
            </div>
        @endif
    </article>

    @if($related->isNotEmpty())
        <section class="border-t border-slate-100 bg-slate-50 py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-section-title mb-6">Artikel Terkait</h2>
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach($related as $item)
                        <a href="{{ route('blog.show', $item->slug) }}"
                           class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                            <p class="text-caption text-brand-primary">{{ $item->category->name ?? 'Blog' }}</p>
                            <p class="text-card-title mt-1 line-clamp-2">{{ $item->title }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
