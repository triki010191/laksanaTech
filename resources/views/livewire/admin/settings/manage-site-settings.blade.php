<div>
    <div class="mb-6">
        <h1 class="font-display text-2xl font-bold">Pengaturan Website (CMS)</h1>
        <p class="mt-1 text-sm text-slate-500">Logo, promo bar, footer, WhatsApp, halaman Cari Design</p>
    </div>

    <div class="mb-4 flex flex-wrap gap-2">
        @foreach(['brand' => 'Brand & Logo', 'promo' => 'Promo Bar', 'footer' => 'Footer & CTA', 'whatsapp' => 'WhatsApp', 'design' => 'Halaman Cari Design'] as $tab => $label)
            <button type="button" wire:click="$set('activeTab', '{{ $tab }}')"
                @class(['rounded-xl px-4 py-2 text-sm font-medium transition', 'bg-brand-primary text-white' => $activeTab === $tab, 'bg-white border text-slate-600' => $activeTab !== $tab])>
                {{ $label }}
            </button>
        @endforeach
    </div>

    <form wire:submit="save" class="admin-form admin-panel space-y-4">
        @if($activeTab === 'brand')
            <div class="rounded-xl border border-dashed p-4">
                @if($current_logo_url)
                    <img src="{{ $current_logo_url }}" alt="Logo" class="mb-3 h-12 object-contain">
                @else
                    <p class="mb-2 text-sm text-slate-500">Logo default dari file brand. Upload untuk mengganti.</p>
                @endif
                <input wire:model="brand_logo" type="file" accept="image/*" class="text-sm">
            </div>
            <textarea wire:model="footer_tagline" rows="3" placeholder="Tagline footer" class="admin-input"></textarea>
            <input wire:model="footer_copyright" placeholder="Teks copyright" class="admin-input">
            <div class="grid gap-3 sm:grid-cols-3">
                <input wire:model="facebook_url" placeholder="URL Facebook" class="admin-input">
                <input wire:model="instagram_url" placeholder="URL Instagram" class="admin-input">
                <input wire:model="linkedin_url" placeholder="URL LinkedIn" class="admin-input">
            </div>
        @endif

        @if($activeTab === 'promo')
            <label class="flex gap-2 text-sm"><input type="checkbox" wire:model="promo_enabled"> Tampilkan promo bar</label>
            <input wire:model="promo_text" placeholder="Teks promo bar" class="admin-input">
            <input wire:model="promo_link_label" placeholder="Label link promo" class="admin-input">
        @endif

        @if($activeTab === 'footer')
            <label class="flex gap-2 text-sm"><input type="checkbox" wire:model="cta_enabled"> Tampilkan banner CTA</label>
            <input wire:model="cta_title" placeholder="Judul CTA" class="admin-input">
            <textarea wire:model="cta_subtitle" rows="2" placeholder="Subjudul CTA" class="admin-input"></textarea>
            <div class="grid gap-3 sm:grid-cols-2">
                <input wire:model="cta_button_label" placeholder="Label tombol CTA" class="admin-input">
                <input wire:model="cta_button_url" placeholder="URL tombol CTA (/cari-design)" class="admin-input">
            </div>
        @endif

        @if($activeTab === 'whatsapp')
            <label class="flex gap-2 text-sm"><input type="checkbox" wire:model="whatsapp_float_enabled"> Tampilkan tombol WhatsApp mengambang</label>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Nomor WhatsApp CS *</label>
                <input wire:model="whatsapp_number" type="text" placeholder="6285212455505 atau 085212455505" class="admin-input">
                <p class="mt-1 text-xs text-slate-500">Format internasional tanpa + (contoh: 6285212455505). Dipakai untuk tombol mengambang & promo bar.</p>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Nomor tampil di footer</label>
                <input wire:model="contact_phone" type="text" placeholder="085212455505" class="admin-input">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Pesan default WhatsApp</label>
                <input wire:model="whatsapp_float_message" type="text" placeholder="Halo, saya ingin konsultasi website." class="admin-input">
            </div>
        @endif

        @if($activeTab === 'design')
            <input wire:model="design_badge" placeholder="Badge (KOLEKSI · DESAIN)" class="admin-input">
            <input wire:model="design_title" placeholder="Judul halaman" class="admin-input">
            <textarea wire:model="design_subtitle" rows="3" class="admin-input"></textarea>
            <input wire:model="design_sidebar_note" placeholder="Catatan sidebar (BISA GANTI KAPAN SAJA)" class="admin-input">
            <textarea wire:model="design_sidebar_text" rows="2" class="admin-input"></textarea>
        @endif

        <button type="submit" class="admin-btn admin-btn--primary inline-flex rounded-lg bg-brand-primary px-6 py-2.5 text-sm font-semibold text-white">Simpan Pengaturan</button>
    </form>
</div>
