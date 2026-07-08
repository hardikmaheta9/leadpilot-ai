<div class="lp-timeline">
    <h5 class="mb-4">Activity Timeline</h5>

    @forelse($activities as $activity)
        <div class="lp-timeline-item">
            <div class="lp-timeline-dot"></div>

            <div>
                <strong>{{ $activity->description }}</strong>
                <br>
                <small class="text-muted">
                    {{ ucfirst($activity->action) }} · {{ $activity->created_at->diffForHumans() }}
                </small>
            </div>
        </div>
    @empty
        <p class="text-muted mb-0">No activities yet.</p>
    @endforelse
</div>