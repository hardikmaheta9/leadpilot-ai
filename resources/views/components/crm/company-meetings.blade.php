<div class="lp-contacts-card">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1">Meetings</h5>
            <small class="text-muted">
                Schedule and manage company meetings.
            </small>
        </div>
    </div>

    <form method="POST"
          action="{{ route('companies.meetings.store',$company->uuid) }}">

        @csrf

        <div class="row">

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Meeting Title
                </label>

                <input
                    name="title"
                    class="form-control"
                    required>

            </div>

            <div class="col-md-3 mb-3">

                <label class="form-label">
                    Date
                </label>

                <input
                    type="date"
                    name="meeting_date"
                    class="form-control"
                    required>

            </div>

            <div class="col-md-3 mb-3">

                <label class="form-label">
                    Meeting Type
                </label>

                <select
                    name="meeting_type"
                    class="form-select">

                    <option value="in_person">In Person</option>
                    <option value="google_meet">Google Meet</option>
                    <option value="zoom">Zoom</option>
                    <option value="teams">Microsoft Teams</option>
                    <option value="phone">Phone</option>

                </select>

            </div>

            <div class="col-md-3 mb-3">

                <label class="form-label">
                    Start Time
                </label>

                <input
                    type="time"
                    name="start_time"
                    class="form-control"
                    required>

            </div>

            <div class="col-md-3 mb-3">

                <label class="form-label">
                    End Time
                </label>

                <input
                    type="time"
                    name="end_time"
                    class="form-control">

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Location / Meeting Link
                </label>

                <input
                    name="location"
                    class="form-control"
                    placeholder="Office / Google Meet URL / Zoom URL">

            </div>

            <div class="col-12 mb-3">

                <label class="form-label">
                    Agenda
                </label>

                <textarea
                    name="agenda"
                    class="form-control"
                    rows="3"></textarea>

            </div>

        </div>

        <button class="btn btn-primary">

            <i class="fa-solid fa-calendar-plus me-2"></i>

            Schedule Meeting

        </button>

    </form>

    <hr class="my-4">

    @forelse($meetings as $meeting)

        @php
            $isPast = $meeting->meeting_date->isPast() && $meeting->status != 'completed';
        @endphp

        <div class="lp-meeting-card {{ $meeting->status=='completed' ? 'opacity-75' : '' }}">

            <div class="d-flex justify-content-between align-items-start">

                <div>

                    <h5 class="mb-1 {{ $meeting->status=='completed' ? 'text-decoration-line-through' : '' }}">

                        {{ $meeting->title }}

                    </h5>

                    <small class="{{ $isPast ? 'text-danger fw-bold' : 'text-muted' }}">

                        {{ $meeting->meeting_date->format('d M Y') }}

                        •

                        {{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}

                    </small>

                </div>

                <div>

                    <span class="badge bg-primary">

                        {{ ucwords(str_replace('_',' ',$meeting->meeting_type)) }}

                    </span>

                </div>

            </div>

            @if($meeting->location)

                <div class="mt-3">

                    <i class="fa-solid fa-location-dot me-2"></i>

                    {{ $meeting->location }}

                </div>

            @endif

            @if($meeting->agenda)

                <p class="mt-3 mb-3">

                    {{ $meeting->agenda }}

                </p>

            @endif

            <div class="d-flex gap-2 mt-3">

                @if($meeting->status!='completed')

                <form
                    method="POST"
                    action="{{ route('companies.meetings.complete',[$company->uuid,$meeting->uuid]) }}">

                    @csrf
                    @method('PATCH')

                    <button class="btn btn-sm btn-outline-success">

                        Complete

                    </button>

                </form>

                @endif

                <button
                    class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="collapse"
                    data-bs-target="#editMeeting{{ $meeting->id }}">

                    Edit

                </button>

                <form
                    method="POST"
                    action="{{ route('companies.meetings.destroy',[$company->uuid,$meeting->uuid]) }}"
                    onsubmit="return confirm('Delete meeting?')">

                    @csrf
                    @method('DELETE')

                    <button class="btn btn-sm btn-outline-danger">

                        Delete

                    </button>

                </form>

            </div>

            <div
                class="collapse mt-3"
                id="editMeeting{{ $meeting->id }}">

                <form
                    method="POST"
                    action="{{ route('companies.meetings.update',[$company->uuid,$meeting->uuid]) }}">

                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="col-md-6 mb-2">

                            <input
                                name="title"
                                class="form-control"
                                value="{{ $meeting->title }}"
                                required>

                        </div>

                        <div class="col-md-3 mb-2">

                            <input
                                type="date"
                                name="meeting_date"
                                class="form-control"
                                value="{{ $meeting->meeting_date->format('Y-m-d') }}">

                        </div>

                        <div class="col-md-3 mb-2">

                            <input
                                type="time"
                                name="start_time"
                                class="form-control"
                                value="{{ substr($meeting->start_time,0,5) }}">

                        </div>

                        <div class="col-12 mb-2">

                            <textarea
                                name="agenda"
                                class="form-control"
                                rows="3">{{ $meeting->agenda }}</textarea>

                        </div>

                    </div>

                    <button class="btn btn-primary btn-sm">

                        Save Changes

                    </button>

                </form>

            </div>

        </div>

    @empty

        <div class="text-center py-5">

            <i class="fa-solid fa-calendar-days fa-3x text-muted mb-3"></i>

            <h5>No Meetings Scheduled</h5>

            <p class="text-muted">

                Schedule your first meeting.

            </p>

        </div>

    @endforelse

</div>