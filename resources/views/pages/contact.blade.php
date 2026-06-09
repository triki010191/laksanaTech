@extends('layouts.app')

@section('content')
    <x-page-header compact title="Hubungi Kami"
        subtitle="Konsultasi gratis untuk kebutuhan website, aplikasi, atau pengadaan IT." />

    <section class="mx-auto max-w-3xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-5">
            <div class="rounded-3xl border border-slate-100 bg-slate-50 p-6 lg:col-span-2">
                <h2 class="text-eyebrow text-heading">Informasi</h2>
                <ul class="mt-4 space-y-3 text-card-body">
                    <li><strong>Alamat:</strong><br>{{ config('laksana.brand.address') }}</li>
                    <li><strong>Email:</strong><br>
                        <a href="mailto:{{ config('laksana.brand.email') }}" class="text-link">{{ config('laksana.brand.email') }}</a>
                    </li>
                    <li><strong>WhatsApp:</strong><br>
                        <a href="https://wa.me/{{ config('laksana.brand.whatsapp') }}" class="text-link">{{ config('laksana.brand.phone') }}</a>
                    </li>
                </ul>
            </div>
            <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-sm lg:col-span-3">
                <h2 class="text-eyebrow text-heading">Formulir Kontak</h2>
                <div class="mt-4">
                    <livewire:contact-form />
                </div>
            </div>
        </div>
    </section>
@endsection
