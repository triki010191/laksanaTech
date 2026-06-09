<div>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-900">Pesanan</h1>
            <p class="mt-1 text-sm text-slate-500">Alur: masuk → konfirmasi → progres pengerjaan → selesai</p>
        </div>
        <select wire:model.live="statusFilter" class="admin-input w-auto min-w-[10rem]">
            <option value="">Semua status</option>
            @foreach($statuses as $s)
                <option value="{{ $s->value }}">{{ ucfirst(str_replace('_', ' ', $s->value)) }}</option>
            @endforeach
        </select>
    </div>

    {{-- Pipeline overview --}}
    <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
        @foreach([
            ['pending', 'Masuk', 'amber'],
            ['confirmed', 'Dikonfirmasi', 'blue'],
            ['in_progress', 'Progres', 'purple'],
            ['completed', 'Selesai', 'green'],
        ] as [$key, $label, $color])
            <button type="button" wire:click="$set('statusFilter', '{{ $key }}')"
                @class(['admin-pipeline-chip', "admin-pipeline-chip--{$color}", 'admin-pipeline-chip--active' => $statusFilter === $key])>
                {{ $label }}
            </button>
        @endforeach
    </div>

    @if($viewing)
        <div class="admin-panel mt-6">
            <div class="admin-panel__head">
                <div>
                    <h2 class="admin-panel__title">{{ $viewing->order_number }}</h2>
                    <x-admin.status-badge :status="$viewing->status->value" type="order" class="mt-1" />
                </div>
                <button wire:click="closeView" class="admin-btn admin-btn--ghost admin-btn--sm">Tutup</button>
            </div>

            <div class="p-5">
                {{-- Workflow steps --}}
                <ol class="admin-workflow mb-6">
                    @foreach([
                        ['pending', '1. Pesanan Masuk'],
                        ['confirmed', '2. Dikonfirmasi'],
                        ['in_progress', '3. Dikerjakan'],
                        ['completed', '4. Selesai'],
                    ] as [$stepKey, $stepLabel])
                        @php
                            $orderIdx = array_search($viewing->status->value, ['pending','confirmed','in_progress','completed'], true);
                            $stepIdx = array_search($stepKey, ['pending','confirmed','in_progress','completed'], true);
                            $done = $orderIdx !== false && $stepIdx !== false && $orderIdx >= $stepIdx;
                            $active = $viewing->status->value === $stepKey;
                        @endphp
                        <li @class(['admin-workflow__step', 'admin-workflow__step--done' => $done, 'admin-workflow__step--active' => $active])>
                            {{ $stepLabel }}
                        </li>
                    @endforeach
                </ol>

                <dl class="grid gap-3 text-sm sm:grid-cols-2 lg:grid-cols-3">
                    <div><dt class="text-slate-500">Pelanggan</dt><dd class="font-medium">{{ $viewing->user->name }} ({{ $viewing->user->email }})</dd></div>
                    <div><dt class="text-slate-500">Domain</dt><dd class="font-medium">{{ $viewing->metadata['full_domain'] ?? '-' }}</dd></div>
                    <div><dt class="text-slate-500">Total</dt><dd class="font-bold text-brand-primary">Rp {{ number_format($viewing->total, 0, ',', '.') }}</dd></div>
                    <div><dt class="text-slate-500">Estimasi siap</dt><dd>{{ $viewing->estimated_ready_at?->format('d M Y H:i') ?? '-' }}</dd></div>
                    <div><dt class="text-slate-500">Custom / Revisi</dt><dd>{{ $viewing->metadata['with_editing_addon'] ?? false ? 'Ya (editing)' : 'Standar' }}</dd></div>
                </dl>

                <ul class="mt-4 rounded-xl bg-slate-50 p-4 text-sm">
                    @foreach($viewing->items as $item)
                        <li class="py-0.5">• {{ $item->name }} — Rp {{ number_format($item->total_price, 0, ',', '.') }}</li>
                    @endforeach
                </ul>

                {{-- Quick actions --}}
                <div class="mt-4 flex flex-wrap gap-2">
                    @if($viewing->status->value === 'pending')
                        <button wire:click="acceptOrder({{ $viewing->id }})" class="admin-btn admin-btn--success admin-btn--sm">✓ Terima Pesanan</button>
                    @endif
                    @if($viewing->status->value === 'confirmed')
                        <button wire:click="startWork({{ $viewing->id }})" class="admin-btn admin-btn--primary admin-btn--sm">▶ Mulai Pengerjaan</button>
                    @endif
                    @if(in_array($viewing->status->value, ['confirmed', 'in_progress'], true))
                        <button wire:click="updateStatus({{ $viewing->id }}, 'completed')" class="admin-btn admin-btn--success admin-btn--sm">✓ Tandai Selesai</button>
                    @endif
                    @if(! in_array($viewing->status->value, ['completed', 'cancelled', 'refunded'], true))
                        <button wire:click="updateStatus({{ $viewing->id }}, 'cancelled')" wire:confirm="Batalkan pesanan?" class="admin-btn admin-btn--danger admin-btn--sm">Batal</button>
                    @endif
                </div>

                {{-- Work progress --}}
                @if(in_array($viewing->status->value, ['confirmed', 'in_progress', 'completed'], true))
                    <form wire:submit="saveWorkProgress" class="admin-form mt-6 border-t border-slate-100 pt-6">
                        <h3 class="text-sm font-semibold text-slate-800">Progres Pengerjaan (Freelancer / Tim)</h3>
                        <div class="mt-4 grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="admin-label">Tahap</label>
                                <select wire:model="workStage" class="admin-input">
                                    @foreach($workStages as $stage)
                                        <option value="{{ $stage->value }}">{{ $stage->label() }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="admin-label">Ditugaskan ke</label>
                                <select wire:model="assignedTo" class="admin-input">
                                    <option value="">— Pilih —</option>
                                    @foreach($freelancers as $f)
                                        <option value="{{ $f->id }}">{{ $f->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="admin-label">Progres: {{ $workProgress }}%</label>
                            <input type="range" wire:model.live="workProgress" min="0" max="100" class="mt-2 w-full accent-brand-primary">
                            <div class="admin-progress-bar mt-2">
                                <div class="admin-progress-bar__fill" style="width: {{ $workProgress }}%"></div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="admin-label">Catatan tim</label>
                            <textarea wire:model="workNotes" rows="2" class="admin-input" placeholder="Update desain, koding, testing..."></textarea>
                        </div>
                        <div class="mt-4">
                            <label class="admin-label">Permintaan revisi / custom dari konsumen</label>
                            <textarea wire:model="revisionNotes" rows="2" class="admin-input" placeholder="Catat permintaan khusus pelanggan"></textarea>
                        </div>
                        <button type="submit" class="admin-btn admin-btn--primary mt-4">Simpan Progres</button>
                    </form>
                @endif
            </div>
        </div>
    @endif

    <div class="admin-table-wrap mt-6">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Domain</th>
                    <th>Status</th>
                    <th>Progres</th>
                    <th>Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="font-medium">{{ $order->order_number }}</td>
                        <td>{{ $order->user->name ?? '-' }}</td>
                        <td>{{ $order->metadata['full_domain'] ?? '-' }}</td>
                        <td><x-admin.status-badge :status="$order->status->value" type="order" /></td>
                        <td>
                            @if(isset($order->metadata['work_progress']))
                                <span class="text-xs font-medium text-brand-primary">{{ $order->metadata['work_progress'] }}%</span>
                            @else
                                <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                        <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td>
                            <button wire:click="view({{ $order->id }})" class="admin-btn admin-btn--primary admin-btn--sm">Detail</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-10 text-center text-slate-500">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
</div>
