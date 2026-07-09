<div class="lp-contacts-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="mb-1">Company Contacts</h5>
            <small class="text-muted">People connected with this company</small>
        </div>
    </div>

    <form method="POST" action="{{ route('companies.contacts.store', $company->uuid) }}" class="mb-4">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">First Name <span class="text-danger">*</span></label>
                <input name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Last Name</label>
                <input name="last_name" class="form-control" value="{{ old('last_name') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control" value="{{ old('email') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Phone</label>
                <input name="phone" class="form-control" value="{{ old('phone') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Designation</label>
                <input name="designation" class="form-control" value="{{ old('designation') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Department</label>
                <input name="department" class="form-control" value="{{ old('department') }}">
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            </div>
        </div>

        <button class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Add Contact
        </button>
    </form>

    <hr>

    <div class="row">
        @forelse($contacts as $contact)
            <div class="col-md-6 mb-3">
                <div class="lp-contact-card">
                    <div class="d-flex gap-3">
                        <div class="lp-contact-avatar">
                            {{ strtoupper(substr($contact->first_name, 0, 1)) }}
                        </div>

                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $contact->full_name }}</h6>
                            <small class="text-muted">{{ $contact->designation ?? 'Designation not set' }}</small>

                            <div class="mt-3">
                                <div class="mb-2">
                                    <i class="fa-solid fa-envelope me-2 text-muted"></i>
                                    {{ $contact->email ?? '-' }}
                                </div>

                                <div>
                                    <i class="fa-solid fa-phone me-2 text-muted"></i>
                                    {{ $contact->phone ?? '-' }}
                                </div>
                            </div>

                            <div class="mt-3 d-flex gap-2">
                                @if($contact->email)
                                    <a href="mailto:{{ $contact->email }}" class="btn btn-sm btn-outline-primary">
                                        Email
                                    </a>
                                @endif

                                @if($contact->phone)
                                    <a href="tel:{{ $contact->phone }}" class="btn btn-sm btn-outline-secondary">
                                        Call
                                    </a>
                                @endif

                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                    Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="fa-solid fa-address-book fa-3x text-muted mb-3"></i>
                <h6>No Contacts Yet</h6>
                <p class="text-muted">Add your first contact above.</p>
            </div>
        @endforelse
    </div>
</div>