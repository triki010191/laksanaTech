<div class="max-w-2xl">
    <div class="mb-6">
        <h1 class="font-display text-2xl font-bold text-slate-900">Payment Gateway</h1>
        <p class="mt-1 text-sm text-slate-500">Pilih gateway aktif dan masukkan API key. Hanya Super Admin.</p>
    </div>

    <form wire:submit="save" class="admin-form admin-panel space-y-6">
        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" wire:model="payment_enabled" class="rounded border-slate-300">
            Aktifkan pembayaran online
        </label>

        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Gateway default</label>
            <select wire:model.live="payment_default_gateway" class="admin-input">
                @foreach($gateways as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        @if($payment_default_gateway === 'midtrans')
            <div class="space-y-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-800">Midtrans</p>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="payment_midtrans_is_production" class="rounded">
                    Mode production
                </label>
                <div>
                    <label class="text-caption mb-1 block">Server Key</label>
                    <input type="password" wire:model="payment_midtrans_server_key"
                           placeholder="{{ $meta['has_midtrans_server_key'] ? '•••••••• (kosongkan jika tidak diubah)' : 'SB-Mid-server-...' }}"
                           class="admin-input">
                </div>
                <div>
                    <label class="text-caption mb-1 block">Client Key</label>
                    <input type="text" wire:model="payment_midtrans_client_key"
                           placeholder="{{ $meta['has_midtrans_client_key'] ? '•••••••• (kosongkan jika tidak diubah)' : 'SB-Mid-client-...' }}"
                           class="admin-input">
                </div>
            </div>
        @endif

        @if($payment_default_gateway === 'xendit')
            <div class="space-y-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-800">Xendit</p>
                <div>
                    <label class="text-caption mb-1 block">Secret API Key</label>
                    <input type="password" wire:model="payment_xendit_secret_key"
                           placeholder="{{ $meta['has_xendit_secret_key'] ? '•••••••• (kosongkan jika tidak diubah)' : 'xnd_development_...' }}"
                           class="admin-input">
                </div>
                <div>
                    <label class="text-caption mb-1 block">Public Key (opsional)</label>
                    <input type="text" wire:model="payment_xendit_public_key"
                           placeholder="{{ $meta['has_xendit_public_key'] ? 'Sudah tersimpan' : 'xnd_public_...' }}"
                           class="admin-input">
                </div>
            </div>
        @endif

        @if($payment_default_gateway === 'tripay')
            <div class="space-y-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                <p class="text-sm font-semibold text-slate-800">Tripay</p>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="payment_tripay_is_production" class="rounded">
                    Mode production
                </label>
                <input wire:model="payment_tripay_merchant_code" placeholder="Merchant Code" class="admin-input">
                <input type="password" wire:model="payment_tripay_api_key" placeholder="API Key" class="admin-input">
                <input type="password" wire:model="payment_tripay_private_key" placeholder="Private Key" class="admin-input">
            </div>
        @endif

        <div @class([
            'rounded-xl border p-3 text-sm',
            'border-green-100 bg-green-50 text-green-900' => $gatewayConfigured,
            'border-amber-100 bg-amber-50 text-amber-900' => ! $gatewayConfigured,
        ])>
            @if($gatewayConfigured)
                Gateway <strong>{{ strtoupper($payment_default_gateway) }}</strong> sudah punya API key.
            @else
                API key belum diisi — halaman pesan tetap bisa dibuka, tetapi pembayaran akan gagal sampai API diisi.
            @endif
        </div>

        <button type="submit" class="admin-btn admin-btn--primary">Simpan Pengaturan</button>
    </form>
</div>
