<div>
    <h3 class="text-section-title text-2xl">Cari domain untuk <em class="text-emphasis">bisnismu</em></h3>
    <p class="text-section-lead mt-2">Masukkan nama domain — kami tampilkan harga semua ekstensi sekaligus.</p>

    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
        <input wire:model="domainName" type="text" placeholder="namabisnis"
               class="flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20">
        <button type="button" wire:click="searchDomains" wire:loading.attr="disabled"
                class="checkout-btn-primary shrink-0">
            <span wire:loading.remove wire:target="searchDomains">Cari Domain</span>
            <span wire:loading wire:target="searchDomains">Mencari...</span>
        </button>
    </div>
    @error('domainName') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

    @if($domainSearched)
        <div class="mt-6 space-y-2">
            @forelse($domainResults as $row)
                <label @class([
                    'checkout-domain-row',
                    'checkout-domain-row--selected' => $domainExtensionId === $row['extension_id'],
                    'checkout-domain-row--unavailable' => ! $row['available'],
                ])>
                    <input type="radio" wire:model.live="domainExtensionId" value="{{ $row['extension_id'] }}"
                           class="sr-only" @disabled(! $row['available'])>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-heading">{{ $row['full_domain'] }}</p>
                        @if(! $row['available'])
                            <p class="text-caption text-red-600">{{ $row['message'] }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-heading">Rp {{ number_format($row['price'], 0, ',', '.') }}</p>
                        @if($row['compare_price'])
                            <p class="text-caption line-through">Rp {{ number_format($row['compare_price'], 0, ',', '.') }}</p>
                        @endif
                    </div>
                </label>
            @empty
                <p class="text-muted">Format domain tidak valid.</p>
            @endforelse
        </div>
        @error('domainExtensionId') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    @endif

    <p class="text-caption mt-4">
        Harga domain diambil dari katalog reseller. Integrasi API registrar (ResellerClub / Rumahweb) dapat diaktifkan admin.
    </p>
</div>
