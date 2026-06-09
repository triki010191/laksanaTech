<div class="mx-auto max-w-5xl px-4 sm:px-6">
    <div class="grid gap-8 lg:grid-cols-[1fr_280px]">
        <div class="checkout-payment-card rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-4">
                <h1 class="text-card-title">Ringkasan Pesanan</h1>
                <span class="text-caption">Invoice {{ $invoice->invoice_number }}</span>
            </div>

            <p class="text-caption mt-4 font-semibold uppercase text-red-600">
                Bayar sebelum {{ $invoice->due_date?->translatedFormat('d F Y') ?? now()->addDay()->translatedFormat('d F Y') }}
            </p>

            <p class="mt-2 font-display text-4xl font-bold text-brand-primary">
                IDR {{ number_format($invoice->amount, 0, ',', '.') }}
            </p>

            <h2 class="text-card-title mt-8">Metode Pembayaran</h2>
            <div class="mt-4 space-y-2">
                @foreach($methods as $method)
                    <label @class([
                        'checkout-payment-method flex cursor-pointer items-center justify-between rounded-xl border px-4 py-3',
                        'border-brand-primary bg-brand-primary/5' => $selectedMethod === $method['id'],
                        'border-slate-200' => $selectedMethod !== $method['id'],
                    ])>
                        <span class="flex items-center gap-3">
                            <input type="radio" wire:model="selectedMethod" value="{{ $method['id'] }}" class="text-brand-primary">
                            <span>
                                <span class="text-card-title block text-sm">{{ $method['label'] }}</span>
                                <span class="text-caption">{{ $method['note'] }}</span>
                            </span>
                        </span>
                    </label>
                @endforeach
            </div>

            <p class="text-caption mt-6 text-center uppercase tracking-wide text-muted">
                Powered by {{ strtoupper(config('laksana.payment.default_gateway', 'xendit')) }}
            </p>

            <button type="button" wire:click="pay" wire:loading.attr="disabled"
                    class="checkout-btn-primary mt-6 w-full justify-center">
                <span wire:loading.remove wire:target="pay">Lanjutkan Pembayaran</span>
                <span wire:loading wire:target="pay">Memproses...</span>
            </button>
        </div>

        <aside class="space-y-4">
            <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <h2 class="text-card-title text-sm uppercase text-muted">Ringkasan</h2>
                <dl class="mt-3 space-y-2 text-sm">
                    @foreach($order->items as $item)
                        <div class="flex justify-between gap-2">
                            <dt class="text-muted line-clamp-1">{{ $item->name }}</dt>
                            <dd class="shrink-0">Rp {{ number_format($item->total_price, 0, ',', '.') }}</dd>
                        </div>
                    @endforeach
                    <div class="flex justify-between gap-2 border-t border-slate-100 pt-2 font-bold text-heading">
                        <dt>TOTAL</dt>
                        <dd>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white p-5 text-sm shadow-sm">
                <h2 class="text-card-title text-sm">Butuh Bantuan?</h2>
                <p class="text-card-body mt-2">Chat tim kami via WhatsApp pada jam kerja.</p>
                <a href="https://wa.me/{{ config('laksana.brand.whatsapp') }}" target="_blank" rel="noopener"
                   class="text-link mt-3 inline-block">Chat WhatsApp →</a>
            </div>
        </aside>
    </div>
</div>
