<div class="max-w-2xl">
    <div class="mb-6">
        <h1 class="font-display text-2xl font-bold text-slate-900">Integrasi API</h1>
        <p class="mt-1 text-sm text-slate-500">Domain reseller, webhook, dan koneksi aplikasi eksternal.</p>
    </div>

    <form wire:submit="save" class="admin-form space-y-6">
        <div class="admin-form-section">
            <h2 class="admin-form-section__title">Domain Reseller</h2>
            <p class="admin-form-section__desc">Saat ini checkout memakai mode simulasi jika provider = Simulasi.</p>

            <div class="space-y-4">
                <div>
                    <label class="admin-label">Provider</label>
                    <select wire:model="integration_domain_provider" class="admin-input">
                        <option value="simulated">Simulasi (development)</option>
                        <option value="resellerclub">ResellerClub</option>
                        <option value="rumahweb">Rumahweb</option>
                        <option value="nic">NIC / PANDI</option>
                        <option value="idwebhost">IDWebhost</option>
                    </select>
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-700">
                    <input type="checkbox" wire:model="integration_domain_sandbox" class="rounded border-slate-300">
                    Mode sandbox / uji coba
                </label>
                <div>
                    <label class="admin-label">API Key</label>
                    <input type="password" wire:model="integration_domain_api_key" class="admin-input"
                           placeholder="{{ $meta['has_domain_api_key'] ? '•••••••• (kosongkan jika tidak diubah)' : 'Masukkan API key' }}">
                </div>
                <div>
                    <label class="admin-label">API Secret</label>
                    <input type="password" wire:model="integration_domain_api_secret" class="admin-input"
                           placeholder="{{ $meta['has_domain_api_secret'] ? '•••••••• (kosongkan jika tidak diubah)' : 'Masukkan secret' }}">
                </div>
            </div>
        </div>

        <div class="admin-form-section">
            <h2 class="admin-form-section__title">Webhook & Aplikasi Lain</h2>
            <div class="space-y-4">
                <div>
                    <label class="admin-label">Webhook Secret</label>
                    <input type="password" wire:model="integration_webhook_secret" class="admin-input"
                           placeholder="{{ $meta['has_webhook_secret'] ? 'Sudah tersimpan' : 'Secret untuk validasi webhook' }}">
                </div>
                <div>
                    <label class="admin-label">Catatan API (internal)</label>
                    <textarea wire:model="integration_api_notes" rows="3" class="admin-input" placeholder="Endpoint custom, token mobile app, dll."></textarea>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-600">
            API publik website: <code class="text-xs">/api/v1/...</code> — autentikasi via Sanctum token.
        </div>

        <button type="submit" class="admin-btn admin-btn--primary">Simpan Integrasi</button>
    </form>
</div>
