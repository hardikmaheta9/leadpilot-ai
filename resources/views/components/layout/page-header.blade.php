<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="lp-page-title">{{ $title }}</h2>

        @if(!empty($subtitle))
            <p class="text-muted mb-0">{{ $subtitle }}</p>
        @endif
    </div>

    @if(!empty($actionUrl) && !empty($actionLabel))
        <a href="{{ $actionUrl }}" class="btn btn-primary">
            @if(!empty($actionIcon))
                <i class="{{ $actionIcon }} me-1"></i>
            @endif

            {{ $actionLabel }}
        </a>
    @endif
</div>