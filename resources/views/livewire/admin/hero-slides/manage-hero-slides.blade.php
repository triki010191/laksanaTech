<div>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-display text-2xl font-bold">Hero Slider</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola gambar dan teks slider beranda</p>
        </div>
        <button wire:click="create" class="rounded-xl bg-brand-primary px-4 py-2 text-sm font-medium text-white hover:opacity-90">
            + Tambah Slide
        </button>
    </div>

    @if($showForm)
        <form wire:submit="save" class="admin-form admin-panel mt-6 space-y-4">
            <h2 class="admin-form-section__title">{{ $editingId ? 'Edit Slide' : 'Slide Baru' }}</h2>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Badge</label>
                    <input wire:model="badge" type="text" placeholder="Mitra Digital Terpercaya" class="admin-input">
                    @error('badge') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Urutan</label>
                    <input wire:model="sort_order" type="number" min="0" class="admin-input">
                    @error('sort_order') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Judul *</label>
                <input wire:model="title" type="text" placeholder="Buat website & aplikasi profesional," class="admin-input">
                @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Penekanan (italic)</label>
                <input wire:model="emphasis" type="text" placeholder="tumbuh bersama bisnis Anda." class="admin-input">
                @error('emphasis') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Deskripsi</label>
                <textarea wire:model="description" rows="3" class="admin-input"></textarea>
                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="admin-form-upload">
                <label class="mb-2 block text-sm font-medium text-slate-700">
                    Gambar Slider {{ $editingId ? '' : '*' }}
                </label>

                @if($editingId && $currentImageUrl && ! $replacingImage)
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <img src="{{ $currentImageUrl }}" alt="Gambar slide saat ini" class="max-h-40 rounded-lg border bg-white object-contain">
                        <button
                            type="button"
                            wire:click="enableImageReplace"
                            class="mt-3 inline-flex rounded-lg border border-brand-primary/30 bg-white px-4 py-2 text-sm font-medium text-brand-primary hover:bg-brand-primary/5"
                        >
                            Ganti Gambar
                        </button>
                    </div>
                @else
                    @if($currentImageUrl && ! $image)
                        <img src="{{ $currentImageUrl }}" alt="Gambar slide saat ini" class="mb-3 max-h-40 rounded-lg border bg-white object-contain">
                    @endif
                    @if($image)
                        <p class="mb-2 text-xs text-green-700">File baru: {{ $image->getClientOriginalName() }}</p>
                    @endif
                    <input wire:model="image" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm text-slate-600">
                    <p class="mt-1 text-xs text-slate-500">JPG/PNG/WebP, maks. 5 MB. Rekomendasi: latar putih, rasio landscape.</p>
                    @if($editingId && $currentImageUrl)
                        <button
                            type="button"
                            wire:click="cancelImageReplace"
                            class="mt-2 text-sm text-slate-500 underline hover:text-slate-700"
                        >
                            Batal ganti gambar
                        </button>
                    @endif
                    @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                @endif
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="admin-form-section space-y-2">
                    <p class="text-sm font-medium text-slate-700">Tombol Utama</p>
                    <input wire:model="cta_primary_label" type="text" placeholder="Label tombol" class="admin-input">
                    <select wire:model="cta_primary_route" class="admin-input">
                        @foreach($routeOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-form-section space-y-2">
                    <p class="text-sm font-medium text-slate-700">Tombol Sekunder</p>
                    <input wire:model="cta_secondary_label" type="text" placeholder="Label tombol" class="admin-input">
                    <select wire:model="cta_secondary_route" class="admin-input">
                        @foreach($routeOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <input wire:model="cta_secondary_url" type="url" placeholder="Atau URL eksternal (WhatsApp, dll.)" class="admin-input">
                    @error('cta_secondary_url') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model="is_active" class="rounded border-slate-300">
                Aktif (tampil di beranda)
            </label>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="admin-btn admin-btn--success inline-flex rounded-lg bg-emerald-600 px-5 py-2 text-sm font-semibold text-white">Simpan</button>
                <button type="button" wire:click="cancel" class="admin-btn admin-btn--ghost inline-flex rounded-lg bg-slate-100 px-5 py-2 text-sm text-slate-600">Batal</button>
            </div>
        </form>
    @endif

    <div class="mt-6 space-y-4">
        @forelse($slides as $slide)
            <div class="flex flex-col gap-4 rounded-2xl border bg-white p-4 shadow-sm sm:flex-row sm:items-center">
                <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}" class="h-24 w-40 flex-shrink-0 rounded-xl border bg-slate-50 object-contain">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">#{{ $slide->sort_order }}</span>
                        @if($slide->is_active)
                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Aktif</span>
                        @else
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">Nonaktif</span>
                        @endif
                    </div>
                    <p class="mt-1 font-semibold text-slate-900">{{ $slide->title }} <span class="font-normal italic text-brand-primary">{{ $slide->emphasis }}</span></p>
                    <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $slide->description }}</p>
                </div>
                <div class="flex gap-2 sm:flex-col">
                    <button wire:click="edit({{ $slide->id }})" class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm text-brand-primary hover:bg-slate-50">Edit</button>
                    <button wire:click="delete({{ $slide->id }})" wire:confirm="Hapus slide ini?" class="rounded-lg border border-red-100 px-3 py-1.5 text-sm text-red-600 hover:bg-red-50">Hapus</button>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed bg-white p-8 text-center text-sm text-slate-500">
                Belum ada slide. Klik <strong>Tambah Slide</strong> atau jalankan seeder.
            </div>
        @endforelse
    </div>
</div>
