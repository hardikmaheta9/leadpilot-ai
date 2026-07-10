@props([
    'title',
    'value' => 0,
    'icon' => 'fa-solid fa-chart-line',
    'color' => 'blue',
    'growth' => '+0',
    'route' => null,
])

@php
    $numericValue = is_numeric($value) ? (int) $value : 0;
@endphp

<div class="col-xl-3 col-lg-6 mb-4">

    <div class="lp-stat-card lp-stat-card-{{ $color }}">

        <div class="d-flex justify-content-between align-items-start">

            <div>

                <div class="lp-stat-title">
                    {{ $title }}
                </div>

                <div class="lp-stat-number">
                    <span
                        data-counter="{{ $numericValue }}"
                        data-counter-value="{{ $numericValue }}">

                        {{ number_format($numericValue) }}
                    </span>
                </div>

                <div class="lp-stat-footer">

                    <span class="lp-stat-growth">
                        <i class="fa-solid fa-arrow-trend-up me-1"></i>
                        {{ $growth }}
                    </span>

                </div>

            </div>

            <div class="lp-stat-icon lp-stat-icon-{{ $color }}">
                <i class="{{ $icon }}"></i>
            </div>

        </div>

        @if($route)
            <div class="mt-4">

                <a href="{{ $route }}" class="lp-card-link">
                    View Details
                    <i class="fa-solid fa-arrow-right ms-1"></i>
                </a>

            </div>
        @endif

    </div>

</div>