@props(['method', 'reversed' => false])

@php
    $steps = $method['steps'];
@endphp

<div class="tutorial-steps-panel">
    <div class="tutorial-steps-panel__header">
        <p class="text-card-title">{{ $method['panel_title'] }}</p>
        <span class="text-brand-primary">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
        </span>
    </div>
    <div class="tutorial-steps-grid">
        @foreach($steps as $step)
            <div class="tutorial-step">
                <div class="tutorial-step__icon-wrap">
                    @include('partials.tutorial.step-icon', ['icon' => $step['icon']])
                    <span class="tutorial-step__num">{{ $step['num'] }}</span>
                </div>
                <p class="tutorial-step__title">{{ $step['title'] }}</p>
            </div>
        @endforeach
    </div>
</div>
