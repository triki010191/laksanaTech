<div class="checkout-page mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="checkout-layout grid gap-8 lg:grid-cols-[280px_1fr]">
        @include('livewire.checkout.partials.sidebar')

        <div class="checkout-main min-w-0">
            @include('livewire.checkout.partials.step-nav')

            <div class="checkout-panel mt-6 rounded-2xl border border-slate-100 bg-white p-6 shadow-sm sm:p-8">
                @if($currentStep)
                    <p class="text-eyebrow">{{ $currentStep['num'] }} · {{ strtoupper($currentStep['title']) }}</p>
                @endif

                @if(($currentStep['key'] ?? '') === 'domain')
                    @include('livewire.checkout.partials.step-domain')
                @elseif(($currentStep['key'] ?? '') === 'template')
                    @include('livewire.checkout.partials.step-template')
                @elseif(($currentStep['key'] ?? '') === 'data')
                    @include('livewire.checkout.partials.step-data')
                @elseif(($currentStep['key'] ?? '') === 'package')
                    @include('livewire.checkout.partials.step-package')
                @endif

                <div class="checkout-panel__nav mt-8 flex justify-between gap-3">
                    @if($step > 1)
                        <button type="button" wire:click="prevStep" class="checkout-btn-outline">← Sebelumnya</button>
                    @else
                        <span></span>
                    @endif

                    @if($step < count($steps))
                        <button type="button" wire:click="nextStep" class="checkout-btn-primary">
                            Selanjutnya
                            <span class="checkout-btn-primary__icon">→</span>
                        </button>
                    @else
                        <button type="button" wire:click="openConfirmModal" class="checkout-btn-primary">
                            Konfirmasi & Bayar
                            <span class="checkout-btn-primary__icon">→</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('livewire.checkout.partials.confirm-modal')
</div>
