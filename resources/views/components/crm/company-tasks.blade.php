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
    <div class="lp-task-card {{ $task->status === 'completed' ? 'opacity-75' : '' }}">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="mb-1 {{ $task->status === 'completed' ? 'text-decoration-line-through' : '' }}">
                    {{ $task->title }}
                </h6>

                <small class="text-muted">
                    Due: {{ $task->due_date ? $task->due_date->format('d M Y') : 'No due date' }}
                </small>
            </div>

            <div class="d-flex gap-2">
                <span class="badge bg-secondary">
                    {{ ucfirst($task->priority) }}
                </span>

                <span class="badge {{ $task->status === 'completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ ucfirst($task->status) }}
                </span>
            </div>
        </div>

        @if($task->description)
            <p class="mt-3 mb-3">{{ $task->description }}</p>
        @endif

        <div class="d-flex gap-2 mt-3">
            @if($task->status !== 'completed')
                <form method="POST" action="{{ route('companies.tasks.complete', [$company->uuid, $task->uuid]) }}">
                    @csrf
                    @method('PATCH')

                    <button class="btn btn-sm btn-outline-success">
                        Complete
                    </button>
                </form>
            @endif

            <form method="POST"
                  action="{{ route('companies.tasks.destroy', [$company->uuid, $task->uuid]) }}"
                  onsubmit="return confirm('Delete this task?')">
                @csrf
                @method('DELETE')

                <button class="btn btn-sm btn-outline-danger">
                    Delete
                </button>
            </form>
        </div>
      </div>
      @empty
        <div class="text-center py-5">
            <i class="fa-solid fa-list-check fa-3x text-muted mb-3"></i>
            <h6>No Tasks Yet</h6>
            <p class="text-muted">Add your first follow-up task above.</p>
        </div>
    @endforelse
</div>