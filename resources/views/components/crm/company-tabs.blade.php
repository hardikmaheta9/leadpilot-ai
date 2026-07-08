@props(['company', 'activeTab' => 'overview'])

@php
    $tabs = [
        'overview' => ['label' => 'Overview', 'icon' => 'fa-solid fa-circle-info'],
        'activities' => ['label' => 'Activities', 'icon' => 'fa-solid fa-clock-rotate-left'],
        'contacts' => ['label' => 'Contacts', 'icon' => 'fa-solid fa-address-book'],
        'notes' => ['label' => 'Notes', 'icon' => 'fa-solid fa-note-sticky'],
        'documents' => ['label' => 'Documents', 'icon' => 'fa-solid fa-folder-open'],
        'ai' => ['label' => 'AI Insights', 'icon' => 'fa-solid fa-wand-magic-sparkles'],
        'tasks' => ['label' => 'Tasks', 'icon' => 'fa-solid fa-list-check'],
    ];
@endphp

<div class="lp-company-tabs mb-4">
    @foreach($tabs as $key => $tab)
        <a href="{{ route('companies.show', $company->uuid) }}?tab={{ $key }}"
           class="{{ $activeTab === $key ? 'active' : '' }}">
            <i class="{{ $tab['icon'] }} me-1"></i> {{ $tab['label'] }}
        </a>
    @endforeach
</div>