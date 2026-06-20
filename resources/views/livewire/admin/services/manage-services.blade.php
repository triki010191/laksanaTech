<div>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Bidang Layanan</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola halaman layanan — kartu, deskripsi, harga, dan gambar</p>
        </div>
        <button wire:click="create" class="admin-btn admin-btn--primary inline-flex items-center rounded-lg bg-brand-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:brightness-105">+ Tambah Layanan</button>
    </div>

    @if (session('status'))
        <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
    @endif

    @if($showForm)
        <form wire:submit="save" class="admin-form admin-panel mt-6">
            <h2 class="admin-form-section__title mb-4">{{ $editingId ? 'Edit Layanan' : 'Layanan Baru' }}</h2>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Nama layanan *</label>
                    <input wire:model.live="name" type="text" class="admin-input">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="admin-label">Slug URL *</label>
                    <input wire:model="slug" type="text" class="admin-input" placeholder="keamanan-dan-akses">
                    @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Kategori *</label>
                    <select wire:model="service_category_id" class="admin-input">
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('service_category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="admin-label">Ikon kartu</label>
                    <select wire:model="category_icon" class="admin-input">
                        @foreach($iconOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category_icon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Label audiens</label>
                    <input wire:model="audience" type="text" class="admin-input" placeholder="Bisnis, sekolah, instansi">
                    <p class="mt-1 text-xs text-slate-500">Teks kecil di atas judul kartu layanan.</p>
                </div>
                <div>
                    <label class="admin-label">Tipe layanan</label>
                    <select wire:model="type" class="admin-input">
                        @foreach($serviceTypes as $serviceType)
                            <option value="{{ $serviceType->value }}">{{ $serviceType->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <label class="admin-label">Ringkasan (kartu & meta)</label>
                <textarea wire:model="short_description" rows="2" class="admin-input"></textarea>
                @error('short_description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mt-4">
                <label class="admin-label">Deskripsi lengkap (halaman detail)</label>
                <textarea wire:model="description" rows="5" class="admin-input"></textarea>
                <p class="mt-1 text-xs text-slate-500">Kosongkan untuk memakai ringkasan di halaman detail.</p>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="admin-label">Harga mulai (Rp)</label>
                    <input wire:model="starting_price" type="number" min="0" class="admin-input" placeholder="5000000">
                    @error('starting_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="admin-label">Estimasi pengerjaan</label>
                    <input wire:model="delivery_estimate" type="text" class="admin-input" placeholder="7–14 hari kerja">
                </div>
                <div>
                    <label class="admin-label">Urutan</label>
                    <input wire:model="sort_order" type="number" min="0" class="admin-input">
                    @error('sort_order') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="admin-label">Fitur (pisahkan koma)</label>
                <textarea wire:model="features" rows="2" class="admin-input" placeholder="Instalasi CCTV, Access door, Maintenance"></textarea>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">URL demo (opsional)</label>
                    <input wire:model="demo_url" type="url" class="admin-input" placeholder="https://...">
                    @error('demo_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="admin-label">Gambar cover</label>
                    <input wire:model="preview_image" type="file" accept="image/*" class="admin-input">
                    @if($currentPreviewUrl)
                        <img src="{{ $currentPreviewUrl }}" alt="" class="mt-2 h-24 rounded-lg object-cover">
                    @endif
                    @error('preview_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-6">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input wire:model="is_featured" type="checkbox" class="rounded border-slate-300">
                    Tampil di beranda
                </label>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input wire:model="is_active" type="checkbox" class="rounded border-slate-300">
                    Aktif / publik
                </label>
            </div>

            <div class="mt-6 flex gap-3">
                <button type="submit" class="admin-btn admin-btn--primary rounded-lg bg-brand-primary px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                <button type="button" wire:click="cancel" class="admin-btn rounded-lg border border-slate-200 px-4 py-2 text-sm">Batal</button>
            </div>
        </form>
    @endif

    <div class="admin-panel mt-6">
        <div class="mb-4">
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari layanan..." class="admin-input max-w-xs">
        </div>

        <div class="overflow-x-auto">
            <table class="admin-table w-full text-sm">
                <thead>
                    <tr>
                        <th class="text-left">Layanan</th>
                        <th class="text-left">Slug</th>
                        <th class="text-left">Harga</th>
                        <th class="text-left">Urutan</th>
                        <th class="text-left">Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr wire:key="service-{{ $service->id }}">
                            <td>
                                <div class="flex items-center gap-3">
                                    @if($service->cover_image_url)
                                        <img src="{{ $service->cover_image_url }}" alt="" class="h-10 w-14 rounded object-cover">
                                    @else
                                        <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-accent/10 text-brand-accent">
                                            @include('partials.icons.service-icon', ['name' => $service->category?->icon ?? 'app'])
                                        </span>
                                    @endif
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $service->name }}</p>
                                        @if($service->audience)
                                            <p class="text-xs text-slate-500">{{ $service->audience }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-slate-600">{{ $service->slug }}</td>
                            <td>
                                @if($service->starting_price)
                                    Rp {{ number_format($service->starting_price, 0, ',', '.') }}
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td>{{ $service->sort_order }}</td>
                            <td>
                                @if($service->is_active)
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700">Aktif</span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('services.show', $service->slug) }}" target="_blank" class="text-xs text-brand-primary hover:underline">Lihat</a>
                                    <button wire:click="edit({{ $service->id }})" class="text-xs text-slate-600 hover:text-brand-primary">Edit</button>
                                    <button wire:click="delete({{ $service->id }})" wire:confirm="Hapus layanan ini?" class="text-xs text-red-600 hover:underline">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">Belum ada layanan. Klik Tambah Layanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $services->links() }}</div>
    </div>
</div>
