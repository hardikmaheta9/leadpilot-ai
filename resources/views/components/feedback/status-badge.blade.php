@props(['status'])

<span class="lp-badge lp-badge-{{ $status }}">
    {{ ucfirst($status) }}
</span>