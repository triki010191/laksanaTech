@extends('layouts.app')

@section('content')
    <x-page-header compact title="Pertanyaan"
        subtitle="Jawaban seputar pemesanan website, template, pembayaran, dan layanan {{ config('laksana.brand.legal_name') }}." />

    <section class="mx-auto max-w-3xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="space-y-5">
            @forelse($faqs as $index => $faq)
                <details class="faq-item group">
                    <summary class="faq-item__summary">
                        <span class="faq-item__number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="faq-item__question">{{ $faq->question }}</span>
                        <span class="faq-item__chevron" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </summary>
                    <div class="faq-item__answer prose-laksana text-card-body">
                        {!! $faq->answer !!}
                    </div>
                </details>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-200 bg-white px-6 py-12 text-center">
                    <p class="text-card-title text-slate-700">Belum ada pertanyaan</p>
                    <p class="text-card-body mt-2 text-muted">Silakan hubungi kami jika ada yang ingin ditanyakan.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
