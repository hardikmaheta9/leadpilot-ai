@extends('layouts.crm')

@section('content')
    <x-layout.page-header
        title="Companies"
        subtitle="Manage prospect and customer companies."
        :action-url="route('companies.create')"
        action-label="Add Company"
        action-icon="fa-solid fa-plus"
    />
    <form method="GET" action="{{ route('companies.index') }}" class="mb-3">
    <div class="input-group">
        <input
            type="text"
            name="search"
            class="form-control"
            placeholder="Search companies by name, website, email, industry, city or country..."
            value="{{ $search ?? '' }}"
        >

        <button class="btn btn-primary">
            <i class="fa-solid fa-magnifying-glass me-1"></i> Search
        </button>

        @if(!empty($search))
            <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary">
                Reset
            </a>
        @endif
    </div>
</form>
    <x-cards.card>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Website</th>
                        <th>Email</th>
                        <th>Industry</th>
                        <th>Status</th>
                        <th width="140">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td>
                                <strong>{{ $company->company_name }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ trim(($company->city ?? '') . ' ' . ($company->country ?? '')) ?: '-' }}
                                </small>
                            </td>

                            <td>
                                @if($company->website)
                                    <a href="{{ $company->website }}" target="_blank">
                                        {{ $company->website }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>

                            <td>{{ $company->email ?? '-' }}</td>
                            <td>{{ $company->industry ?? '-' }}</td>

                            <td>
                                <x-feedback.status-badge :status="$company->status" />
                            </td>

                            <td>
                                <a href="{{ route('companies.show', $company->uuid) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="{{ route('companies.edit', $company->uuid) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form method="POST" action="{{ route('companies.destroy', $company->uuid) }}"
                                    data-confirm-delete
                                    data-confirm-message="Delete {{ $company->company_name }}? All related CRM data may also be removed. This action cannot be undone.">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger">

                                        <i class="fa-solid fa-trash"></i>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty

                    <tr>

                        <td colspan="6">

                            <x-ui.empty-state
                                icon="fa-solid fa-building"
                                title="No Companies Found"
                                message="Start building your CRM by adding your first company."
                                buttonText="Add Company"
                                buttonUrl="{{ route('companies.create') }}"
                            />

                        </td>

                    </tr>

                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $companies->links() }}
        </div>
</x-cards.card>
@endsection