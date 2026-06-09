@extends('layouts.customer')

@section('content')
<h1 class="font-display text-2xl font-bold">Invoice Saya</h1>
<div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-slate-600">
            <tr><th class="px-4 py-3">No. Invoice</th><th class="px-4 py-3">Jumlah</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Aksi</th></tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">{{ $invoice->invoice_number }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 capitalize">{{ $invoice->status }}</td>
                    <td class="px-4 py-3">
                        @if($invoice->status !== 'paid')
                            <a href="{{ route('customer.invoices.pay', $invoice) }}" class="font-medium text-brand-primary hover:underline">Bayar</a>
                        @else
                            <span class="text-green-600">Lunas</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-slate-500">Belum ada invoice.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $invoices->links() }}</div>
@endsection
