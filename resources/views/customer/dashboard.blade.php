@extends('layouts.customer')

@section('content')
<div>
    <h1 class="font-display text-2xl font-bold">Dashboard</h1>
    <p class="mt-1 text-slate-600">Selamat datang, {{ auth()->user()->name }}</p>
    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach(['orders' => 'Pesanan', 'invoices' => 'Invoice', 'projects' => 'Proyek', 'tickets' => 'Tiket Aktif'] as $key => $label)
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">{{ $label }}</p>
                <p class="mt-2 text-3xl font-bold text-brand-primary">{{ $stats[$key] }}</p>
            </div>
        @endforeach
    </div>
    <div class="mt-10 rounded-2xl border border-slate-200 bg-white p-6">
        <h2 class="font-semibold">Pesanan Terbaru</h2>
        @forelse($recentOrders as $order)
            <div class="mt-4 flex justify-between border-t border-slate-100 pt-4 text-sm">
                <span>{{ $order->order_number }}</span>
                <span class="capitalize">{{ $order->status->value ?? $order->status }}</span>
            </div>
        @empty
            <p class="mt-4 text-sm text-slate-500">Belum ada pesanan.</p>
        @endforelse
    </div>
</div>
@endsection
