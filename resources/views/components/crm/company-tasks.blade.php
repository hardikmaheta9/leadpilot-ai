<div class="lp-module-card">

    <div class="lp-module-header">
        <div>
            <span class="lp-module-eyebrow">Work Items</span>
            <h4>Company Tasks</h4>
            <p>Manage follow-ups, reminders and company-related work.</p>
        </div>

        <button
            type="button"
            class="lp-btn lp-btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addTaskModal">

            <i class="fa-solid fa-plus"></i>
            Add Task
        </button>
    </div>

    <div class="lp-module-body">

        <div class="row g-4">

            @forelse($tasks as $task)

                @php
                    $isCompleted = $task->status === 'completed';

                    $isOverdue = $task->due_date
                        && $task->due_date->isPast()
                        && !$isCompleted;

                    $priorityClass = match($task->priority) {
                        'high' => 'lp-task-priority-high',
                        'medium' => 'lp-task-priority-medium',
                        'low' => 'lp-task-priority-low',
                        default => 'lp-task-priority-default',
                    };

                    $statusClass = match($task->status) {
                        'completed' => 'lp-task-status-completed',
                        'in_progress' => 'lp-task-status-progress',
                        default => 'lp-task-status-pending',
                    };
                @endphp

                <div class="col-xl-4 col-md-6">

                    <div class="lp-premium-task-card {{ $isCompleted ? 'lp-task-completed' : '' }}">

                        <div class="lp-task-card-top">

                            <div class="lp-task-title-area">

                                <div class="lp-task-icon {{ $isCompleted ? 'lp-task-icon-completed' : '' }}">
                                    <i class="fa-solid {{ $isCompleted ? 'fa-check' : 'fa-list-check' }}"></i>
                                </div>

                                <div>
                                    <h5 class="{{ $isCompleted ? 'text-decoration-line-through' : '' }}">
                                        {{ $task->title }}
                                    </h5>

                                    <div class="lp-task-badges">
                                        <span class="lp-task-priority {{ $priorityClass }}">
                                            {{ ucfirst($task->priority) }} Priority
                                        </span>

                                        <span class="lp-task-status {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </div>
                                </div>

                            </div>

                            <x-ui.action-menu>

                                <button
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editTaskModal{{ $task->id }}">

                                    <i class="fa-solid fa-pen"></i>
                                    Edit Task
                                </button>

                                @if(!$isCompleted)
                                    <form
                                        method="POST"
                                        action="{{ route('companies.tasks.complete', [$company->uuid, $task->uuid]) }}">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit">
                                            <i class="fa-solid fa-check"></i>
                                            Mark Complete
                                        </button>
                                    </form>
                                @endif

                                <div class="dropdown-divider"></div>

                                <form
                                    method="POST"
                                    action="{{ route('companies.tasks.destroy', [$company->uuid, $task->uuid]) }}"
                                    data-confirm-delete
                                    data-confirm-message="Delete this task? This action cannot be undone.">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-danger">
                                        <i class="fa-solid fa-trash"></i>
                                        Delete Task
                                    </button>
                                </form>

                            </x-ui.action-menu>

                        </div>

                        <div class="lp-task-content">

                            @if($task->description)
                                <p>{{ $task->description }}</p>
                            @else
                                <p class="text-muted">No description provided.</p>
                            @endif

                        </div>

                        <div class="lp-task-meta">

                            <div class="lp-task-meta-item {{ $isOverdue ? 'lp-task-overdue' : '' }}">

                                <span>
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>

                                <div>
                                    <small>{{ $isOverdue ? 'Overdue' : 'Due Date' }}</small>

                                    <strong>
                                        {{ $task->due_date
                                            ? $task->due_date->format('d M Y')
                                            : 'No due date' }}
                                    </strong>
                                </div>

                            </div>

                        </div>

                        <div class="lp-task-card-footer">

                            @if(!$isCompleted)
                                <form
                                    method="POST"
                                    action="{{ route('companies.tasks.complete', [$company->uuid, $task->uuid]) }}">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="lp-task-action-btn lp-task-complete-btn">
                                        <i class="fa-solid fa-check"></i>
                                        Complete
                                    </button>
                                </form>
                            @endif

                            <button
                                type="button"
                                class="lp-task-action-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#editTaskModal{{ $task->id }}">

                                <i class="fa-solid fa-pen"></i>
                                Edit
                            </button>

                            <form
                                method="POST"
                                action="{{ route('companies.tasks.destroy', [$company->uuid, $task->uuid]) }}"
                                    data-confirm-delete
                                    data-confirm-message="Delete this task? This action cannot be undone.">

                                @csrf
                                @method('DELETE')

                                <button type="submit" class="lp-task-action-btn lp-task-delete-btn">
                                    <i class="fa-solid fa-trash"></i>
                                    Delete
                                </button>
                            </form>

                        </div>

                    </div>

                </div>            @empty

               <div class="col-12">

                    <x-ui.empty-state
                        icon="fa-solid fa-list-check"
                        title="No Tasks Yet"
                        message="Create follow-ups, reminders and action items so nothing slips through the cracks."
                        buttonText="Create First Task"
                        buttonTarget="#addTaskModal"
                    />

                </div>

            @endforelse

        </div>

    </div>

