@extends('layouts.customer')

@section('content')
<h1 class="font-display text-2xl font-bold">Pesanan Saya</h1>
<div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-slate-600">
            <tr><th class="px-4 py-3">No. Pesanan</th><th class="px-4 py-3">Domain</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Estimasi Siap</th><th class="px-4 py-3">Total</th></tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3 font-medium">{{ $order->order_number }}</td>
                    <td class="px-4 py-3">{{ $order->metadata['full_domain'] ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php
                            $fulfillment = $order->metadata['fulfillment_status'] ?? null;
                        @endphp
                        <span class="capitalize">{{ str_replace('_', ' ', $order->status->value ?? $order->status) }}</span>
                        @if($fulfillment === 'waiting_setup')
                            <span class="mt-0.5 block text-xs text-amber-600">Menunggu setup 2×24 jam</span>
                        @elseif($fulfillment === 'ready')
                            <span class="mt-0.5 block text-xs text-green-600">Website siap</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs">{{ $order->estimated_ready_at?->format('d M Y H:i') ?? '-' }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-slate-500">Belum ada pesanan. <a href="{{ route('designs') }}" class="text-brand-primary underline">Pesan website</a></td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection
