<div>
    <div class="mb-6">
        <h1 class="font-display text-2xl font-bold text-slate-900">Profil Saya</h1>
        <p class="mt-1 text-sm text-slate-500">Informasi pribadi & keahlian</p>
    </div>

    @if($profile)
        <div class="admin-panel mb-6 p-5">
            <div class="flex flex-wrap gap-2">
                <span class="admin-badge admin-badge--purple">{{ $profile->employmentLabel() }}</span>
                <span class="admin-badge admin-badge--blue">{{ $profile->specializationLabel() }}</span>
                <span class="admin-badge admin-badge--slate">{{ $profile->employee_code }}</span>
            </div>
            @foreach($user->roles as $r)
                <p class="mt-2 text-sm text-slate-600">Akses sistem: <strong>{{ $r->name }}</strong></p>
            @endforeach
        </div>
    @else
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
            Profil karyawan belum lengkap. Isi form di bawah — data akan tersimpan dan dashboard Anda akan disesuaikan.
        </div>
    @endif

    <form wire:submit="save" class="admin-form admin-panel max-w-2xl space-y-4">
        <div>
            <label class="admin-label">Nama</label>
            <input wire:model="name" class="admin-input">
            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="admin-label">Email (tidak bisa diubah)</label>
            <input value="{{ $email }}" disabled class="admin-input bg-slate-100 text-slate-500">
        </div>
        <div>
            <label class="admin-label">Telepon</label>
            <input wire:model="phone" class="admin-input">
        </div>
        <div>
            <label class="admin-label">Jabatan</label>
            <input wire:model="job_title" class="admin-input">
        </div>
        <div>
            <label class="admin-label">Bio</label>
            <textarea wire:model="bio" rows="4" class="admin-input"></textarea>
        </div>
        <div>
            <label class="admin-label">Keahlian (pisahkan koma)</label>
            <input wire:model="skillsText" class="admin-input">
        </div>
        <div>
            <label class="admin-label">Password baru (opsional)</label>
            <input wire:model="password" type="password" class="admin-input" autocomplete="new-password">
            @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="admin-btn admin-btn--primary inline-flex rounded-lg bg-brand-primary px-5 py-2 text-sm font-semibold text-white">Simpan Profil</button>
    </form>
</div>
