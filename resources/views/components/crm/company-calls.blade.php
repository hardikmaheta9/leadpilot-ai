<div class="lp-module-card">

    <div class="lp-module-header">
        <div>
            <span class="lp-module-eyebrow">Communication</span>
            <h4>Company Calls</h4>
            <p>Record incoming and outgoing calls with this company.</p>
        </div>

        <button
            type="button"
            class="lp-btn lp-btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addCallModal">

            <i class="fa-solid fa-phone-plus"></i>
            Add Call
        </button>
    </div>

    <div class="lp-module-body">

        <div class="row g-4">

            @forelse($calls as $call)

                @php
                    $isIncoming = $call->call_type === 'incoming';

                    $callTypeClass = $isIncoming
                        ? 'lp-call-type-incoming'
                        : 'lp-call-type-outgoing';

                    $statusClass = match($call->status) {
                        'completed' => 'lp-call-status-completed',
                        'missed' => 'lp-call-status-missed',
                        'scheduled' => 'lp-call-status-scheduled',
                        default => 'lp-call-status-default',
                    };
                @endphp

                <div class="col-xl-4 col-md-6">

                    <div class="lp-premium-call-card">

                        <div class="lp-call-card-top">

                            <div class="lp-call-title-area">

                                <div class="lp-call-icon {{ $callTypeClass }}">
                                    <i class="fa-solid {{ $isIncoming ? 'fa-phone-arrow-down-left' : 'fa-phone-arrow-up-right' }}"></i>
                                </div>

                                <div>
                                    <h5>{{ $call->subject }}</h5>

                                    <div class="lp-call-badges">

                                        <span class="lp-call-type-badge {{ $callTypeClass }}">
                                            {{ ucfirst($call->call_type) }}
                                        </span>

                                        <span class="lp-call-status {{ $statusClass }}">
                                            {{ ucfirst($call->status) }}
                                        </span>

                                    </div>
                                </div>

                            </div>

                            <x-ui.action-menu>

                                <button
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCallModal{{ $call->id }}">

                                    <i class="fa-solid fa-pen"></i>
                                    Edit Call
                                </button>

                                <div class="dropdown-divider"></div>

                                <form
                                    method="POST"
                                    action="{{ route('companies.calls.destroy', [$company->uuid, $call->uuid]) }}"
                                    data-confirm-delete
                                    data-confirm-message="Delete this call log? This action cannot be undone.">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-danger">
                                        <i class="fa-solid fa-trash"></i>
                                        Delete Call
                                    </button>
                                </form>

                            </x-ui.action-menu>

                        </div>

                        <div class="lp-call-meta">

                            <div class="lp-call-meta-item">

                                <span class="lp-call-meta-icon lp-call-meta-date">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </span>

                                <div>
                                    <small>Call Date</small>
                                    <strong>{{ $call->call_date->format('d M Y') }}</strong>
                                </div>

                            </div>

                            <div class="lp-call-meta-item">

                                <span class="lp-call-meta-icon lp-call-meta-time">
                                    <i class="fa-solid fa-clock"></i>
                                </span>

                                <div>
                                    <small>Call Time</small>
                                    <strong>
                                        {{ \Carbon\Carbon::parse($call->call_time)->format('h:i A') }}
                                    </strong>
                                </div>

                            </div>

                            <div class="lp-call-meta-item">

                                <span class="lp-call-meta-icon lp-call-meta-duration">
                                    <i class="fa-solid fa-stopwatch"></i>
                                </span>

                                <div>
                                    <small>Duration</small>
                                    <strong>{{ $call->duration ?? 0 }} min</strong>
                                </div>

                            </div>

                        </div>

                        <div class="lp-call-content">

                            <div class="lp-call-section">
                                <small>Outcome</small>
                                <p>{{ $call->outcome ?: 'No outcome recorded.' }}</p>
                            </div>

                            <div class="lp-call-section">
                                <small>Notes</small>
                                <p>{{ $call->notes ?: 'No notes recorded.' }}</p>
                            </div>

                        </div>

                        <div class="lp-call-card-footer">

                            <button
                                type="button"
                                class="lp-call-action-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#editCallModal{{ $call->id }}">

                                <i class="fa-solid fa-pen"></i>
                                Edit
                            </button>

                            <form
                                method="POST"
                                action="{{ route('companies.calls.destroy', [$company->uuid, $call->uuid]) }}"
                                data-confirm-delete
                                data-confirm-message="Delete this call log? This action cannot be undone.">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="lp-call-action-btn lp-call-delete-btn">

                                    <i class="fa-solid fa-trash"></i>
                                    Delete
                                </button>
                            </form>

                        </div>

                    </div>

                </div>

                <x-ui.modal
                    id="editCallModal{{ $call->id }}"
                    title="Edit Call">

                    <form
                        method="POST"
                        action="{{ route('companies.calls.update', [$company->uuid, $call->uuid]) }}">

                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <div class="col-md-7">
                                <label class="form-label">
                                    Subject <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="subject"
                                    class="form-control"
                                    value="{{ $call->subject }}"
                                    required>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label">Call Type</label>

                                <select name="call_type" class="form-select">
                                    <option value="outgoing" @selected($call->call_type === 'outgoing')>
                                        Outgoing
                                    </option>

                                    <option value="incoming" @selected($call->call_type === 'incoming')>
                                        Incoming
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Call Date</label>

                                <input
                                    type="date"
                                    name="call_date"
                                    class="form-control"
                                    value="{{ $call->call_date->format('Y-m-d') }}"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Call Time</label>

                                <input
                                    type="time"
                                    name="call_time"
                                    class="form-control"
                                    value="{{ substr($call->call_time, 0, 5) }}"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Duration</label>

                                <input
                                    type="number"
                                    name="duration"
                                    class="form-control"
                                    value="{{ $call->duration }}"
                                    min="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>

                                <select name="status" class="form-select">
                                    <option value="completed" @selected($call->status === 'completed')>
                                        Completed
                                    </option>

                                    <option value="missed" @selected($call->status === 'missed')>
                                        Missed
                                    </option>

                                    <option value="scheduled" @selected($call->status === 'scheduled')>
                                        Scheduled
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Outcome</label>

                                <input
                                    type="text"
                                    name="outcome"
                                    class="form-control"
                                    value="{{ $call->outcome }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>

                                <textarea
                                    name="notes"
                                    class="form-control"
                                    rows="4">{{ $call->notes }}</textarea>
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

            @empty

                <div class="col-12">

                    <x-ui.empty-state
                        icon="fa-solid fa-phone-volume"
                        title="No Calls Logged"
                        subtitle="Add the first incoming or outgoing call for this company."
                    />

                </div>

            @endforelse

        </div>

    </div>

