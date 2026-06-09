<div class="checkout-step-nav hidden items-center justify-end gap-3 sm:flex">
    @if($step > 1)
        <button type="button" wire:click="prevStep" class="checkout-btn-outline text-sm">← Sebelumnya</button>
    @endif
    @if($step < count($steps))
        <button type="button" wire:click="nextStep" class="checkout-btn-primary text-sm">
            Selanjutnya <span class="checkout-btn-primary__icon">→</span>
        </button>
    @else
        <button type="button" wire:click="openConfirmModal" class="checkout-btn-primary text-sm">
            Konfirmasi & Bayar <span class="checkout-btn-primary__icon">→</span>
        </button>
    @endif
</div>
