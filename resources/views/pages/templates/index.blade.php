@extends('layouts.app')

@section('content')
<section class="bg-gradient-to-b from-sky-50/80 to-white py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h1 class="text-page-title">Template Website</h1>
        <p class="text-page-lead mt-2 max-w-2xl">Pilih desain siap pakai, lalu lanjut domain & checkout. Website siap 2×24 jam.</p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('checkout', ['flow' => 'template_first']) }}" class="text-btn rounded-2xl bg-brand-primary px-5 py-2.5 text-white">Mulai dari Template</a>
            <a href="{{ route('checkout', ['flow' => 'domain_first']) }}" class="text-btn rounded-2xl border-2 border-brand-primary px-5 py-2.5 text-brand-primary">Mulai dari Domain</a>
        </div>
    </div>
</section>

@if($featured->isNotEmpty())
<section class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
    <h2 class="text-section-title">Template Unggulan</h2>
    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($featured as $tpl)
            @include('pages.templates._card', ['template' => $tpl])
        @endforeach
    </div>
</section>
@endif

<section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
    <h2 class="text-section-title">Semua Template</h2>
    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($templates as $tpl)
            @include('pages.templates._card', ['template' => $tpl])
        @empty
            <p class="col-span-full text-center text-muted">Belum ada template. Admin dapat menambahkan di panel.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $templates->links() }}</div>
</section>
@endsection
