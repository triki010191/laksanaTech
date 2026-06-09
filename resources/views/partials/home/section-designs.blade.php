@php $s = config('laksana.home.section_01'); @endphp

<section class="home-section mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    @include('partials.home.section-header', [
        'eyebrow' => $s['eyebrow'],
        'title' => $s['title'],
        'lead' => $s['lead'],
    ])

    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($featuredTemplates as $template)
            @include('pages.designs._card', ['template' => $template])
        @endforeach
    </div>

    <div class="mt-10 text-center">
        <a href="{{ route($s['cta_route']) }}" class="home-cta-outline">
            {{ $s['cta'] }}
            <span aria-hidden="true">→</span>
        </a>
    </div>
</section>
