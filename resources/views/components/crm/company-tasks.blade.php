<div class="lp-contacts-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1">Company Tasks</h5>
            <small class="text-muted">Follow-ups, reminders and work items</small>
        </div>
    </div>

    <form method="POST" action="{{ route('companies.tasks.store', $company->uuid) }}" class="mb-4">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Task Title <span class="text-danger">*</span></label>
                <input name="title" class="form-control" required>
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Due Date</label>
                <input name="due_date" type="date" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Priority</label>
                <select name="priority" class="form-select">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
        </div>

        <button class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Add Task
        </button>
    </form>

    <hr>

    @forelse($tasks as $task)
        @php
            $priorityClass = match($task->priority) {
                'high' => 'bg-danger',
                'medium' => 'bg-warning text-dark',
                'low' => 'bg-success',
                default => 'bg-secondary',
            };

            $isOverdue = $task->due_date
                && $task->due_date->isPast()
                && $task->status !== 'completed';
        @endphp

        <div class="lp-task-card {{ $task->status === 'completed' ? 'opacity-75' : '' }}">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1 {{ $task->status === 'completed' ? 'text-decoration-line-through' : '' }}">
                        {{ $task->title }}
                    </h6>

                    <small class="{{ $isOverdue ? 'text-danger fw-bold' : 'text-muted' }}">
                        @if($isOverdue)
                            ⚠ Overdue:
                        @else
                            Due:
                        @endif

                        {{ $task->due_date ? $task->due_date->format('d M Y') : 'No due date' }}
                    </small>
                </div>

                <div class="d-flex gap-2">
                    <span class="badge {{ $priorityClass }}">
                        {{ ucfirst($task->priority) }}
                    </span>

                    <span class="badge {{ $task->status === 'completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                </div>
            </div>

            @if($task->description)
                <p class="mt-3 mb-3">{{ $task->description }}</p>
            @endif

            <div class="d-flex gap-2 mt-3">

                @if($task->status !== 'completed')
                    <form method="POST"
                        action="{{ route('companies.tasks.complete', [$company->uuid, $task->uuid]) }}">
                        @csrf
                        @method('PATCH')

                        <button class="btn btn-sm btn-outline-success">
                            <i class="fa-solid fa-check"></i>
                            Complete
                        </button>
                    </form>
                @endif

                <button
                    class="btn btn-sm btn-outline-primary"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#editTask{{ $task->id }}">

                    <i class="fa-solid fa-pen"></i>
                    Edit

                </button>

                <form method="POST"
                    action="{{ route('companies.tasks.destroy', [$company->uuid, $task->uuid]) }}"
                    onsubmit="return confirm('Delete this task?')">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-sm btn-outline-danger">
                        <i class="fa-solid fa-trash"></i>
                        Delete
                    </button>
                </form>

                 </div>
            </div>
            <div class="collapse mt-3" id="editTask{{ $task->id }}">

            <form method="POST"
                action="{{ route('companies.tasks.update', [$company->uuid, $task->uuid]) }}">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-2">
                        <input
                            name="title"
                            class="form-control"
                            value="{{ $task->title }}"
                            required>
                    </div>

                    <div class="col-md-3 mb-2">
                        <input
                            type="date"
                            name="due_date"
                            class="form-control"
                            value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                    </div>

                    <div class="col-md-3 mb-2">
                        <select name="priority" class="form-select">

                            <option value="low"
                                @selected($task->priority=='low')>
                                Low
                            </option>

                            <option value="medium"
                                @selected($task->priority=='medium')>
                                Medium
                            </option>

                            <option value="high"
                                @selected($task->priority=='high')>
                                High
                            </option>

                        </select>
                    </div>

                    <div class="col-12 mb-2">
                        <textarea
                            name="description"
                            class="form-control"
                            rows="3">{{ $task->description }}</textarea>
                    </div>

                </div>

                <button class="btn btn-primary btn-sm">
                    Save Changes
                </button>

            </form>

        </div>
        @empty
        <div class="text-center py-5">
            <i class="fa-solid fa-list-check fa-3x text-muted mb-3"></i>
            <h6>No Tasks Yet</h6>
            <p class="text-muted">Add your first follow-up task above.</p>
        </div>
    @endforelse
</div>