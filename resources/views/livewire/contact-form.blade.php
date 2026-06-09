<div>
    @if($submitted)
        <div class="rounded-2xl border border-green-200 bg-green-50 p-6 text-center">
            <p class="font-semibold text-green-800">Terima kasih! Pesan Anda telah kami terima.</p>
            <p class="mt-2 text-sm text-green-700">Tim kami akan menghubungi Anda segera.</p>
            <button type="button" wire:click="$set('submitted', false)" class="mt-4 text-sm font-medium text-brand-primary hover:underline">
                Kirim pesan lain
            </button>
        </div>
    @else
        <form wire:submit="submit" class="space-y-5">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Nama *</label>
                    <input wire:model="name" id="name" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email *</label>
                    <input wire:model="email" id="email" type="email" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="phone" class="mb-1 block text-sm font-medium text-slate-700">Telepon / WhatsApp</label>
                    <input wire:model="phone" id="phone" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20">
                    @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="subject" class="mb-1 block text-sm font-medium text-slate-700">Subjek</label>
                    <input wire:model="subject" id="subject" type="text" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20">
                    @error('subject') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label for="message" class="mb-1 block text-sm font-medium text-slate-700">Pesan *</label>
                <textarea wire:model="message" id="message" rows="5" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20"></textarea>
                @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="rounded-2xl bg-brand-primary px-6 py-3 text-sm font-semibold text-white transition hover:opacity-90" wire:loading.attr="disabled">
                <span wire:loading.remove>Kirim Pesan</span>
                <span wire:loading>Mengirim...</span>
            </button>
        </form>
    @endif
</div>
