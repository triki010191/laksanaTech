<div>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">FAQ</h1>
            <p class="mt-1 text-sm text-slate-500">Pertanyaan umum di website</p>
        </div>
        <button wire:click="create" class="admin-btn admin-btn--primary inline-flex rounded-lg bg-brand-primary px-4 py-2 text-sm font-semibold text-white">+ Tambah FAQ</button>
    </div>

    @if($showForm)
        <form wire:submit="save" class="admin-form admin-panel mt-6 space-y-4">
            <h2 class="admin-form-section__title">{{ $editingId ? 'Edit FAQ' : 'FAQ Baru' }}</h2>
            <div>
                <label class="admin-label">Kategori</label>
                <input wire:model="category" class="admin-input">
            </div>
            <div>
                <label class="admin-label">Pertanyaan</label>
                <input wire:model="question" class="admin-input">
            </div>
            <div>
                <label class="admin-label">Jawaban</label>
                <textarea wire:model="answer" rows="4" class="admin-input"></textarea>
            </div>
            <div class="w-40">
                <label class="admin-label">Urutan</label>
                <input wire:model="sort_order" type="number" class="admin-input">
            </div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_active" class="rounded"> Aktif</label>
            <div class="flex gap-2">
                <button type="submit" class="admin-btn admin-btn--success inline-flex rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                <button type="button" wire:click="$set('showForm', false)" class="admin-btn admin-btn--ghost inline-flex rounded-lg bg-slate-100 px-4 py-2 text-sm text-slate-600">Batal</button>
            </div>
        </form>
    @endif

    <div class="mt-6 space-y-3">
        @foreach($faqs as $faq)
            <div class="admin-panel p-5">
                <p class="font-semibold text-slate-900">{{ $faq->question }}</p>
                <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $faq->answer }}</p>
                <div class="mt-3 flex gap-2">
                    <button wire:click="edit({{ $faq->id }})" class="admin-btn admin-btn--primary admin-btn--sm inline-flex rounded-lg bg-brand-primary px-3 py-1.5 text-xs font-semibold text-white">Edit</button>
                    <button wire:click="delete({{ $faq->id }})" wire:confirm="Hapus?" class="admin-btn admin-btn--danger admin-btn--sm inline-flex rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white">Hapus</button>
                </div>
            </div>
        @endforeach
    </div>
    {{ $faqs->links() }}
</div>
