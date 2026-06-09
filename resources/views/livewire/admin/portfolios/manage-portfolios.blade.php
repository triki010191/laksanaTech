<div>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-display text-2xl font-bold">Portofolio</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola proyek, screenshot, dan status publikasi</p>
        </div>
        <button wire:click="create" class="rounded-xl bg-brand-primary px-4 py-2 text-sm font-medium text-white hover:opacity-90">
            + Tambah Proyek
        </button>
    </div>

    @if($showForm)
        <form wire:submit="save" class="mt-6 space-y-4 rounded-2xl border bg-white p-6 shadow-sm">
            <h2 class="font-semibold">{{ $editingId ? 'Edit Proyek' : 'Proyek Baru' }}</h2>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Kategori *</label>
                    <select wire:model="portfolio_category_id" class="admin-input">
                        <option value="">— Pilih kategori —</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('portfolio_category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Urutan</label>
                    <input wire:model="sort_order" type="number" min="0" class="admin-input">
                    @error('sort_order') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Judul Proyek *</label>
                <input wire:model.live="title" type="text" class="admin-input">
                @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Slug URL</label>
                <input wire:model="slug" type="text" class="admin-input">
                @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Nama Klien *</label>
                    <input wire:model="client_name" type="text" class="admin-input">
                    @error('client_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">URL Proyek</label>
                    <input wire:model="project_url" type="url" placeholder="https://..." class="admin-input">
                    @error('project_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Deskripsi Singkat</label>
                <textarea wire:model="short_description" rows="3" class="admin-input"></textarea>
                @error('short_description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Studi Kasus (opsional)</label>
                <textarea wire:model="case_study" rows="4" class="admin-input"></textarea>
                @error('case_study') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Teknologi</label>
                    <input wire:model="technologies" type="text" placeholder="PHP, MySQL, Laravel" class="admin-input">
                    <p class="mt-1 text-xs text-slate-500">Pisahkan dengan koma</p>
                    @error('technologies') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Tanggal Selesai</label>
                    <input wire:model="completed_at" type="date" class="admin-input">
                    @error('completed_at') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-4">
                <label class="mb-2 block text-sm font-medium text-slate-700">Screenshot / Foto Proyek</label>

                @if($editingId && $editingImages->isNotEmpty())
                    <div class="mb-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($editingImages as $image)
                            <div class="relative overflow-hidden rounded-xl border bg-white">
                                <img src="{{ $image->url }}" alt="Screenshot" class="h-32 w-full object-cover object-top">
                                <button
                                    type="button"
                                    wire:click="deleteScreenshot({{ $image->id }})"
                                    wire:confirm="Hapus screenshot ini?"
                                    class="absolute right-2 top-2 rounded-lg bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700"
                                >
                                    Hapus
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif

                <input wire:model="screenshots" type="file" accept="image/jpeg,image/png,image/webp" multiple class="block w-full text-sm text-slate-600">
                <p class="mt-1 text-xs text-slate-500">JPG/PNG/WebP, maks. 5 MB per file. Bisa upload beberapa sekaligus.</p>
                @error('screenshots.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                <div wire:loading wire:target="screenshots" class="mt-2 text-xs text-brand-primary">Mengunggah preview...</div>
            </div>

            <div class="flex flex-wrap gap-4 text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model="is_featured" class="rounded border-slate-300">
                    Unggulan (tampil di beranda)
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" wire:model="is_published" class="rounded border-slate-300">
                    Publikasikan
                </label>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="submit" class="rounded-xl bg-brand-primary px-5 py-2 text-sm font-medium text-white">Simpan</button>
                <button type="button" wire:click="cancel" class="rounded-xl border border-slate-200 px-5 py-2 text-sm text-slate-600">Batal</button>
            </div>
        </form>
    @endif

    <div class="mt-6">
        <input
            wire:model.live.debounce.300ms="search"
            type="search"
            placeholder="Cari judul atau klien..."
            class="w-full max-w-md rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm"
        >
    </div>

    <div class="mt-6 space-y-4">
        @forelse($portfolios as $item)
            <div class="flex flex-col gap-4 rounded-2xl border bg-white p-4 shadow-sm sm:flex-row sm:items-center">
                @if($item->cover_image_url)
                    <img src="{{ $item->cover_image_url }}" alt="{{ $item->title }}" class="h-24 w-40 flex-shrink-0 rounded-xl border bg-slate-50 object-cover object-top">
                @else
                    <div class="flex h-24 w-40 flex-shrink-0 items-center justify-center rounded-xl border bg-gradient-to-br from-brand-primary/10 to-brand-secondary/10 text-xs text-slate-500">
                        Belum ada foto
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-brand-primary/10 px-2 py-0.5 text-xs font-medium text-brand-primary">
                            {{ $item->category->name ?? '—' }}
                        </span>
                        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">#{{ $item->sort_order }}</span>
                        @if($item->is_featured)
                            <span class="rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-700">Unggulan</span>
                        @endif
                        @if($item->is_published)
                            <span class="rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Publik</span>
                        @else
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">Draft</span>
                        @endif
                        <span class="text-xs text-slate-500">{{ $item->images->count() }} foto</span>
                    </div>
                    <p class="mt-1 text-xs font-medium uppercase tracking-wide text-slate-500">{{ $item->client_name }}</p>
                    <p class="font-semibold text-slate-900">{{ $item->title }}</p>
                    <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $item->short_description }}</p>
                </div>
                <div class="flex gap-2 sm:flex-col">
                    <button wire:click="edit({{ $item->id }})" class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm text-brand-primary hover:bg-slate-50">Edit</button>
                    <button wire:click="delete({{ $item->id }})" wire:confirm="Hapus proyek ini beserta semua fotonya?" class="rounded-lg border border-red-100 px-3 py-1.5 text-sm text-red-600 hover:bg-red-50">Hapus</button>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed bg-white p-8 text-center text-sm text-slate-500">
                Belum ada portofolio. Klik <strong>Tambah Proyek</strong> atau jalankan seeder.
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $portfolios->links() }}</div>
</div>
