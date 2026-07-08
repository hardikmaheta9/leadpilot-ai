@extends('layouts.crm')

@section('content')

<x-crm.company-header :company="$company" />

<x-crm.company-tabs
    :company="$company"
    :active-tab="$activeTab"
/>

{{-- ===========================
    OVERVIEW
============================ --}}
@if($activeTab === 'overview')

<div class="row">

    <div class="col-lg-8">

        <x-cards.card>

            <h5 class="mb-4">Company Information</h5>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <strong>Website</strong>
                    <p>{{ $company->website ?: '-' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Email</strong>
                    <p>{{ $company->email ?: '-' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Phone</strong>
                    <p>{{ $company->phone ?: '-' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Industry</strong>
                    <p>{{ $company->industry ?: '-' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>City</strong>
                    <p>{{ $company->city ?: '-' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Status</strong><br>

                    <x-feedback.status-badge
                        :status="$company->status"
                    />

                </div>

            </div>

        </x-cards.card>

    </div>

    <div class="col-lg-4">

        <x-cards.card>

            <h5>Company Health</h5>

            <hr>

            <div class="mb-3">
                Website {{ $company->website ? '✅' : '❌' }}
            </div>

            <div class="mb-3">
                Email {{ $company->email ? '✅' : '❌' }}
            </div>

            <div>
                Phone {{ $company->phone ? '✅' : '❌' }}
            </div>

        </x-cards.card>

    </div>

</div>

@endif

{{-- ===========================
    ACTIVITIES
============================ --}}

@if($activeTab === 'activities')

<x-crm.activity-timeline
    :activities="$activities"
/>

@endif

{{-- ===========================
    NOTES
============================ --}}

@if($activeTab === 'notes')

<x-crm.company-notes
    :company="$company"
    :notes="$notes"
/>

@endif

{{-- ===========================
    PLACEHOLDERS
============================ --}}

@if($activeTab === 'contacts')

<x-cards.card>

    <h4>Contacts</h4>

    <p class="text-muted">

        Contacts module coming in Sprint 4.

    </p>

</x-cards.card>

@endif

@if($activeTab === 'documents')

<x-cards.card>

    <h4>Documents</h4>

    <p class="text-muted">

        Documents module coming in Sprint 5.

    </p>

</x-cards.card>

@endif

@if($activeTab === 'tasks')

<x-cards.card>

    <h4>Tasks</h4>

    <p class="text-muted">

        Tasks module coming in Sprint 6.

    </p>

</x-cards.card>

@endif

@if($activeTab === 'ai')

<x-cards.card>

    <h4>AI Insights</h4>

    <p class="text-muted">

        AI Company Analysis coming soon.

    </p>

</x-cards.card>

@endif

@endsection