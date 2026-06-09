<aside class="checkout-sidebar">
    <div class="checkout-summary rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
        <h2 class="text-card-title text-sm uppercase tracking-wide text-muted">Ringkasan Pesanan</h2>
        <dl class="mt-4 space-y-3 text-sm">
            @if($this->selectedExtension && $domainName)
                <div class="flex justify-between gap-3">
                    <dt class="text-muted">Domain</dt>
                    <dd class="text-right font-medium text-heading">{{ $domainName }}{{ $this->selectedExtension->extension }}</dd>
                </div>
                <div class="flex justify-between gap-3 text-caption">
                    <dt></dt>
                    <dd>Rp {{ number_format($pricing['domain_price'], 0, ',', '.') }}</dd>
                </div>
            @endif
            @if($this->selectedTemplate)
                <div class="flex justify-between gap-3">
                    <dt class="text-muted">Template</dt>
                    <dd class="max-w-[10rem] truncate text-right font-medium text-heading">{{ $this->selectedTemplate->name }}</dd>
                </div>
                <div class="flex justify-between gap-3 text-caption">
                    <dt></dt>
                    <dd>Rp {{ number_format($pricing['template_price'], 0, ',', '.') }}</dd>
                </div>
            @endif
            @if($step >= 4 && $pricing['hosting_price'] > 0)
                <div class="flex justify-between gap-3">
                    <dt class="text-muted">Hosting</dt>
                    <dd class="text-right font-medium text-heading">{{ $subscriptionYears }} Tahun</dd>
                </div>
                <div class="flex justify-between gap-3 text-caption">
                    <dt></dt>
                    <dd>Rp {{ number_format($pricing['hosting_price'], 0, ',', '.') }}</dd>
                </div>
            @endif
            @if($step >= 4 && $pricing['admin_fee'] > 0)
                <div class="flex justify-between gap-3">
                    <dt class="text-muted">Biaya Admin</dt>
                    <dd>Rp {{ number_format($pricing['admin_fee'], 0, ',', '.') }}</dd>
                </div>
            @endif
            @if($step >= 4 && $pricing['discount'] > 0)
                <div class="flex justify-between gap-3 text-brand-primary">
                    <dt>Promo</dt>
                    <dd>- Rp {{ number_format($pricing['discount'], 0, ',', '.') }}</dd>
                </div>
            @endif
            <div class="flex justify-between gap-3 border-t border-slate-100 pt-3 text-base font-bold text-heading">
                <dt>Total</dt>
                <dd>Rp {{ number_format($pricing['total'], 0, ',', '.') }}</dd>
            </div>
        </dl>
    </div>

    <div class="checkout-steps mt-6 rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
        <h2 class="text-card-title text-sm uppercase tracking-wide text-muted">Langkah Pemesanan</h2>
        <ol class="mt-4 space-y-4">
            @foreach($steps as $index => $s)
                @php $num = $index + 1; @endphp
                <li>
                    <button type="button" wire:click="goToStep({{ $num }})" @class([
                        'flex w-full items-start gap-3 text-left',
                        'opacity-50 pointer-events-none' => $num > $step,
                    ])>
                        <span @class([
                            'checkout-step-badge',
                            'checkout-step-badge--done' => $num < $step,
                            'checkout-step-badge--active' => $num === $step,
                        ])>{{ $num < $step ? '✓' : $s['num'] }}</span>
                        <span>
                            <span class="text-card-title block text-sm">{{ $s['title'] }}</span>
                            <span class="text-caption mt-0.5 block">{{ $s['hint'] }}</span>
                        </span>
                    </button>
                </li>
            @endforeach
        </ol>
    </div>
</aside>
