@php
    $items = collect();

    foreach ($notes as $note) {
        $items->push([
            'date' => $note->created_at,
            'type' => 'note',
            'icon' => 'fa-solid fa-note-sticky',
            'title' => 'Note Added',
            'text' => trim($note->note),
            'meta' => 'Knowledge update',
        ]);
    }

    foreach ($tasks as $task) {
        $items->push([
            'date' => $task->created_at,
            'type' => 'task',
            'icon' => 'fa-solid fa-list-check',
            'title' => 'Task: ' . $task->title,
            'text' => ucfirst(str_replace('_', ' ', $task->status))
                . ' · ' . ucfirst($task->priority) . ' priority',
            'meta' => $task->due_date
                ? 'Due ' . $task->due_date->format('d M Y')
                : 'No due date',
        ]);
    }

    foreach ($meetings as $meeting) {
        $items->push([
            'date' => $meeting->created_at,
            'type' => 'meeting',
            'icon' => 'fa-solid fa-calendar-days',
            'title' => 'Meeting: ' . $meeting->title,
            'text' => $meeting->meeting_date->format('d M Y')
                . ' · '
                . \Carbon\Carbon::parse($meeting->start_time)->format('h:i A'),
            'meta' => ucwords(str_replace('_', ' ', $meeting->meeting_type)),
        ]);
    }

    foreach ($calls as $call) {
        $items->push([
            'date' => $call->created_at,
            'type' => 'call',
            'icon' => 'fa-solid fa-phone',
            'title' => 'Call: ' . $call->subject,
            'text' => ucfirst($call->call_type)
                . ' · '
                . ($call->duration ?? 0)
                . ' min',
            'meta' => ucfirst($call->status ?? 'logged'),
        ]);
    }

    foreach ($documents as $document) {
        $items->push([
            'date' => $document->created_at,
            'type' => 'document',
            'icon' => 'fa-solid fa-file-lines',
            'title' => 'Document Uploaded: ' . $document->title,
            'text' => $document->original_filename,
            'meta' => $document->category ?: 'General',
        ]);
    }

    $items = $items->sortByDesc('date')->values();
@endphp

<div class="lp-module-card">

    <div class="lp-module-header">
        <div>
            <span class="lp-module-eyebrow">History</span>
            <h4>Company Timeline</h4>
            <p>Complete chronological history of company interactions.</p>
        </div>

        <div class="lp-timeline-count">
            <i class="fa-solid fa-clock-rotate-left"></i>
            {{ $items->count() }} Activities
        </div>
    </div>

    <div class="lp-module-body">

        @forelse($items as $item)

            <div class="lp-premium-timeline-item">

                <div class="lp-timeline-marker">

                    <div class="lp-timeline-icon lp-timeline-{{ $item['type'] }}">
                        <i class="{{ $item['icon'] }}"></i>
                    </div>

                    <div class="lp-timeline-line"></div>

                </div>

                <div class="lp-timeline-content">

                    <div class="lp-timeline-content-top">

                        <div>
                            <span class="lp-timeline-type">
                                {{ ucfirst($item['type']) }}
                            </span>

                            <h5>{{ $item['title'] }}</h5>
                        </div>

                        <div class="lp-timeline-time">
                            <strong>{{ $item['date']->format('d M Y') }}</strong>
                            <small>{{ $item['date']->format('h:i A') }}</small>
                        </div>

                    </div>

                    <p>{{ $item['text'] }}</p>

                    <div class="lp-timeline-footer">

                        <span>
                            <i class="fa-solid fa-circle-info"></i>
                            {{ $item['meta'] }}
                        </span>

                        <span>
                            <i class="fa-regular fa-clock"></i>
                            {{ $item['date']->diffForHumans() }}
                        </span>

                    </div>

                </div>

            </div>

        @empty

            <x-ui.empty-state
                icon="fa-solid fa-clock-rotate-left"
                title="No Timeline Yet"
                message="Notes, tasks, meetings, calls and document uploads will appear here automatically as your team works with this company."
                buttonText="Add First Activity"
                buttonUrl="{{ route('companies.show', [$company->uuid, 'tab' => 'activities']) }}"
            />

        @endforelse

    </div>

</div>