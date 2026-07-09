@props(['company', 'activeTab' => 'overview'])

@php
    $tabs = [
        'overview' => [
            'label' => 'Overview',
            'icon' => 'fa-solid fa-circle-info',
        ],

        'timeline' => [
            'label' => 'Timeline',
            'icon' => 'fa-solid fa-timeline',
        ],

        'activities' => [
            'label' => 'Activities',
            'icon' => 'fa-solid fa-clock-rotate-left',
        ],

        'contacts' => [
            'label' => 'Contacts',
            'icon' => 'fa-solid fa-address-book',
        ],

        'notes' => [
            'label' => 'Notes',
            'icon' => 'fa-solid fa-note-sticky',
        ],

        'tasks' => [
            'label' => 'Tasks',
            'icon' => 'fa-solid fa-list-check',
        ],

        'meetings' => [
            'label' => 'Meetings',
            'icon' => 'fa-solid fa-calendar-days',
        ],

        'calls' => [
            'label' => 'Calls',
            'icon' => 'fa-solid fa-phone-volume',
        ],

        'documents' => [
            'label' => 'Documents',
            'icon' => 'fa-solid fa-folder-open',
        ],

        'deals' => [
            'label' => 'Deals',
            'icon' => 'fa-solid fa-handshake',
        ],

        'ai' => [
            'label' => 'AI Insights',
            'icon' => 'fa-solid fa-wand-magic-sparkles',
        ],
    ];
@endphp

<div class="lp-company-tabs mb-4">
    @foreach($tabs as $key => $tab)
        <a href="{{ route('companies.show', $company->uuid) }}?tab={{ $key }}"
           class="{{ $activeTab === $key ? 'active' : '' }}">
            <i class="{{ $tab['icon'] }} me-1"></i>
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>