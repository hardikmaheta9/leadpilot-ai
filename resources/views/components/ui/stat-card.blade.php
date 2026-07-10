@props([
    'title',
    'value' => 0,
    'icon' => 'fa-solid fa-chart-line',
    'color' => 'blue',
    'trend' => '+12%',
    'label' => 'This month',
    'route' => null,
])

@php
    $numericValue = (int) $value;
@endphp

<div class="col-xl-3 col-md-6 mb-4">

    <div class="lp-stat-card lp-stat-card-{{ $color }}">

        <div class="lp-stat-top">

            <div class="lp-stat-icon lp-{{ $color }}">
                <i class="{{ $icon }}"></i>
            </div>

            <div class="lp-stat-trend">
                <i class="fa-solid fa-arrow-trend-up"></i>
                {{ $trend }}
            </div>

        </div>

        <div class="lp-stat-content">

            <h3>
                <span
                    data-counter="{{ $numericValue }}"
                    data-counter-value="{{ $numericValue }}">
                    {{ number_format($numericValue) }}
                </span>
            </h3>

            <p>{{ $title }}</p>

            <small>{{ $label }}</small>

        </div>

        @if($route)

            <div class="mt-3">

                <a href="{{ $route }}" class="lp-card-link">

                    View Details

                    <i class="fa-solid fa-arrow-right ms-1"></i>

                </a>

            </div>

        @endif

        <div class="lp-stat-sparkline"></div>

    </div>

</div>