<div class="lp-module-card">

    <div class="lp-module-header">
        <div>
            <span class="lp-module-eyebrow">People</span>
            <h4>Company Contacts</h4>
            <p>Manage decision-makers and people connected with this company.</p>
        </div>

        <button
            type="button"
            class="lp-btn lp-btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addContactModal">

            <i class="fa-solid fa-user-plus"></i>
            Add Contact
        </button>
    </div>

    <div class="lp-module-body">

        <div class="row g-4">

            @forelse($contacts as $contact)

                <div class="col-xl-4 col-md-6">

                    <div class="lp-premium-contact-card">

                        <div class="lp-contact-card-top">

                            <div class="lp-contact-identity">

                                <div class="lp-contact-avatar">
                                    {{ strtoupper(substr($contact->first_name, 0, 1)) }}
                                </div>

                                <div class="lp-contact-name-wrap">
                                    <div class="d-flex align-items-center flex-wrap gap-2">

                                        <h5>{{ $contact->full_name }}</h5>

                                        @if($contact->is_primary)
                                            <span class="lp-primary-contact-badge">
                                                <i class="fa-solid fa-star"></i>
                                                Primary
                                            </span>
                                        @endif

                                    </div>

                                    <p>
                                        {{ $contact->designation ?: 'Designation not set' }}
                                    </p>

                                    @if($contact->department)
                                        <small>{{ $contact->department }}</small>
                                    @endif
                                </div>

                            </div>

                            <x-ui.action-menu>

                                <button
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editContactModal{{ $contact->id }}">

                                    <i class="fa-solid fa-pen"></i>
                                    Edit Contact
                                </button>

                                @if($contact->email)
                                    <a href="mailto:{{ $contact->email }}">
                                        <i class="fa-solid fa-envelope"></i>
                                        Send Email
                                    </a>
                                @endif

                                @if($contact->phone)
                                    <a href="tel:{{ $contact->phone }}">
                                        <i class="fa-solid fa-phone"></i>
                                        Call Contact
                                    </a>
                                @endif

                                <a href="{{ route('companies.show', [$company->uuid, 'tab' => 'meetings']) }}">
                                    <i class="fa-solid fa-calendar-plus"></i>
                                    Schedule Meeting
                                </a>

                                <a href="{{ route('companies.show', [$company->uuid, 'tab' => 'tasks']) }}">
                                    <i class="fa-solid fa-list-check"></i>
                                    Create Task
                                </a>

                                @if(!$contact->is_primary)
                                    <form
                                        method="POST"
                                        action="{{ route('companies.contacts.primary', [$company->uuid, $contact->uuid]) }}">

                                        @csrf
                                        @method('PATCH')

                                        <button type="submit">
                                            <i class="fa-solid fa-star"></i>
                                            Make Primary
                                        </button>
                                    </form>
                                @endif

                                <div class="dropdown-divider"></div>

                                <form
                                    method="POST"
                                    action="{{ route('companies.contacts.destroy', [$company->uuid, $contact->uuid]) }}"
                                    data-confirm-delete
                                    data-confirm-message="Delete this contact? This action cannot be undone.">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-danger">
                                        <i class="fa-solid fa-trash"></i>
                                        Delete Contact
                                    </button>
                                </form>

                            </x-ui.action-menu>

                        </div>

                        <div class="lp-contact-details">

                            <div class="lp-contact-detail">
                                <span class="lp-contact-detail-icon lp-contact-detail-email">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>

                                <div>
                                    <small>Email</small>

                                    @if($contact->email)
                                        <a href="mailto:{{ $contact->email }}">
                                            {{ $contact->email }}
                                        </a>
                                    @else
                                        <strong>Not provided</strong>
                                    @endif
                                </div>
                            </div>

                            <div class="lp-contact-detail">
                                <span class="lp-contact-detail-icon lp-contact-detail-phone">
                                    <i class="fa-solid fa-phone"></i>
                                </span>

                                <div>
                                    <small>Phone</small>

                                    @if($contact->phone)
                                        <a href="tel:{{ $contact->phone }}">
                                            {{ $contact->phone }}
                                        </a>
                                    @else
                                        <strong>Not provided</strong>
                                    @endif
                                </div>
                            </div>

                        </div>

                        @if($contact->notes)
                            <div class="lp-contact-note">
                                <i class="fa-solid fa-note-sticky"></i>
                                <p>{{ $contact->notes }}</p>
                            </div>
                        @endif

