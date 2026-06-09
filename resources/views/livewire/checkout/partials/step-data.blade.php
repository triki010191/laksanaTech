<div>
    <h3 class="text-section-title text-2xl">Lengkapi data diri untuk <em class="text-emphasis">aktivasi domain</em></h3>
    <p class="text-section-lead mt-2">Hanya 3 kolom. Data ini dibutuhkan untuk pendaftaran domain ke registrar resmi.</p>

    <div class="mt-6 space-y-4 max-w-lg">
        <div>
            <label class="text-caption mb-1 block font-semibold text-heading">Nama Lengkap</label>
            <input wire:model="customerName" type="text" placeholder="Tulis nama lengkap kamu"
                   class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
            @error('customerName') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-caption mb-1 block font-semibold text-heading">Email</label>
            <input wire:model="customerEmail" type="email" placeholder="contoh: kamu@perusahaan.com"
                   class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
            <p class="text-caption mt-1">Bukti pembayaran dan info aktivasi dikirim ke sini.</p>
            @error('customerEmail') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-caption mb-1 block font-semibold text-heading">No. Handphone</label>
            <input wire:model="phone" type="text" placeholder="8123456789"
                   class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">
            <p class="text-caption mt-1">Tim kami akan menghubungi via WhatsApp setelah pembayaran.</p>
            @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    @guest
        <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
            Untuk melanjutkan pembayaran, silakan <a href="{{ route('login') }}" class="font-semibold underline">masuk</a>
            atau <a href="{{ route('register') }}" class="font-semibold underline">daftar akun</a>.
        </div>
    @endguest

    <div class="mt-6 rounded-xl border border-sky-100 bg-sky-50 p-4 text-sm text-slate-700">
        Data kamu aman — disimpan terenkripsi dan hanya dibagikan ke registrar domain resmi sesuai regulasi.
    </div>
</div>
