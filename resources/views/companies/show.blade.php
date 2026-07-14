@extends('layouts.crm')

@section('content')

<x-crm.company-header
    :company="$company"
    :contacts="$contacts"
    :tasks="$tasks"
    :meetings="$meetings"
    :documents="$documents"
/>

<x-crm.company-tabs
    :company="$company"
    :active-tab="$activeTab"
/>

@if($activeTab === 'overview')
    <x-crm.company-tabs.overview :company="$company" />
@endif

@if($activeTab === 'timeline')
    <x-crm.company-tabs.timeline
        :notes="$notes"
        :tasks="$tasks"
        :meetings="$meetings"
        :calls="$calls"
        :documents="$documents"
    />
@endif

@if($activeTab === 'activities')
    <x-crm.company-tabs.activities :activities="$activities" />
@endif

@if($activeTab === 'notes')
    <x-crm.company-tabs.notes
        :company="$company"
        :notes="$notes"
    />
@endif

@if($activeTab === 'contacts')
    <x-crm.company-tabs.contacts
        :company="$company"
        :contacts="$contacts"
    />
@endif

@if($activeTab === 'documents')
    <x-crm.company-tabs.documents
        :company="$company"
        :documents="$documents"
    />
@endif

@if($activeTab === 'tasks')
    <x-crm.company-tabs.tasks
        :company="$company"
        :tasks="$tasks"
    />
@endif

@if($activeTab === 'meetings')
    <x-crm.company-tabs.meetings
        :company="$company"
        :meetings="$meetings"
    />
@endif

@if($activeTab === 'ai')
    <x-crm.company-tabs.ai
        :company="$company"
        :ai-profile="$aiProfile"
        :website-analysis="$websiteAnalysis"
        :contacts="$contacts"
        :tasks="$tasks"
        :meetings="$meetings"
        :calls="$calls"
        :notes="$notes"
        :documents="$documents"
    />
@endif

@endsection