<div class="lp-contact-card-footer">

    @if($contact->email)
        <a href="mailto:{{ $contact->email }}" class="lp-contact-quick-btn">
            <i class="fa-solid fa-envelope"></i>
            Email
        </a>
    @endif

    @if($contact->phone)
        <a href="tel:{{ $contact->phone }}" class="lp-contact-quick-btn">
            <i class="fa-solid fa-phone"></i>
            Call
        </a>
    @endif

    <button
        type="button"
        class="lp-contact-quick-btn"
        data-bs-toggle="modal"
        data-bs-target="#editContactModal{{ $contact->id }}">

        <i class="fa-solid fa-pen"></i>
        Edit
    </button>

    <form
        method="POST"
        action="{{ route('companies.contacts.destroy', [$company->uuid, $contact->uuid]) }}"
        class="d-inline"
                                    data-confirm-delete
                                    data-confirm-message="Delete this contact? This action cannot be undone.">

        @csrf
        @method('DELETE')

        <button type="submit" class="lp-contact-quick-btn lp-contact-delete-btn">
            <i class="fa-solid fa-trash"></i>
            Delete
        </button>

    </form>

</div>
{{-- End contact footer --}}

</div>
{{-- End premium contact card --}}

</div>
{{-- End col-xl-4 col-md-6 --}}

                <x-ui.modal
                    id="editContactModal{{ $contact->id }}"
                    title="Edit Contact">

                    <form
                        method="POST"
                        action="{{ route('companies.contacts.update', [$company->uuid, $contact->uuid]) }}">

                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">
                                    First Name <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="first_name"
                                    class="form-control"
                                    value="{{ $contact->first_name }}"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>

                                <input
                                    type="text"
                                    name="last_name"
                                    class="form-control"
                                    value="{{ $contact->last_name }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ $contact->email }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    value="{{ $contact->phone }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Designation</label>

                                <input
                                    type="text"
                                    name="designation"
                                    class="form-control"
                                    value="{{ $contact->designation }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Department</label>

                                <input
                                    type="text"
                                    name="department"
                                    class="form-control"
                                    value="{{ $contact->department }}">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Notes</label>

                                <textarea
                                    name="notes"
                                    class="form-control"
                                    rows="4">{{ $contact->notes }}</textarea>
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
                        icon="fa-solid fa-address-book"
                        title="No Contacts Yet"
                        subtitle="Add the first contact connected with this company."
                    />

                </div>

            @endforelse

        </div>

    </div>

</div>

<x-ui.modal id="addContactModal" title="Add New Contact">

    <form
        method="POST"
        action="{{ route('companies.contacts.store', $company->uuid) }}">

        @csrf

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">
                    First Name <span class="text-danger">*</span>
                </label>

                <input
                    type="text"
                    name="first_name"
                    class="form-control @error('first_name') is-invalid @enderror"
                    value="{{ old('first_name') }}"
                    required>

                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Last Name</label>

                <input
                    type="text"
                    name="last_name"
                    class="form-control"
                    value="{{ old('last_name') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Phone</label>

                <input
                    type="text"
                    name="phone"
                    class="form-control"
                    value="{{ old('phone') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Designation</label>

                <input
                    type="text"
                    name="designation"
                    class="form-control"
                    value="{{ old('designation') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Department</label>

                <input
                    type="text"
                    name="department"
                    class="form-control"
                    value="{{ old('department') }}">
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
                <i class="fa-solid fa-user-plus"></i>
                Add Contact
            </button>

        </div>

    </form>

</x-ui.modal>

@if($errors->has('first_name'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalElement = document.getElementById('addContactModal');

            if (modalElement && typeof bootstrap !== 'undefined') {
                bootstrap.Modal.getOrCreateInstance(modalElement).show();
            }
        });
    </script>
@endif