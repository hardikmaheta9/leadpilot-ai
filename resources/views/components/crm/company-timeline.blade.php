<div class="lp-contacts-card">
    <div class="mb-4">
        <h5 class="mb-1">Company Timeline</h5>
        <small class="text-muted">Complete history of company interactions.</small>
    </div>

    @php
        $items = collect();

        foreach($notes as $note){
            $items->push([
                'date' => $note->created_at,
                'icon' => 'fa-note-sticky',
                'title' => 'Note Added',
                'text' => $note->note,
            ]);
        }

        foreach($tasks as $task){
            $items->push([
                'date' => $task->created_at,
                'icon' => 'fa-list-check',
                'title' => 'Task: '.$task->title,
                'text' => $task->status.' | Priority: '.$task->priority,
            ]);
        }

        foreach($meetings as $meeting){
            $items->push([
                'date' => $meeting->created_at,
                'icon' => 'fa-calendar-days',
                'title' => 'Meeting: '.$meeting->title,
                'text' => $meeting->meeting_date->format('d M Y').' '.$meeting->start_time,
            ]);
        }

        foreach($calls as $call){
            $items->push([
                'date' => $call->created_at,
                'icon' => 'fa-phone',
                'title' => 'Call: '.$call->subject,
                'text' => ucfirst($call->call_type).' | '.$call->duration.' min',
            ]);
        }

        foreach($documents as $document){
            $items->push([
                'date' => $document->created_at,
                'icon' => 'fa-file',
                'title' => 'Document Uploaded: '.$document->title,
                'text' => $document->category,
            ]);
        }

        $items = $items->sortByDesc('date');
    @endphp

    @forelse($items as $item)
        <div class="lp-timeline-item">
            <div class="lp-timeline-dot">
                <i class="fa-solid {{ $item['icon'] }}"></i>
            </div>

            <div>
                <strong>{{ $item['title'] }}</strong>
                <p class="mb-1 text-muted">{{ $item['text'] }}</p>
                <small class="text-muted">{{ $item['date']->diffForHumans() }}</small>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="fa-solid fa-clock-rotate-left fa-3x text-muted mb-3"></i>
            <h5>No Timeline Yet</h5>
            <p class="text-muted">Company activities will appear here.</p>
        </div>
    @endforelse
</div>