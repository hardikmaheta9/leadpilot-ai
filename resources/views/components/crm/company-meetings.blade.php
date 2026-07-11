<div class="lp-module-card">

    <div class="lp-module-header">
        <div>
            <span class="lp-module-eyebrow">Schedule</span>
            <h4>Company Meetings</h4>
            <p>Schedule, manage and complete meetings with this company.</p>
        </div>

        <button
            type="button"
            class="lp-btn lp-btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addMeetingModal">

            <i class="fa-solid fa-calendar-plus"></i>
            Schedule Meeting
        </button>
    </div>

    <div class="lp-module-body">

        <div class="row g-4">

            @forelse($meetings as $meeting)

                @php
                    $isCompleted = $meeting->status === 'completed';

                    $isPast = $meeting->meeting_date->isPast()
                        && !$isCompleted;

                    $isToday = $meeting->meeting_date->isToday();

                    $meetingTypeClass = match($meeting->meeting_type) {
                        'google_meet' => 'lp-meeting-type-google',
                        'zoom' => 'lp-meeting-type-zoom',
                        'teams' => 'lp-meeting-type-teams',
                        'phone' => 'lp-meeting-type-phone',
                        default => 'lp-meeting-type-person',
                    };

                    $meetingIcon = match($meeting->meeting_type) {
                        'google_meet' => 'fa-solid fa-video',
                        'zoom' => 'fa-solid fa-video',
                        'teams' => 'fa-solid fa-users',
                        'phone' => 'fa-solid fa-phone',
                        default => 'fa-solid fa-handshake',
                    };
                @endphp

                <div class="col-xl-4 col-md-6">

                    <div class="lp-premium-meeting-card {{ $isCompleted ? 'lp-meeting-completed' : '' }}">

                        <div class="lp-meeting-card-top">

                            <div class="lp-meeting-title-area">

                                <div class="lp-meeting-icon {{ $meetingTypeClass }}">
                                    <i class="{{ $meetingIcon }}"></i>
                                </div>

                                <div>
                                    <h5 class="{{ $isCompleted ? 'text-decoration-line-through' : '' }}">
                                        {{ $meeting->title }}
                                    </h5>

                                    <div class="lp-meeting-badges">

                                        <span class="lp-meeting-type-badge {{ $meetingTypeClass }}">
                                            {{ ucwords(str_replace('_', ' ', $meeting->meeting_type)) }}
                                        </span>

                                        @if($isCompleted)
                                            <span class="lp-meeting-status lp-meeting-status-completed">
                                                Completed
                                            </span>
                                        @elseif($isPast)
                                            <span class="lp-meeting-status lp-meeting-status-overdue">
                                                Overdue
                                            </span>
                                        @elseif($isToday)
                                            <span class="lp-meeting-status lp-meeting-status-today">
                                                Today
                                            </span>
                                        @else
                                            <span class="lp-meeting-status lp-meeting-status-scheduled">
                                                Scheduled
                                            </span>
                                        @endif

                                    </div>
                                </div>

                            </div>

                            <x-ui.action-menu>

                                <button
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editMeetingModal{{ $meeting->id }}">

                                    <i class="fa-solid fa-pen"></i>
                                    Edit Meeting
                                </button>

                                @if(!$isCompleted)
                                    <form
                                        method="POST"
                                        action="{{ route('companies.meetings.complete', [$company->uuid, $meeting->uuid]) }}">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit">
                                            <i class="fa-solid fa-check"></i>
                                            Mark Complete
                                        </button>
                                    </form>
                                @endif

                                @if($meeting->location)
                                    @if(filter_var($meeting->location, FILTER_VALIDATE_URL))
                                        <a
                                            href="{{ $meeting->location }}"
                                            target="_blank"
                                            rel="noopener noreferrer">

                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            Open Meeting Link
                                        </a>
                                    @endif
                                @endif

                                <div class="dropdown-divider"></div>

                                <form
                                    method="POST"
                                    action="{{ route('companies.meetings.destroy', [$company->uuid, $meeting->uuid]) }}"
                                    data-confirm-delete
                                    data-confirm-message="Delete this meeting? This action cannot be undone.">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-danger">
                                        <i class="fa-solid fa-trash"></i>
                                        Delete Meeting
                                    </button>
                                </form>

                            </x-ui.action-menu>

                        </div>

                        <div class="lp-meeting-schedule">

                            <div class="lp-meeting-date-box">
                                <span>{{ $meeting->meeting_date->format('M') }}</span>
                                <strong>{{ $meeting->meeting_date->format('d') }}</strong>
                            </div>

                            <div>
                                <small>Date and Time</small>

                                <strong>
                                    {{ $meeting->meeting_date->format('d M Y') }}
                                </strong>

                                <p>
                                    {{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}

                                    @if($meeting->end_time)
                                        –
                                        {{ \Carbon\Carbon::parse($meeting->end_time)->format('h:i A') }}
                                    @endif
                                </p>
                            </div>

                        </div>

                        @if($meeting->location)
                            <div class="lp-meeting-location">

                                <span>
                                    <i class="fa-solid fa-location-dot"></i>
                                </span>

                                <div>
                                    <small>Location / Link</small>

                                    @if(filter_var($meeting->location, FILTER_VALIDATE_URL))
                                        <a
                                            href="{{ $meeting->location }}"
                                            target="_blank"
                                            rel="noopener noreferrer">

                                            Join Meeting
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    @else
                                        <strong>{{ $meeting->location }}</strong>
                                    @endif
                                </div>

                            </div>
                        @endif

                        <div class="lp-meeting-agenda">

                            <small>Agenda</small>

                            <p>
                                {{ $meeting->agenda ?: 'No agenda provided.' }}
                            </p>

                        </div>

                        <div class="lp-meeting-card-footer">

                            @if(!$isCompleted)
                                <form
                                    method="POST"
                                    action="{{ route('companies.meetings.complete', [$company->uuid, $meeting->uuid]) }}">

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="lp-meeting-action-btn lp-meeting-complete-btn">

                                        <i class="fa-solid fa-check"></i>
                                        Complete
                                    </button>
                                </form>
                            @endif

                            <button
                                type="button"
                                class="lp-meeting-action-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#editMeetingModal{{ $meeting->id }}">

                                <i class="fa-solid fa-pen"></i>
                                Edit
                            </button>

                            <form
                                method="POST"
                                action="{{ route('companies.meetings.destroy', [$company->uuid, $meeting->uuid]) }}"
                                    data-confirm-delete
                                    data-confirm-message="Delete this meeting? This action cannot be undone.">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="lp-meeting-action-btn lp-meeting-delete-btn">

                                    <i class="fa-solid fa-trash"></i>
                                    Delete
                                </button>
                            </form>

                        </div>

                    </div>

                </div>
            @empty

                <div class="col-12">

                        <x-ui.empty-state
                            icon="fa-solid fa-calendar-days"
                            title="No Meetings Scheduled"
                            message="Schedule client meetings, demos and follow-ups to keep every opportunity moving."
                            buttonText="Schedule Meeting"
                            buttonTarget="#addMeetingModal"
                        />

                    </div>

            @endforelse

        </div>

    </div>

</div>


{-- Edit Meeting Modals: kept outside the animated module card --}
@foreach($meetings as $meeting)

<x-ui.modal
                    id="editMeetingModal{{ $meeting->id }}"
                    title="Edit Meeting">

                    <form
                        method="POST"
                        action="{{ route('companies.meetings.update', [$company->uuid, $meeting->uuid]) }}">

                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <div class="col-md-7">
                                <label class="form-label">
                                    Meeting Title <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="title"
                                    class="form-control"
                                    value="{{ $meeting->title }}"
                                    required>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label">Meeting Type</label>

                                <select name="meeting_type" class="form-select">
                                    <option value="in_person" @selected($meeting->meeting_type === 'in_person')>
                                        In Person
                                    </option>

                                    <option value="google_meet" @selected($meeting->meeting_type === 'google_meet')>
                                        Google Meet
                                    </option>

                                    <option value="zoom" @selected($meeting->meeting_type === 'zoom')>
                                        Zoom
                                    </option>

                                    <option value="teams" @selected($meeting->meeting_type === 'teams')>
                                        Microsoft Teams
                                    </option>

                                    <option value="phone" @selected($meeting->meeting_type === 'phone')>
                                        Phone
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Meeting Date</label>

                                <input
                                    type="date"
                                    name="meeting_date"
                                    class="form-control"
                                    value="{{ $meeting->meeting_date->format('Y-m-d') }}"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Start Time</label>

                                <input
                                    type="time"
                                    name="start_time"
                                    class="form-control"
                                    value="{{ substr($meeting->start_time, 0, 5) }}"
                                    required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">End Time</label>

                                <input
                                    type="time"
                                    name="end_time"
                                    class="form-control"
                                    value="{{ $meeting->end_time ? substr($meeting->end_time, 0, 5) : '' }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Location / Meeting Link</label>

                                <input
                                    type="text"
                                    name="location"
                                    class="form-control"
                                    value="{{ $meeting->location }}"
                                    placeholder="Office address or online meeting URL">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Agenda</label>

                                <textarea
                                    name="agenda"
                                    class="form-control"
                                    rows="4">{{ $meeting->agenda }}</textarea>
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


<x-ui.modal id="addMeetingModal" title="Schedule New Meeting">

    <form
        method="POST"
        action="{{ route('companies.meetings.store', $company->uuid) }}">

        @csrf

        <div class="row g-3">

            <div class="col-md-7">
                <label class="form-label">
                    Meeting Title <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title') }}"
                    required>
            </div>

            <div class="col-md-5">
                <label class="form-label">Meeting Type</label>

                <select name="meeting_type" class="form-select">
                    <option value="in_person">In Person</option>
                    <option value="google_meet">Google Meet</option>
                    <option value="zoom">Zoom</option>
                    <option value="teams">Microsoft Teams</option>
                    <option value="phone">Phone</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">
                    Meeting Date <span class="text-danger">*</span>
                </label>

                <input
                    type="date"
                    name="meeting_date"
                    class="form-control"
                    value="{{ old('meeting_date') }}"
                    required>
            </div>

            <div class="col-md-4">
                <label class="form-label">
                    Start Time <span class="text-danger">*</span>
                </label>

                <input
                    type="time"
                    name="start_time"
                    class="form-control"
                    value="{{ old('start_time') }}"
                    required>
            </div>

            <div class="col-md-4">
                <label class="form-label">End Time</label>

                <input
                    type="time"
                    name="end_time"
                    class="form-control"
                    value="{{ old('end_time') }}">
            </div>

            <div class="col-12">
                <label class="form-label">Location / Meeting Link</label>

                <input
                    type="text"
                    name="location"
                    class="form-control"
                    value="{{ old('location') }}"
                    placeholder="Office address or online meeting URL">
            </div>

            <div class="col-12">
                <label class="form-label">Agenda</label>

                <textarea
                    name="agenda"
                    class="form-control"
                    rows="4">{{ old('agenda') }}</textarea>
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
                <i class="fa-solid fa-calendar-plus"></i>
                Schedule Meeting
            </button>

        </div>

    </form>

</x-ui.modal>