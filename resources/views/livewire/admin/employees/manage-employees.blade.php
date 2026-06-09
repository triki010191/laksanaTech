<div>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Kelola Karyawan</h1>
            <p class="mt-1 text-sm text-slate-500">Profil lengkap · karyawan/freelancer · terhubung ke akun user</p>
        </div>
        <button wire:click="create" class="admin-btn admin-btn--primary inline-flex rounded-lg bg-brand-primary px-4 py-2 text-sm font-semibold text-white">+ Tambah Karyawan</button>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
        <select wire:model.live="filterType" class="admin-input w-auto min-w-[10rem]">
            <option value="">Semua tipe</option>
            @foreach($employmentTypes as $t)
                <option value="{{ $t->value }}">{{ $t->label() }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterSpec" class="admin-input w-auto min-w-[12rem]">
            <option value="">Semua spesialisasi</option>
            @foreach($specializations as $s)
                <option value="{{ $s->value }}">{{ $s->label() }}</option>
            @endforeach
        </select>
    </div>

    @if($showForm)
        <form wire:submit="save" class="admin-form admin-panel mt-6 space-y-6">
            <h2 class="admin-form-section__title">{{ $editingUserId ? 'Edit Karyawan' : 'Karyawan Baru' }}</h2>

            <div class="admin-form-section space-y-4">
                <p class="text-sm font-semibold text-slate-800">Akun User (login)</p>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="admin-label">Nama lengkap *</label>
                        <input wire:model="name" class="admin-input">
                        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="admin-label">Email login *</label>
                        <input wire:model="email" type="email" class="admin-input">
                        @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="admin-label">Telepon</label>
                        <input wire:model="phone" class="admin-input">
                    </div>
                    <div>
                        <label class="admin-label">Password {{ $editingUserId ? '(kosongkan jika tidak diubah)' : '*' }}</label>
                        <input wire:model="password" type="password" class="admin-input" autocomplete="new-password">
                        @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="admin-label">Akses sistem (role) *</label>
                        <select wire:model="role" class="admin-input">
                            @foreach($assignableRoles as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Status akun</label>
                        <select wire:model="status" class="admin-input">
                            @foreach($statuses as $s)
                                <option value="{{ $s->value }}">{{ ucfirst($s->value) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="admin-form-section space-y-4">
                <p class="text-sm font-semibold text-slate-800">Profil Karyawan</p>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="admin-label">Kode karyawan</label>
                        <input wire:model="employee_code" class="admin-input" placeholder="Auto jika kosong">
                    </div>
                    <div>
                        <label class="admin-label">Tipe *</label>
                        <select wire:model.live="employment_type" class="admin-input">
                            @foreach($employmentTypes as $t)
                                <option value="{{ $t->value }}">{{ $t->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Spesialisasi *</label>
                        <select wire:model.live="specialization" class="admin-input">
                            @foreach($specializations as $s)
                                <option value="{{ $s->value }}">{{ $s->label() }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-slate-500">Menentukan dashboard & tugas (Marketing, CS, Desain, dll.)</p>
                    </div>
                    <div>
                        <label class="admin-label">Jabatan</label>
                        <input wire:model="job_title" class="admin-input" placeholder="Senior Designer, CS Lead, dll.">
                    </div>
                    <div>
                        <label class="admin-label">Tanggal bergabung</label>
                        <input wire:model="joined_at" type="date" class="admin-input">
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="profile_active" class="rounded"> Profil aktif</label>
                    </div>
                </div>
                <div>
                    <label class="admin-label">Bio / deskripsi</label>
                    <textarea wire:model="bio" rows="3" class="admin-input"></textarea>
                </div>
                <div>
                    <label class="admin-label">Keahlian (pisahkan koma)</label>
                    <input wire:model="skillsText" class="admin-input" placeholder="Figma, Laravel, Copywriting">
                </div>
                <div>
                    <label class="admin-label">Catatan internal (admin saja)</label>
                    <textarea wire:model="internal_notes" rows="2" class="admin-input"></textarea>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="admin-btn admin-btn--success inline-flex rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Simpan Karyawan</button>
                <button type="button" wire:click="$set('showForm', false)" class="admin-btn admin-btn--ghost inline-flex rounded-lg bg-slate-100 px-4 py-2 text-sm text-slate-600">Batal</button>
            </div>
        </form>
    @endif

    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($employees as $employee)
            @php $p = $employee->employeeProfile; @endphp
            <div class="admin-panel p-5">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="font-semibold text-slate-900">{{ $employee->name }}</p>
                        <p class="text-xs text-slate-500">{{ $p->employee_code ?? '-' }}</p>
                    </div>
                    @if($p && ! $p->is_active)
                        <span class="admin-badge admin-badge--slate">Nonaktif</span>
                    @endif
                </div>
                <p class="mt-2 text-sm text-slate-600">{{ $employee->email }}</p>
                <div class="mt-3 flex flex-wrap gap-1">
                    <span class="admin-badge admin-badge--purple">{{ $p?->employmentLabel() }}</span>
                    <span class="admin-badge admin-badge--blue">{{ $p?->specializationLabel() }}</span>
                    @foreach($employee->roles as $r)
                        <span class="admin-badge admin-badge--slate">{{ $roleLabels[$r->name] ?? $r->name }}</span>
                    @endforeach
                </div>
                @if($p?->job_title)<p class="mt-2 text-sm font-medium text-slate-700">{{ $p->job_title }}</p>@endif
                @if($p?->bio)<p class="mt-1 line-clamp-2 text-xs text-slate-500">{{ $p->bio }}</p>@endif
                <div class="mt-4 flex gap-2">
                    <button wire:click="edit({{ $employee->id }})" class="admin-btn admin-btn--primary admin-btn--sm inline-flex rounded-lg bg-brand-primary px-3 py-1.5 text-xs font-semibold text-white">Edit</button>
                    @if($employee->id !== auth()->id())
                        <button wire:click="delete({{ $employee->id }})" wire:confirm="Hapus karyawan & akun user?" class="admin-btn admin-btn--danger admin-btn--sm inline-flex rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white">Hapus</button>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-slate-500 sm:col-span-3">Belum ada karyawan. Klik Tambah Karyawan untuk membuat profil + akun login.</p>
        @endforelse
    </div>
    {{ $employees->links() }}
</div>
