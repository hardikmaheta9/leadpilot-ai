@props([
    'icon' => 'fa-regular fa-folder-open',
    'title' => 'Nothing here yet',
    'message' => '',
    'buttonText' => null,
    'buttonTarget' => null,
    'buttonUrl' => null,
    'buttonColor' => 'primary',
])

<div class="lp-empty-state">

    <div class="lp-empty-icon">
        <i class="{{ $icon }}"></i>
    </div>

    <h3 class="lp-empty-title">
        {{ $title }}
    </h3>

    <p class="lp-empty-message">
        {{ $message }}
    </p>

    @if($buttonTarget)

        <button
            type="button"
            class="lp-btn lp-btn-{{ $buttonColor }}"
            data-bs-toggle="modal"
            data-bs-target="{{ $buttonTarget }}">

            <i class="fa-solid fa-plus me-2"></i>

            {{ $buttonText }}

        </button>

    @elseif($buttonUrl)

        <a
            href="{{ $buttonUrl }}"
            class="lp-btn lp-btn-{{ $buttonColor }}">

            <i class="fa-solid fa-plus me-2"></i>

            {{ $buttonText }}

        </a>

    @endif

</div>