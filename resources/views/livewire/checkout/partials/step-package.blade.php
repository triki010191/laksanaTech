<div>
    <h3 class="text-section-title text-2xl">Pilih durasi yang <em class="text-emphasis">paling hemat</em> untuk bisnismu</h3>
    <p class="text-section-lead mt-2">Semua paket sudah termasuk domain, hosting unlimited, SSL, dan template.</p>

    <div class="mt-6 grid gap-4 lg:grid-cols-3">
        @foreach($subscriptionPlans as $plan)
            <label @class([
                'checkout-plan-card',
                'checkout-plan-card--selected' => $subscriptionYears === $plan['years'],
            ])>
                <input type="radio" wire:model.live="subscriptionYears" value="{{ $plan['years'] }}" class="sr-only">
                @if($plan['recommended'] ?? false)
                    <span class="checkout-plan-card__badge">Rekomendasi</span>
                @endif
                <p class="text-card-title">{{ $plan['label'] }}</p>
                <p class="mt-2 text-2xl font-bold text-heading">Rp {{ number_format($plan['hosting_price'], 0, ',', '.') }}</p>
                <p class="text-caption">/ paket hosting {{ $plan['label'] }}</p>
                <p class="text-caption mt-1">{{ $plan['tagline'] }}</p>
                <ul class="mt-4 space-y-1.5">
                    @foreach($plan['features'] as $feature)
                        <li class="flex items-center gap-2 text-card-body text-sm">
                            <span class="text-brand-accent">✓</span> {{ $feature }}
                        </li>
                    @endforeach
                </ul>
            </label>
        @endforeach
    </div>

    <label class="checkout-addon mt-6 flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-4">
        <input type="checkbox" wire:model.live="withEditingAddon" class="mt-1 rounded border-slate-300 text-brand-primary">
        <span>
            <span class="text-card-title block">Jasa Editing Website</span>
            <span class="text-card-body mt-1 block">Tim kami bantu isi konten, gambar, dan layout awal.</span>
            <span class="text-price mt-2 inline-block">+ Rp {{ number_format(config('laksana.checkout.editing_addon_price', 500000), 0, ',', '.') }}</span>
        </span>
    </label>

    <div class="mt-6 max-w-md">
        <label class="text-caption mb-2 block font-semibold uppercase text-muted">Kode Promo</label>
        <div class="flex gap-2">
            <input wire:model="promoCode" type="text" placeholder="WEBSITEJUARA"
                   class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm uppercase">
            <button type="button" wire:click="applyPromo" class="checkout-btn-primary shrink-0 px-4 py-2.5 text-sm">
                Gunakan Promo
            </button>
        </div>
        @if($promoMessage)
            <p @class(['mt-2 text-sm', $appliedPromoCode ? 'text-green-700' : 'text-red-600'])>{{ $promoMessage }}</p>
        @endif
    </div>
</div>
