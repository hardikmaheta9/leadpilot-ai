@extends('layouts.crm')

@section('content')
    <x-page-header
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
    <x-card>
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
                                <x-status-badge :status="$company->status" />
                            </td>

                            <td>
                                <a href="{{ route('companies.show', $company->uuid) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="{{ route('companies.edit', $company->uuid) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form action="{{ route('companies.destroy', $company->uuid) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete {{ addslashes($company->company_name) }}?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <h5>No companies found</h5>
                                <p class="text-muted">Start by adding your first prospect company.</p>
                                <a href="{{ route('companies.create') }}" class="btn btn-primary">
                                    Add Company
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $companies->links() }}
        </div>
    </x-card>
@endsection