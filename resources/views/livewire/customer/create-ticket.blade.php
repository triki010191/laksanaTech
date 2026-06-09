<div>
    <h1 class="font-display text-2xl font-bold text-slate-900">Buat Tiket Support</h1>
    <form wire:submit="submit" class="mt-6 max-w-xl space-y-4">
        <div>
            <label class="mb-1 block text-sm font-medium">Subjek</label>
            <input wire:model="subject" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm">
            @error('subject') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Prioritas</label>
            <select wire:model="priority" class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm">
                <option value="low">Rendah</option>
                <option value="medium">Sedang</option>
                <option value="high">Tinggi</option>
                <option value="urgent">Mendesak</option>
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Pesan</label>
            <textarea wire:model="message" rows="5" class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm"></textarea>
            @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="rounded-2xl bg-brand-primary px-5 py-2.5 text-sm font-semibold text-white">Kirim Tiket</button>
    </form>
</div>
