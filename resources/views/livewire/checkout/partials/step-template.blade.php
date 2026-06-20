<div>
    <h3 class="text-section-title text-2xl">Pilih desain yang <em class="text-emphasis">mewakili bisnismu</em></h3>
    <p class="text-section-lead mt-2">Template bisa diganti gratis setelah aktivasi. Semua desain responsif & SEO-friendly.</p>

    @if($this->selectedTemplate)
        <div class="checkout-selected-template mt-6">
            <div class="flex gap-4">
                <div class="h-20 w-28 shrink-0 overflow-hidden rounded-lg">
                    <x-template-preview :template="$this->selectedTemplate" :linkable="false"
                                        img-class="h-20 w-28 object-cover object-top" />
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-card-title">{{ $this->selectedTemplate->name }}</p>
                    <p class="text-caption mt-1">{{ number_format($this->selectedTemplate->downloads_count, 0, ',', '.') }} orang memilih ini</p>
                    <p class="text-price mt-2">Rp {{ number_format($this->selectedTemplate->sale_price ?? $this->selectedTemplate->price, 0, ',', '.') }}</p>
                </div>
                <button type="button" wire:click="$set('templateId', null)" class="text-caption text-brand-primary">Ganti</button>
            </div>
        </div>
    @endif

    <div class="mt-6">
        <input wire:model.live.debounce.300ms="templateSearch" type="search" placeholder="Cari template..."
               class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm">
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-2">
        @foreach($this->filteredTemplates as $tpl)
            <label @class([
                'checkout-template-card cursor-pointer',
                'checkout-template-card--selected' => $templateId === $tpl->id,
            ])>
                <input type="radio" wire:model.live="templateId" value="{{ $tpl->id }}" class="sr-only">
                <x-template-preview :template="$tpl" :linkable="false"
                                    wrapper-class="checkout-template-card__img"
                                    img-class="checkout-template-card__img h-full w-full object-cover object-top" />
                <div class="p-3">
                    <p class="text-card-title text-sm line-clamp-2">{{ $tpl->name }}</p>
                    <p class="text-price mt-1 text-xs">Rp {{ number_format($tpl->sale_price ?? $tpl->price, 0, ',', '.') }}</p>
                    @if($tpl->live_demo_link)
                        <a href="{{ $tpl->live_demo_link }}" target="_blank" rel="noopener noreferrer"
                           class="text-caption mt-1 inline-block text-brand-primary hover:underline"
                           onclick="event.stopPropagation()">Lihat Demo ↗</a>
                    @endif
                </div>
            </label>
        @endforeach
    </div>
    @error('templateId') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
</div>
