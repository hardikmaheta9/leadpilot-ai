@props([
'title',
'icon'=>null
])

<div class="lp-section-card">

<div class="lp-section-header">

<div>

@if($icon)

<i class="{{ $icon }} me-2"></i>

@endif

{{ $title }}

</div>

</div>

<div class="lp-section-body">

{{ $slot }}

</div>

</div>