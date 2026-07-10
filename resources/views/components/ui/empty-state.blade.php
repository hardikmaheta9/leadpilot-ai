@props([
'icon',
'title',
'subtitle'
])

<div class="lp-empty-state">

    <div class="lp-empty-icon">

        <i class="{{ $icon }}"></i>

    </div>

    <h5>{{ $title }}</h5>

    <p>{{ $subtitle }}</p>

</div>