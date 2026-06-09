@extends('layouts.app')

@section('content')
    <x-page-header compact title="Paket Domain & Hosting"
        subtitle="Harga domain, paket website tahunan, hosting, dan SSL — mulai pesan online atau konsultasi tim kami." />

    <section class="mx-auto max-w-7xl px-4 pb-8 sm:px-6 lg:px-8">
        @include('partials.home.section-header', [
            'eyebrow' => config('laksana.home.section_03.eyebrow'),
            'title' => config('laksana.home.section_03.title'),
            'lead' => config('laksana.home.section_03.lead'),
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
    </section>

    <section class="home-section home-section--muted py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10">
                <span class="text-eyebrow">Paket Website</span>
                <h2 class="text-section-title mt-3">Pilih paket hosting sesuai skala bisnis</h2>
            </div>
            <div class="grid gap-6 lg:grid-cols-3">
                @foreach($packages as $package)
                    <article class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm {{ $package->is_featured ? 'ring-2 ring-brand-accent' : '' }}">
                        @if($package->is_featured)
                            <span class="text-eyebrow text-xs">Paling populer</span>
                        @endif
                        <h3 class="text-card-title mt-2">{{ $package->name }}</h3>
                        <p class="text-card-body mt-2">{{ $package->description }}</p>
                        <p class="mt-4 text-2xl font-bold text-heading">
                            Rp{{ number_format($package->price, 0, ',', '.') }}
                            <span class="text-base font-normal text-muted">/thn</span>
                        </p>
                        <ul class="mt-4 space-y-2">
                            @foreach($package->features ?? [] as $feature)
                                <li class="flex items-center gap-2 text-card-body">
                                    <span class="text-brand-accent">✓</span>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('checkout', ['flow' => 'domain_first']) }}"
                           class="domain-offer-card__btn mt-6">
                            Pesan Paket
                            <span class="domain-offer-card__btn-icon">→</span>
                        </a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    @if($extensions->isNotEmpty())
        <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
            <h2 class="text-section-title mb-6">Semua Ekstensi Domain</h2>
            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-100 bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-heading">Ekstensi</th>
                            <th class="px-4 py-3 font-semibold text-heading">Keterangan</th>
                            <th class="px-4 py-3 font-semibold text-heading">Harga / thn</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($extensions as $ext)
                            <tr class="border-b border-slate-50 last:border-0">
                                <td class="px-4 py-3 font-medium text-heading">{{ $ext->extension }}</td>
                                <td class="px-4 py-3 text-card-body">{{ $ext->label }}</td>
                                <td class="px-4 py-3 font-semibold text-heading">Rp{{ number_format($ext->price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    @endif
@endsection
