<article class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition hover:shadow-md">
    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
        @if($template->preview_image)
            <img src="{{ asset('storage/'.$template->preview_image) }}" alt="{{ $template->name }}" class="h-full w-full object-cover object-top">
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-primary/10 to-brand-secondary/10">
                <span class="text-meta text-brand-primary">{{ $template->category->name ?? 'Website' }}</span>
            </div>
        @endif
    </div>

    <div class="p-4">
        <h3 class="text-card-title line-clamp-2">{{ $template->name }}</h3>
        <p class="text-caption mt-1.5">
            @if($template->downloads_count > 0)
                {{ number_format($template->downloads_count, 0, ',', '.') }} orang memilih ini
            @else
                Template populer
            @endif
        </p>

        <div class="mt-4 flex gap-2">
            @if($template->demo_url)
                <a href="{{ $template->demo_url }}" target="_blank" rel="noopener"
                   class="text-btn inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-heading transition hover:border-slate-300 hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Lihat
                </a>
            @else
                <a href="{{ route('templates.show', $template->slug) }}"
                   class="text-btn inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-heading transition hover:border-slate-300 hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Lihat
                </a>
            @endif
            <a href="{{ route('checkout', ['flow' => 'template_first', 'template' => $template->id]) }}"
               class="text-btn inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-brand-accent px-3 py-2.5 text-white shadow-sm transition hover:opacity-90">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Beli
            </a>
        </div>
    </div>
</article>
