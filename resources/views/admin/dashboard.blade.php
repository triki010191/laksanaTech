@extends('layouts.admin')

@section('content')
<h1 class="font-display text-2xl font-bold">Dashboard Admin</h1>
<p class="mt-2 text-slate-600">Kelola marketplace website, pesanan, dan konten LaksanaTech.id</p>

<h2 class="mt-10 text-sm font-semibold uppercase tracking-widest text-slate-400">Marketplace</h2>
<div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <a href="{{ route('admin.templates.index') }}" class="rounded-2xl border bg-white p-5 shadow-sm hover:border-brand-primary">
        <p class="font-semibold">Template</p>
        <p class="mt-1 text-sm text-slate-500">Produk & harga website</p>
    </a>
    <a href="{{ route('admin.packages.index') }}" class="rounded-2xl border bg-white p-5 shadow-sm hover:border-brand-primary">
        <p class="font-semibold">Paket Website</p>
        <p class="mt-1 text-sm text-slate-500">Hosting & paket tahunan</p>
    </a>
    <a href="{{ route('admin.domain-extensions.index') }}" class="rounded-2xl border bg-white p-5 shadow-sm hover:border-brand-primary">
        <p class="font-semibold">Ekstensi Domain</p>
        <p class="mt-1 text-sm text-slate-500">Harga .com, .id, dll</p>
    </a>
    <a href="{{ route('admin.orders.index') }}" class="rounded-2xl border bg-white p-5 shadow-sm hover:border-brand-primary">
        <p class="font-semibold">Pesanan</p>
        <p class="mt-1 text-sm text-slate-500">Checkout & fulfillment 2×24 jam</p>
    </a>
    <a href="{{ route('admin.settings.index') }}" class="rounded-2xl border bg-white p-5 shadow-sm hover:border-brand-primary">
        <p class="font-semibold">Pengaturan CMS</p>
        <p class="mt-1 text-sm text-slate-500">Logo, footer, WhatsApp, promo</p>
    </a>
    @role('super_admin')
    <a href="{{ route('admin.payment-settings.index') }}" class="rounded-2xl border bg-white p-5 shadow-sm hover:border-brand-primary">
        <p class="font-semibold">Payment Gateway</p>
        <p class="mt-1 text-sm text-slate-500">Midtrans, Xendit, Tripay & API key</p>
    </a>
    @endrole
</div>

<h2 class="mt-10 text-sm font-semibold uppercase tracking-widest text-slate-400">Konten & Marketing</h2>
<div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <a href="{{ route('admin.hero-slides.index') }}" class="rounded-2xl border bg-white p-5 shadow-sm hover:border-brand-primary">
        <p class="font-semibold">Hero Slider</p>
        <p class="mt-1 text-sm text-slate-500">Gambar & teks beranda</p>
    </a>
    <a href="{{ route('admin.portfolios.index') }}" class="rounded-2xl border bg-white p-5 shadow-sm hover:border-brand-primary">
        <p class="font-semibold">Portofolio</p>
        <p class="mt-1 text-sm text-slate-500">Proyek & screenshot</p>
    </a>
    <a href="{{ route('admin.blog.index') }}" class="rounded-2xl border bg-white p-5 shadow-sm hover:border-brand-primary">
        <p class="font-semibold">Blog</p>
        <p class="mt-1 text-sm text-slate-500">Artikel & berita</p>
    </a>
    <a href="{{ route('admin.faqs.index') }}" class="rounded-2xl border bg-white p-5 shadow-sm hover:border-brand-primary">
        <p class="font-semibold">FAQ</p>
        <p class="mt-1 text-sm text-slate-500">Pertanyaan umum</p>
    </a>
    <a href="{{ route('admin.leads.index') }}" class="rounded-2xl border bg-white p-5 shadow-sm hover:border-brand-primary">
        <p class="font-semibold">Leads</p>
        <p class="mt-1 text-sm text-slate-500">Form kontak</p>
    </a>
</div>
@endsection
