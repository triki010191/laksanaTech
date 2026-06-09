<div>
    <div class="mb-6">
        <h1 class="font-display text-2xl font-bold text-slate-900">Pesan & Leads</h1>
        <p class="mt-1 text-sm text-slate-500">Balas langsung jika pengirim sudah punya akun</p>
    </div>

    @if($viewing)
        <div class="admin-panel mb-6">
            <div class="admin-panel__head">
                <h2 class="admin-panel__title">Balas: {{ $viewing->name }}</h2>
                <button wire:click="closeReply" class="admin-btn admin-btn--ghost admin-btn--sm">Tutup</button>
            </div>
            <div class="p-5">
                <div class="rounded-xl bg-slate-50 p-4 text-sm">
                    <p class="font-medium">{{ $viewing->subject ?? 'Tanpa subjek' }}</p>
                    <p class="mt-2 text-slate-700">{{ $viewing->message }}</p>
                    <p class="mt-2 text-xs text-slate-500">{{ $viewing->email }} · {{ $viewing->phone ?? '-' }}</p>
                    @if($viewing->user)
                        <span class="admin-badge admin-badge--green mt-2 inline-block">Akun terdaftar: {{ $viewing->user->name }}</span>
                    @else
                        <span class="admin-badge admin-badge--slate mt-2 inline-block">Belum punya akun</span>
                    @endif
                </div>

                @if($viewing->replies->isNotEmpty())
                    <ul class="mt-4 space-y-3">
                        @foreach($viewing->replies as $reply)
                            <li @class(['rounded-xl p-3 text-sm', $reply->is_staff ? 'bg-brand-primary/5 border border-brand-primary/20 ml-4' : 'bg-white border border-slate-100 mr-4'])>
                                <p class="text-xs font-medium text-slate-500">{{ $reply->user->name ?? 'Staff' }} · {{ $reply->created_at->format('d M H:i') }}</p>
                                <p class="mt-1">{{ $reply->message }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif

                <form wire:submit="sendReply" class="admin-form mt-4">
                    <label class="admin-label">Balasan Anda</label>
                    <textarea wire:model="replyMessage" rows="3" class="admin-input" placeholder="Tulis balasan..."></textarea>
                    @error('replyMessage')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    <button type="submit" class="admin-btn admin-btn--primary mt-3">Kirim Balasan</button>
                </form>
            </div>
        </div>
    @endif

    <div class="space-y-4">
        @forelse($leads as $lead)
            <div class="admin-panel">
                <div class="flex flex-wrap items-start justify-between gap-3 p-5">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="font-semibold text-slate-900">{{ $lead->name }}</p>
                            <x-admin.status-badge :status="$lead->status" type="lead" />
                            @if($lead->user_id)
                                <span class="admin-badge admin-badge--blue text-[10px]">User terdaftar</span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-600">{{ $lead->email }} · {{ $lead->phone ?? '-' }}</p>
                        @if($lead->subject)<p class="mt-2 text-sm font-medium">{{ $lead->subject }}</p>@endif
                        <p class="mt-1 text-sm text-slate-700">{{ \Illuminate\Support\Str::limit($lead->message, 200) }}</p>
                        <p class="mt-2 text-xs text-slate-400">{{ $lead->created_at->format('d M Y H:i') }} · {{ $lead->source }}</p>
                    </div>
                    <div class="flex flex-col gap-2">
                        <select wire:change="updateStatus({{ $lead->id }}, $event.target.value)" class="admin-input admin-input--sm">
                            @foreach(['new','contacted','qualified','converted','closed'] as $s)
                                <option value="{{ $s }}" @selected($lead->status === $s)>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                        <button wire:click="openReply({{ $lead->id }})" class="admin-btn admin-btn--primary admin-btn--sm">Balas</button>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-slate-500">Belum ada lead.</p>
        @endforelse
    </div>
    {{ $leads->links() }}
</div>
