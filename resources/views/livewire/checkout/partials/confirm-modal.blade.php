@if($showConfirmModal)
    <div class="checkout-modal-backdrop" wire:click="closeConfirmModal">
        <div class="checkout-modal" wire:click.stop>
            <p class="text-eyebrow">Konfirmasi Pesanan</p>
            <h3 class="text-section-title mt-2 text-xl">Periksa pesananmu sebelum bayar</h3>

            <dl class="mt-6 space-y-3 text-sm">
                @if($this->selectedExtension && $domainName)
                    <div class="flex justify-between gap-3">
                        <dt class="text-muted uppercase text-xs">Domain</dt>
                        <dd class="font-medium text-heading">{{ $domainName }}{{ $this->selectedExtension->extension }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt></dt>
                        <dd>Rp {{ number_format($pricing['domain_price'], 0, ',', '.') }}</dd>
                    </div>
                @endif
                @if($this->selectedTemplate)
                    <div class="flex justify-between gap-3">
                        <dt class="text-muted uppercase text-xs">Template</dt>
                        <dd class="max-w-[12rem] truncate font-medium text-heading">{{ $this->selectedTemplate->name }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt></dt>
                        <dd>Rp {{ number_format($pricing['template_price'], 0, ',', '.') }}</dd>
                    </div>
                @endif
                <div class="flex justify-between gap-3">
                    <dt class="text-muted uppercase text-xs">Hosting</dt>
                    <dd>{{ $subscriptionYears }} Tahun Berlangganan</dd>
                </div>
                <div class="flex justify-between gap-3">
                    <dt></dt>
                    <dd>Rp {{ number_format($pricing['hosting_price'], 0, ',', '.') }}</dd>
                </div>
                @if($pricing['admin_fee'] > 0)
                    <div class="flex justify-between gap-3">
                        <dt class="text-muted uppercase text-xs">Biaya Admin</dt>
                        <dd>Rp {{ number_format($pricing['admin_fee'], 0, ',', '.') }}</dd>
                    </div>
                @endif
                @if($pricing['addon_price'] > 0)
                    <div class="flex justify-between gap-3">
                        <dt class="text-muted uppercase text-xs">Editing</dt>
                        <dd>Rp {{ number_format($pricing['addon_price'], 0, ',', '.') }}</dd>
                    </div>
                @endif
                @if($pricing['discount'] > 0)
                    <div class="flex justify-between gap-3 text-brand-primary">
                        <dt class="uppercase text-xs">Promo {{ $appliedPromoCode }}</dt>
                        <dd>- Rp {{ number_format($pricing['discount'], 0, ',', '.') }}</dd>
                    </div>
                @endif
                <div class="flex justify-between gap-3 border-t border-slate-100 pt-3 text-lg font-bold text-heading">
                    <dt>Total Bayar</dt>
                    <dd>Rp {{ number_format($pricing['total'], 0, ',', '.') }}</dd>
                </div>
            </dl>

            <p class="text-caption mt-4 rounded-lg bg-slate-50 p-3">
                Pembayaran diproses {{ strtoupper(config('laksana.payment.default_gateway', 'xendit')) }} — transfer bank, e-wallet, atau kartu kredit.
            </p>

            <div class="mt-6 flex gap-3">
                <button type="button" wire:click="closeConfirmModal" class="checkout-btn-outline flex-1">Kembali</button>
                <button type="button" wire:click="placeOrder" wire:loading.attr="disabled" class="checkout-btn-primary flex-1">
                    <span wire:loading.remove wire:target="placeOrder">Bayar Sekarang</span>
                    <span wire:loading wire:target="placeOrder">Memproses...</span>
                    <span class="checkout-btn-primary__icon">→</span>
                </button>
            </div>
        </div>
    </div>
@endif
