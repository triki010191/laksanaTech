@extends('layouts.app')

@section('content')
    @include('partials.hero-slider')

    @include('partials.home.section-designs')
    @include('partials.home.section-service-lines')
    @include('partials.home.section-domain-packages')
    @include('partials.home.section-social-proof')

    <section class="mx-auto max-w-5xl px-4 py-20 text-center sm:px-6">
        <h2 class="text-section-title">Siap Wujudkan Proyek Digital Anda?</h2>
        <p class="text-section-lead mx-auto mt-4 max-w-xl">Konsultasi gratis via WhatsApp atau formulir kontak.</p>
        <a href="https://wa.me/{{ config('laksana.brand.whatsapp') }}" target="_blank" rel="noopener"
           class="text-btn mt-8 inline-flex rounded-2xl bg-brand-accent px-8 py-3 text-white shadow-lg transition hover:opacity-90">
            Chat WhatsApp
        </a>
    </section>

    @include('partials.schema-organization')
@endsection
