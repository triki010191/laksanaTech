@php
    $s = config('laksana.home.section_02');
@endphp

<section class="home-section home-section--muted py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        @include('partials.home.section-header', [
            'eyebrow' => $s['eyebrow'],
            'title' => $s['title'],
            'lead' => $s['lead'],
        ])

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($services as $index => $service)
                @include('pages.services._line-card', [
                    'service' => $service,
                    'active' => $index === 1,
                ])
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route($s['cta_route']) }}" class="home-cta-outline">
                {{ $s['cta'] }}
                <span aria-hidden="true">→</span>
            </a>
        </div>
    </div>
</section>
