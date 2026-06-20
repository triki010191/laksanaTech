<div
    x-data
    @scroll-to-form.window="$nextTick(() => document.getElementById('user-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' }))"
>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Kelola Pengguna</h1>
            <p class="mt-1 text-sm text-slate-500">Tambah admin, karyawan, sales, dan admin transaksi</p>
        </div>
        <button type="button" wire:click="create" class="admin-btn admin-btn--primary inline-flex rounded-lg bg-brand-primary px-4 py-2 text-sm font-semibold text-white">+ Tambah Pengguna</button>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
        @foreach(['staff' => 'Tim Internal', 'customer' => 'Pelanggan', 'all' => 'Semua'] as $key => $label)
            <button type="button" wire:click="$set('roleFilter', '{{ $key }}')"
                @class(['rounded-lg px-4 py-2 text-sm font-medium transition', 'bg-brand-primary text-white' => $roleFilter === $key, 'bg-white text-slate-600 shadow-sm ring-1 ring-slate-200' => $roleFilter !== $key])>
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if($showForm)
        <form id="user-form" wire:submit="save" class="admin-form admin-panel mt-6 scroll-mt-6 space-y-4 ring-2 ring-brand-primary/30">
            <h2 class="admin-form-section__title">{{ $editingId ? 'Edit Pengguna' : 'Pengguna Baru' }}</h2>
            @if($editingId)
                <p class="text-sm text-slate-600">Isi <strong>Password</strong> di bawah jika ingin mengganti password login.</p>
            @endif
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Nama *</label>
                    <input wire:model="name" class="admin-input">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="admin-label">Email *</label>
                    <input wire:model="email" type="email" class="admin-input">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="admin-label">Telepon</label>
                    <input wire:model="phone" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Role *</label>
                    <select wire:model="role" class="admin-input">
                        @foreach($assignableRoles as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="admin-label">Status</label>
                    <select wire:model="status" class="admin-input">
                        @foreach($statuses as $s)
                            <option value="{{ $s->value }}">{{ ucfirst($s->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="admin-label">Password {{ $editingId ? '(kosongkan jika tidak diubah)' : '*' }}</label>
                    <input wire:model="password" type="password" class="admin-input" autocomplete="new-password">
                    @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="admin-btn admin-btn--success inline-flex rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
                <button type="button" wire:click="$set('showForm', false)" class="admin-btn admin-btn--ghost inline-flex rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-600">Batal</button>
            </div>
        </form>
    @endif

    <div class="admin-table-wrap mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="admin-table w-full border-collapse text-sm">
            <thead class="bg-slate-50 text-left">
                <tr>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Nama</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Email</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Role</th>
                    <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            @foreach($user->roles as $r)
                                <span class="admin-badge admin-badge--blue">{{ $roleLabels[$r->name] ?? $r->name }}</span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3 capitalize">{{ $user->status->value ?? $user->status }}</td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" wire:click="edit({{ $user->id }})" wire:loading.attr="disabled" wire:target="edit({{ $user->id }})" class="admin-btn admin-btn--primary admin-btn--sm inline-flex rounded-lg bg-brand-primary px-3 py-1.5 text-xs font-semibold text-white disabled:opacity-60">Edit</button>
                            @if($user->id !== auth()->id())
                                <button type="button" wire:click="delete({{ $user->id }})" wire:confirm="Hapus pengguna ini?" class="admin-btn admin-btn--danger admin-btn--sm ml-1 inline-flex rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white">Hapus</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">Belum ada pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
</div>
