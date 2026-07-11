<div class="lp-module-card">

    <div class="lp-module-header">

        <div>
            <span class="lp-module-eyebrow">History</span>

            <h4>Activity Timeline</h4>

            <p>Every CRM activity performed for this company appears here automatically.</p>
        </div>

    </div>

    <div class="lp-module-body">

        @forelse($activities as $activity)

            <div class="lp-timeline-item">

                <div class="lp-timeline-dot">

                    <i class="fa-solid fa-clock-rotate-left"></i>

                </div>

                <div class="flex-grow-1">

                    <strong>{{ $activity->description }}</strong>

                    <div class="text-muted small mt-1">

                        {{ ucfirst($activity->action) }}

                        •

                        {{ $activity->created_at->format('d M Y h:i A') }}

                    </div>

                </div>

            </div>

        @empty

            <x-ui.empty-state
                icon="fa-solid fa-clock-rotate-left"
                title="No Activities Yet"
                message="All CRM actions such as notes, meetings, calls, tasks and document uploads will automatically appear here."
            />

        @endforelse

    </div>

</div>