@php $s = config('laksana.home.section_03'); @endphp

<section class="home-section mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
    @include('partials.home.section-header', [
        'eyebrow' => $s['eyebrow'],
        'title' => $s['title'],
        'lead' => $s['lead'],
    ])

    <div class="grid gap-6 lg:grid-cols-3">
        @foreach($domainOffers as $row)
            <article class="domain-offer-card">
                <div class="domain-offer-card__top">
                    <span class="text-caption font-semibold uppercase text-muted">Domain</span>
                    <span class="domain-offer-card__tag">{{ $row['extension']->extension }}</span>
                </div>
                <div class="mt-4">
                    <p class="text-2xl font-bold text-heading">
                        Rp{{ number_format($row['price'], 0, ',', '.') }}
                        <span class="text-base font-normal text-muted">/thn</span>
                    </p>
                    <p class="text-caption mt-1 line-through">Rp{{ number_format($row['compare_price'], 0, ',', '.') }}</p>
                    @if($row['promo_code'])
                        <span class="domain-offer-card__promo mt-2">KODE {{ $row['promo_code'] }}</span>
                    @endif
                </div>
                <h3 class="text-card-title mt-6">{{ $row['offer']['title'] }}</h3>
                <p class="text-card-body mt-2">{{ $row['offer']['description'] }}</p>
                <ul class="mt-4 space-y-2">
                    @foreach($row['offer']['features'] as $feature)
                        <li class="flex items-center gap-2 text-card-body">
                            <span class="text-brand-accent">✓</span>
                            {{ $feature }}
                        </li>
                    @endforeach
                    <li class="flex items-center gap-2 text-card-body">
                        <span class="text-brand-accent">✓</span>
                        Paket {{ $row['package']->name }}
                    </li>
                </ul>
                <a href="{{ route('checkout', ['flow' => 'domain_first']) }}"
                   class="domain-offer-card__btn mt-6">
                    Cari Domain
                    <span class="domain-offer-card__btn-icon">→</span>
                </a>
            </article>
        @endforeach
    </div>

    <div class="mt-10 text-center">
        <a href="{{ route($s['cta_route']) }}" class="home-cta-outline">
            {{ $s['cta'] }}
            <span aria-hidden="true">→</span>
        </a>
    </div>
</section>
