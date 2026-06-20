<div>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Produk Template</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola produk website siap pakai — harga, promo, dan status</p>
        </div>
        <button wire:click="create" class="admin-btn admin-btn--primary inline-flex items-center rounded-lg bg-brand-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:brightness-105">+ Tambah Produk</button>
    </div>

    @if($showForm)
        <form wire:submit="save" class="admin-form admin-panel mt-6">
            <h2 class="admin-form-section__title mb-4">{{ $editingId ? 'Edit Produk' : 'Produk Baru' }}</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Nama produk *</label>
                    <input wire:model="name" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Kategori *</label>
                    <select wire:model="template_category_id" class="admin-input">
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label class="admin-label">Deskripsi singkat</label>
                <textarea wire:model="short_description" rows="2" class="admin-input"></textarea>
            </div>
            <div class="mt-4">
                <label class="admin-label">Deskripsi lengkap</label>
                <textarea wire:model="description" rows="3" class="admin-input"></textarea>
            </div>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Harga normal *</label>
                    <input wire:model="price" type="number" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Harga promo</label>
                    <input wire:model="sale_price" type="number" class="admin-input">
                </div>
            </div>
            <div class="mt-4">
                <label class="admin-label">URL Demo Live (ThemeForest / Envato)</label>
                <input wire:model="live_demo_url" type="url" class="admin-input" placeholder="https://preview.themeforest.net/item/...">
                <p class="mt-1 text-xs text-slate-500">Thumbnail otomatis diambil dari URL ini. Klik kartu / Lihat Demo membuka tab baru.</p>
            </div>
            <div class="mt-4">
                <label class="admin-label">URL Demo (legacy, opsional)</label>
                <input wire:model="demo_url" class="admin-input">
            </div>
            <div class="admin-form-upload mt-4">
                @if($currentPreviewUrl)<img src="{{ $currentPreviewUrl }}" class="mb-3 max-h-36 rounded-xl">@endif
                <label class="admin-label">Preview image (fallback manual)</label>
                <input wire:model="preview_image" type="file" accept="image/*" class="admin-input admin-input--file">
                <p class="mt-1 text-xs text-slate-500">Digunakan jika screenshot API gagal dimuat.</p>
            </div>
            <div class="mt-4 flex flex-wrap gap-4">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_featured" class="rounded"> Unggulan</label>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_active" class="rounded"> Aktif di marketplace</label>
            </div>
            <div class="mt-6 flex gap-2">
                <button type="submit" class="admin-btn admin-btn--success inline-flex rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                <button type="button" wire:click="$set('showForm', false)" class="admin-btn admin-btn--ghost inline-flex rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">Batal</button>
            </div>
        </form>
    @endif

    <div class="admin-table-wrap mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="admin-table w-full border-collapse text-sm">
            <thead class="bg-slate-50 text-left">
                <tr>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Produk</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Kategori</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Harga</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $t)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">
                            <p class="font-medium text-slate-900">{{ $t->name }}</p>
                            @if($t->is_featured)<x-admin.status-badge status="featured" type="template" class="mt-1" />@endif
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $t->category->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="font-semibold text-brand-primary">Rp {{ number_format($t->sale_price ?? $t->price, 0, ',', '.') }}</span>
                            @if($t->sale_price)<br><span class="text-xs text-slate-400 line-through">Rp {{ number_format($t->price, 0, ',', '.') }}</span>@endif
                        </td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :status="$t->is_active ? 'active' : 'inactive'" type="template" />
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="edit({{ $t->id }})" class="admin-btn admin-btn--primary admin-btn--sm inline-flex rounded-lg bg-brand-primary px-3 py-1.5 text-xs font-semibold text-white">Edit</button>
                            <button wire:click="delete({{ $t->id }})" wire:confirm="Hapus template ini?" class="admin-btn admin-btn--danger admin-btn--sm ml-1 inline-flex rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-10 text-center text-slate-500">Belum ada produk template.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $templates->links() }}
</div>
