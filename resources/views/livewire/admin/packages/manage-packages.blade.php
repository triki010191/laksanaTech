<div>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Paket Website</h1>
            <p class="mt-1 text-sm text-slate-500">Hosting & langganan tahunan checkout</p>
        </div>
        <button wire:click="create" class="admin-btn admin-btn--primary">+ Tambah Paket</button>
    </div>

    @if($showForm)
        <form wire:submit="save" class="admin-form admin-panel mt-6">
            <h2 class="admin-form-section__title mb-4">{{ $editingId ? 'Edit Paket' : 'Paket Baru' }}</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Nama paket *</label>
                    <input wire:model="name" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Harga *</label>
                    <input wire:model="price" type="number" class="admin-input">
                </div>
            </div>
            <div class="mt-4">
                <label class="admin-label">Deskripsi</label>
                <textarea wire:model="description" rows="2" class="admin-input"></textarea>
            </div>
            <div class="mt-4">
                <label class="admin-label">Fitur (satu per baris)</label>
                <textarea wire:model="featuresText" rows="4" class="admin-input"></textarea>
            </div>
            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="admin-label">Periode</label>
                    <select wire:model="billing_period" class="admin-input">
                        <option value="yearly">Tahunan</option>
                        <option value="monthly">Bulanan</option>
                    </select>
                </div>
                <div>
                    <label class="admin-label">Urutan</label>
                    <input wire:model="sort_order" type="number" class="admin-input">
                </div>
            </div>
            <div class="mt-4 flex flex-wrap gap-4">
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_featured"> Paket populer</label>
                <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_active"> Aktif</label>
            </div>
            <div class="mt-6 flex gap-2">
                <button type="submit" class="admin-btn admin-btn--success">Simpan</button>
                <button type="button" wire:click="$set('showForm', false)" class="admin-btn admin-btn--ghost">Batal</button>
            </div>
        </form>
    @endif

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        @forelse($packages as $pkg)
            <div class="admin-panel p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-semibold text-slate-900">{{ $pkg->name }}</h3>
                            @if($pkg->is_featured)<x-admin.status-badge status="featured" type="template" />@endif
                            <x-admin.status-badge :status="$pkg->is_active ? 'active' : 'inactive'" type="template" />
                        </div>
                        <p class="mt-2 text-lg font-bold text-brand-primary">
                            Rp {{ number_format($pkg->price, 0, ',', '.') }}
                            <span class="text-sm font-normal text-slate-500">/ {{ $pkg->billing_period === 'yearly' ? 'tahun' : 'bulan' }}</span>
                        </p>
                        @if($pkg->description)<p class="mt-2 text-sm text-slate-600">{{ $pkg->description }}</p>@endif
                    </div>
                </div>
                <div class="mt-4 flex gap-2">
                    <button wire:click="edit({{ $pkg->id }})" class="admin-btn admin-btn--primary admin-btn--sm">Edit</button>
                    <button wire:click="delete({{ $pkg->id }})" wire:confirm="Hapus paket?" class="admin-btn admin-btn--danger admin-btn--sm">Hapus</button>
                </div>
            </div>
        @empty
            <p class="text-slate-500 sm:col-span-2">Belum ada paket.</p>
        @endforelse
    </div>
</div>
