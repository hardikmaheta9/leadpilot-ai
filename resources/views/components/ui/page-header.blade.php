@props([
    'title',
    'subtitle' => null,
    'buttonText' => null,
    'buttonLink' => null,
    'buttonIcon' => 'fa-solid fa-plus'
])

<div class="lp-page-header mb-4">

    <div>
        <h1>{{ $title }}</h1>

        @if($subtitle)
            <p>{{ $subtitle }}</p>
        @endif
    </div>

    @if($buttonText)

        <a href="{{ $buttonLink }}"
           class="lp-btn lp-btn-primary">

            <i class="{{ $buttonIcon }} me-2"></i>

            {{ $buttonText }}

        </a>

    @endif

</div>