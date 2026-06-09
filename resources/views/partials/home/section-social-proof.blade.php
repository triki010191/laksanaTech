@php $s = config('laksana.home.section_04'); @endphp

<section class="home-section home-section--muted py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @include('partials.home.section-header', [
            'eyebrow' => $s['eyebrow'],
            'title' => $s['title'],
            'lead' => $s['lead'],
        ])

        <div class="grid gap-8 border-t border-slate-200 pt-10 sm:grid-cols-3">
            @foreach($s['stats'] as $stat)
                <div>
                    <p class="text-eyebrow text-sm">{{ $stat['index'] }}</p>
                    <p class="mt-2 font-display text-4xl font-bold text-heading">{{ $stat['value'] }}</p>
                    <p class="text-card-body mt-2">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>

        @if($featuredPortfolios->isNotEmpty())
            <div class="mt-14">
                <div class="mb-8 flex items-end justify-between gap-4">
                    <h3 class="text-section-title text-xl">Portofolio Terpilih</h3>
                    <a href="{{ route('portfolio') }}" class="text-link shrink-0">Semua proyek →</a>
                </div>
                <div class="grid gap-6 md:grid-cols-2">
                    @foreach($featuredPortfolios->take(2) as $item)
                        <article class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                            @if($item->cover_image_url)
                                <img src="{{ $item->cover_image_url }}" alt="{{ $item->title }}" class="h-48 w-full object-cover object-top">
                            @else
                                <div class="h-48 bg-gradient-to-br from-brand-primary/10 to-brand-secondary/10"></div>
                            @endif
                            <div class="p-6">
                                <p class="text-meta text-brand-primary">{{ $item->client_name }}</p>
                                <h4 class="text-card-title mt-2">{{ $item->title }}</h4>
                                <p class="text-card-body mt-2 line-clamp-2">{{ $item->short_description }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