</div>


{{-- Edit Task Modals --}}
@foreach($tasks as $task)


                <x-ui.modal
                    id="editTaskModal{{ $task->id }}"
                    title="Edit Task">

                    <form
                        method="POST"
                        action="{{ route('companies.tasks.update', [$company->uuid, $task->uuid]) }}">

                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <div class="col-md-7">
                                <label class="form-label">
                                    Task Title <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    value="{{ $task->title }}"
                                    required>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label">Due Date</label>

                                <input
                                    type="date"
                                    name="due_date"
                                    class="form-control"
                                    value="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Priority</label>

                                <select name="priority" class="form-select">
                                    <option value="low" @selected($task->priority === 'low')>
                                        Low
                                    </option>

                                    <option value="medium" @selected($task->priority === 'medium')>
                                        Medium
                                    </option>

                                    <option value="high" @selected($task->priority === 'high')>
                                        High
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>

                                <select name="status" class="form-select">
                                    <option value="pending" @selected($task->status === 'pending')>
                                        Pending
                                    </option>

                                    <option value="in_progress" @selected($task->status === 'in_progress')>
                                        In Progress
                                    </option>

                                    <option value="completed" @selected($task->status === 'completed')>
                                        Completed
                                    </option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>

                                <textarea
                                    name="description"
                                    class="form-control"
                                    rows="4">{{ $task->description }}</textarea>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <button
                                type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">

                                Cancel
                            </button>

                            <button type="submit" class="lp-btn lp-btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Save Changes
                            </button>

                        </div>

                    </form>

                </x-ui.modal>


@endforeach

<x-ui.modal id="addTaskModal" title="Add New Task">

    <form
        method="POST"
        action="{{ route('companies.tasks.store', $company->uuid) }}">

        @csrf

        <div class="row g-3">

            <div class="col-md-7">
                <label class="form-label">
                    Task Title <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title') }}"
                    required>
            </div>

            <div class="col-md-5">
                <label class="form-label">Due Date</label>

                <input
                    type="date"
                    name="due_date"
                    class="form-control"
                    value="{{ old('due_date') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Priority</label>

                <select name="priority" class="form-select">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Status</label>

                <select name="status" class="form-select">
                    <option value="pending" selected>Pending</option>
                    <option value="in_progress">In Progress</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label">Description</label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="4">{{ old('description') }}</textarea>
            </div>

        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">

            <button
                type="button"
                class="btn btn-light"
                data-bs-dismiss="modal">

                Cancel
            </button>

            <button type="submit" class="lp-btn lp-btn-primary">
                <i class="fa-solid fa-plus"></i>
                Add Task
            </button>

        </div>

    </form>

</x-ui.modal>