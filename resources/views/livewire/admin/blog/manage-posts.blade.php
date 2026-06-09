<div>
    <div class="flex items-center justify-between">
        <h1 class="font-display text-2xl font-bold">Blog</h1>
        <button wire:click="create" class="rounded-xl bg-brand-primary px-4 py-2 text-sm font-semibold text-white">+ Artikel</button>
    </div>

    @if($showForm)
        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="font-semibold">{{ $editingId ? 'Edit' : 'Tambah' }} Artikel</h2>
            <form wire:submit="save" class="mt-4 space-y-4">
                <input wire:model="title" placeholder="Judul" class="admin-input">
                <input wire:model="slug" placeholder="Slug" class="admin-input">
                <select wire:model="blog_category_id" class="admin-input">
                    <option value="">— Kategori —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                <textarea wire:model="excerpt" rows="2" placeholder="Ringkasan" class="admin-input"></textarea>
                <textarea wire:model="content" rows="8" placeholder="Konten" class="admin-input"></textarea>
                <select wire:model="status" class="admin-input">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
                <div class="flex gap-2">
                    <button type="submit" class="rounded-xl bg-brand-primary px-4 py-2 text-sm text-white">Simpan</button>
                    <button type="button" wire:click="$set('showForm', false)" class="admin-input">Batal</button>
                </div>
            </form>
        </div>
    @endif

    <div class="mt-6 overflow-hidden rounded-2xl border bg-white">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left"><tr><th class="px-4 py-3">Judul</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Aksi</th></tr></thead>
            <tbody>
                @foreach($posts as $post)
                    <tr class="border-t">
                        <td class="px-4 py-3">{{ $post->title }}</td>
                        <td class="px-4 py-3">{{ $post->status }}</td>
                        <td class="px-4 py-3 space-x-2">
                            <button wire:click="edit({{ $post->id }})" class="text-brand-primary">Edit</button>
                            <button wire:click="delete({{ $post->id }})" wire:confirm="Hapus artikel?" class="text-red-600">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $posts->links() }}</div>
</div>
