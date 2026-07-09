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
    <x-crm.company-tabs.overview :company="$company" />
@endif

{{-- ===========================
    ACTIVITIES
============================ --}}

@if($activeTab === 'activities')
    <x-crm.company-tabs.activities :activities="$activities" />
@endif

{{-- ===========================
    NOTES
============================ --}}

@if($activeTab === 'notes')
    <x-crm.company-tabs.notes
        :company="$company"
        :notes="$notes"
    />
@endif

{{-- ===========================
    Contact
============================ --}}

@if($activeTab === 'contacts')
    <x-crm.company-tabs.contacts
        :company="$company"
        :contacts="$contacts"
    />
@endif


{{-- ===========================
    document
============================ --}}

@if($activeTab === 'documents')
    <x-crm.company-tabs.documents
        :company="$company"
        :documents="$documents"
    />
@endif

{{-- ===========================
    Tasks
============================ --}}

@if($activeTab === 'tasks')
    <x-crm.company-tabs.tasks
        :company="$company"
        :tasks="$tasks"
    />
@endif

{{-- ===========================
    AI Insight
============================ --}}

@if($activeTab === 'ai')

<x-cards.card>

    <h4>AI Insights</h4>

    <p class="text-muted">

        AI Company Analysis coming soon.

    </p>

</x-cards.card>

@endif

@endsection