</div>

<x-ui.modal id="addCallModal" title="Add New Call">

    <form
        method="POST"
        action="{{ route('companies.calls.store', $company->uuid) }}">

        @csrf

        <div class="row g-3">

            <div class="col-md-7">
                <label class="form-label">
                    Subject <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="subject"
                    class="form-control"
                    value="{{ old('subject') }}"
                    required>
            </div>

            <div class="col-md-5">
                <label class="form-label">Call Type</label>

                <select name="call_type" class="form-select">
                    <option value="outgoing" selected>Outgoing</option>
                    <option value="incoming">Incoming</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">
                    Call Date <span class="text-danger">*</span>
                </label>

                <input
                    type="date"
                    name="call_date"
                    class="form-control"
                    value="{{ old('call_date', now()->format('Y-m-d')) }}"
                    required>
            </div>

            <div class="col-md-4">
                <label class="form-label">
                    Call Time <span class="text-danger">*</span>
                </label>

                <input
                    type="time"
                    name="call_time"
                    class="form-control"
                    value="{{ old('call_time', now()->format('H:i')) }}"
                    required>
            </div>

            <div class="col-md-4">
                <label class="form-label">Duration</label>

                <input
                    type="number"
                    name="duration"
                    class="form-control"
                    value="{{ old('duration', 0) }}"
                    min="0">
            </div>

            <div class="col-md-6">
                <label class="form-label">Status</label>

                <select name="status" class="form-select">
                    <option value="completed" selected>Completed</option>
                    <option value="missed">Missed</option>
                    <option value="scheduled">Scheduled</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Outcome</label>

                <input
                    type="text"
                    name="outcome"
                    class="form-control"
                    value="{{ old('outcome') }}"
                    placeholder="Interested, No answer, Follow-up required">
            </div>

            <div class="col-12">
                <label class="form-label">Notes</label>

                <textarea
                    name="notes"
                    class="form-control"
                    rows="4">{{ old('notes') }}</textarea>
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
                <i class="fa-solid fa-phone-plus"></i>
                Add Call
            </button>

        </div>

    </form>

</x-ui.modal>   