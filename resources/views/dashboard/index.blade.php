@extends('layouts.crm')

@section('content')

<x-page-header
    title="Dashboard"
    subtitle="Your LeadPilot AI business development command center."
/>

<div class="row">
    <x-dashboard.stat-card
        title="Total Companies"
        :value="$totalCompanies"
        icon="fa-solid fa-building"
    />

    <x-dashboard.stat-card
        title="Prospects"
        :value="$prospectCompanies"
        icon="fa-solid fa-bullseye"
    />

    <x-dashboard.stat-card
        title="Qualified"
        :value="$qualifiedCompanies"
        icon="fa-solid fa-circle-check"
    />

    <x-dashboard.stat-card
        title="Customers"
        :value="$customerCompanies"
        icon="fa-solid fa-handshake"
    />
</div>

<div class="row">
    <div class="col-md-8">
        <x-card>
            <h5 class="mb-3">Recent Companies</h5>

            @forelse($recentCompanies as $company)
                <div class="d-flex justify-content-between border-bottom py-2">
                    <div>
                        <strong>{{ $company->company_name }}</strong>
                        <br>
                        <small class="text-muted">{{ $company->industry ?? '-' }}</small>
                    </div>

                    <x-status-badge :status="$company->status" />
                </div>
            @empty
                <p class="text-muted mb-0">No companies found.</p>
            @endforelse
        </x-card>
    </div>

    <div class="col-md-4">
        <x-card>
            <h5 class="mb-3">AI Suggestions</h5>

            <ul class="mb-0">
                <li>Add more company details for better AI analysis.</li>
                <li>Start by adding prospect companies.</li>
                <li>Next: connect contacts with companies.</li>
            </ul>
        </x-card>
    </div>
    <div class="row mt-4">
        
    <div class="col-md-12">
        <x-card>
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
        </x-card>
    </div>
</div>
</div>

@endsection