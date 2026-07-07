@csrf

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Company Name <span class="text-danger">*</span></label>
        <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
               value="{{ old('company_name', $company->company_name ?? '') }}" required>
        @error('company_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Website</label>
        <input type="url" name="website" class="form-control" value="{{ old('website', $company->website ?? '') }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $company->email ?? '') }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" value="{{ old('phone', $company->phone ?? '') }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Industry</label>
        <input type="text" name="industry" class="form-control" value="{{ old('industry', $company->industry ?? '') }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach(['prospect', 'qualified', 'customer', 'inactive', 'blacklisted'] as $status)
                <option value="{{ $status }}" @selected(old('status', $company->status ?? 'prospect') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-4">
    <button class="btn btn-primary">
        <i class="fa-solid fa-floppy-disk me-1"></i> Save Company
    </button>

    <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary">
        Cancel
    </a>
</div>