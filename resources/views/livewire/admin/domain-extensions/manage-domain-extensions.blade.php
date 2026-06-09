<div>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Ekstensi Domain</h1>
            <p class="mt-1 text-sm text-slate-500">Harga domain .com, .id, dll (langkah checkout)</p>
        </div>
        <button wire:click="create" class="admin-btn admin-btn--primary inline-flex rounded-lg bg-brand-primary px-4 py-2 text-sm font-semibold text-white">+ Tambah Ekstensi</button>
    </div>

    @if($showForm)
        <form wire:submit="save" class="admin-form admin-panel mt-6">
            <h2 class="admin-form-section__title mb-4">{{ $editingId ? 'Edit Ekstensi' : 'Ekstensi Baru' }}</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Ekstensi</label>
                    <input wire:model="extension" placeholder=".com" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Label tampilan</label>
                    <input wire:model="label" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Harga / tahun</label>
                    <input wire:model="price" type="number" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Urutan</label>
                    <input wire:model="sort_order" type="number" class="admin-input">
                </div>
            </div>
            <label class="mt-4 flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_active" class="rounded"> Aktif</label>
            <div class="mt-6 flex gap-2">
                <button type="submit" class="admin-btn admin-btn--success inline-flex rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                <button type="button" wire:click="$set('showForm', false)" class="admin-btn admin-btn--ghost inline-flex rounded-lg bg-slate-100 px-4 py-2 text-sm text-slate-600">Batal</button>
            </div>
        </form>
    @endif

    <div class="admin-table-wrap mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="admin-table w-full border-collapse text-sm">
            <thead class="bg-slate-50 text-left">
                <tr>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Ekstensi</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Harga</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($extensions as $ext)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3 font-medium">{{ $ext->extension }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($ext->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :status="$ext->is_active ? 'active' : 'inactive'" type="template" />
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button wire:click="edit({{ $ext->id }})" class="admin-btn admin-btn--primary admin-btn--sm inline-flex rounded-lg bg-brand-primary px-3 py-1.5 text-xs font-semibold text-white">Edit</button>
                            <button wire:click="delete({{ $ext->id }})" wire:confirm="Hapus?" class="admin-btn admin-btn--danger admin-btn--sm ml-1 inline-flex rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
