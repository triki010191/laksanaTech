@props(['eyebrow', 'title', 'lead' => null])

<div class="home-section-header mb-12 grid gap-6 lg:grid-cols-2 lg:items-end">
    <div>
        <span class="home-section-header__eyebrow text-eyebrow">{{ $eyebrow }}</span>
        <h2 class="text-section-title mt-3">{!! $title !!}</h2>
    </div>
    @if($lead)
        <p class="text-section-lead max-w-xl lg:ml-auto">{{ $lead }}</p>
    @endif
</div>
