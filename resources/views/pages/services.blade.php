@extends('layouts.app')

@section('content')
    <x-page-header compact title="Layanan Kami"
        subtitle="Keamanan, jaringan, pengadaan IT, dan pesanan custom untuk bisnis, sekolah, dan instansi." />

    <section class="mx-auto max-w-7xl px-4 pb-16 sm:px-6 lg:px-8">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach(config('laksana.layanan_menu.bidang.items', []) as $index => $item)
                @include('pages.services._line-card', [
                    'item' => $item,
                    'service' => $services->firstWhere('slug', $item['slug']),
                    'active' => $index === 1,
                ])
            @endforeach
        </div>
    </section>
@endsection
