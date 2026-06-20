<article class="group overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
    @if($template->live_demo_link)
        <a href="{{ $template->live_demo_link }}" target="_blank" rel="noopener noreferrer">
            <x-template-preview :template="$template" wrapper-class="h-44" />
            <div class="p-5">
                <p class="text-xs font-medium uppercase text-brand-primary">{{ $template->category->name ?? 'Website' }}</p>
                <h3 class="mt-1 font-display text-lg font-semibold text-slate-900 group-hover:text-brand-primary">{{ $template->name }}</h3>
                <p class="mt-2 text-sm text-slate-600 line-clamp-2">{{ $template->short_description }}</p>
                <p class="mt-3 font-bold text-brand-accent">
                    Rp {{ number_format($template->sale_price ?? $template->price, 0, ',', '.') }}
                    @if($template->sale_price)
                        <span class="text-sm font-normal text-slate-400 line-through">Rp {{ number_format($template->price, 0, ',', '.') }}</span>
                    @endif
                </p>
            </div>
        </a>
    @else
        <a href="{{ route('templates.show', $template->slug) }}">
            <x-template-preview :template="$template" wrapper-class="h-44" />
            <div class="p-5">
                <p class="text-xs font-medium uppercase text-brand-primary">{{ $template->category->name ?? 'Website' }}</p>
                <h3 class="mt-1 font-display text-lg font-semibold text-slate-900 group-hover:text-brand-primary">{{ $template->name }}</h3>
                <p class="mt-2 text-sm text-slate-600 line-clamp-2">{{ $template->short_description }}</p>
                <p class="mt-3 font-bold text-brand-accent">
                    Rp {{ number_format($template->sale_price ?? $template->price, 0, ',', '.') }}
                    @if($template->sale_price)
                        <span class="text-sm font-normal text-slate-400 line-through">Rp {{ number_format($template->price, 0, ',', '.') }}</span>
                    @endif
                </p>
            </div>
        </a>
    @endif
</article>
