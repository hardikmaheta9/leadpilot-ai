@extends('layouts.crm')

@section('content')

<x-dashboard.welcome-banner />

<div class="row">

    <x-dashboard.stat-card
        title="Companies"
        :value="$totalCompanies"
        icon="fa-solid fa-building"
        growth="+2 This Week"
        :route="route('companies.index')"
    />

    <x-dashboard.stat-card
        title="Prospects"
        :value="$prospectCompanies"
        icon="fa-solid fa-bullseye"
        growth="+0"
    />

    <x-dashboard.stat-card
        title="Qualified"
        :value="$qualifiedCompanies"
        icon="fa-solid fa-circle-check"
        growth="+0"
    />

    <x-dashboard.stat-card
        title="Customers"
        :value="$customerCompanies"
        icon="fa-solid fa-handshake"
        growth="+0"
    />

</div>

<div class="row">
    <div class="col-md-8">
        <x-cards.card>
            <h5 class="mb-3">Recent Companies</h5>

            @forelse($recentCompanies as $company)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <div>
                        <strong>{{ $company->company_name }}</strong>
                        <br>
                        <small class="text-muted">{{ $company->industry ?? '-' }}</small>
                    </div>

                    <x-feedback.status-badge :status="$company->status" />
                </div>
            @empty
                <p class="text-muted mb-0">No companies found.</p>
            @endforelse
        </x-cards.card>
    </div>

    <div class="col-md-4">
        <x-dashboard.ai-assistant
            :totalCompanies="$totalCompanies"
            :prospectCompanies="$prospectCompanies"
            :qualifiedCompanies="$qualifiedCompanies"
        />
    </div>

    <div class="row mt-4">
    <div class="col-md-12">
        <x-dashboard.quick-actions />
    </div>
    </div>

    <div class="row mt-4">
        
    <div class="col-md-12">
        <x-cards.card>
            <h5 class="mb-3">Recent Activity</h5>

            @forelse($recentActivities as $activity)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <div>
                        <strong>{{ $activity->description }}</strong>
                        <br>
                        <small class="text-muted">
                            {{ $activity->module }} · {{ ucfirst($activity->action) }}
                        </small>
                    </div>

                    <small class="text-muted">
                        {{ $activity->created_at->diffForHumans() }}
                    </small>
                </div>
            @empty
                <p class="text-muted mb-0">No recent activities found.</p>
            @endforelse
        </x-cards.card>
    </div>
</div>
</div>

@endsection