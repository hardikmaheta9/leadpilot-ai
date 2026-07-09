@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
    'trend' => '+12%',
    'label' => 'This month'
])

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
            <h3 data-count="{{ $value }}">0</h3>
            <p>{{ $title }}</p>
            <small>{{ $label }}</small>
        </div>

        <div class="lp-stat-sparkline"></div>
    </div>
</div>