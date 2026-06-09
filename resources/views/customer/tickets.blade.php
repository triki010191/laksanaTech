@extends('layouts.customer')

@section('content')
<div class="flex items-center justify-between">
    <h1 class="font-display text-2xl font-bold">Tiket Support</h1>
    <a href="{{ route('customer.tickets.create') }}" class="rounded-2xl bg-brand-primary px-4 py-2 text-sm font-semibold text-white">+ Tiket Baru</a>
</div>
<div class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-left text-slate-600">
            <tr><th class="px-4 py-3">No. Tiket</th><th class="px-4 py-3">Subjek</th><th class="px-4 py-3">Status</th></tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr class="border-t border-slate-100">
                    <td class="px-4 py-3">{{ $ticket->ticket_number }}</td>
                    <td class="px-4 py-3">{{ $ticket->subject }}</td>
                    <td class="px-4 py-3 capitalize">{{ $ticket->status }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="px-4 py-8 text-center text-slate-500">Belum ada tiket.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $tickets->links() }}</div>
@endsection
