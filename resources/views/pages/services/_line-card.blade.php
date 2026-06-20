@props(['service', 'active' => false])

<a href="{{ route('services.show', $service->slug) }}"
   @class(['service-line-card group h-full', 'service-line-card--active' => $active])>
    @if($service->cover_image_url)
        <img src="{{ $service->cover_image_url }}" alt="{{ $service->name }}" class="service-line-card__cover" loading="lazy" decoding="async">
    @endif
    <div class="service-line-card__body">
        <span class="service-line-card__icon">
            @include('partials.icons.service-icon', ['name' => $service->category?->icon ?? 'app'])
        </span>
        @if($service->audience)
            <p class="service-line-card__audience">{{ $service->audience }}</p>
        @endif
        <h3 class="service-line-card__title">{{ $service->name }}</h3>
        <p class="service-line-card__desc">{{ $service->short_description }}</p>
        <div class="service-line-card__footer">
            <span class="service-line-card__link-label">Lihat layanan</span>
            <span class="service-line-card__arrow">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M7 7h10v10"/></svg>
            </span>
        </div>
    </div>
</a>
