@props([
    'company',
    'contacts' => collect(),
    'tasks' => collect(),
    'meetings' => collect(),
    'documents' => collect(),
])


<div class="lp-company-hero">

    <div class="lp-company-hero-main">

        <div class="lp-company-hero-left">

            <a href="{{ route('companies.index') }}"
               class="lp-company-back-link">

                <i class="fa-solid fa-arrow-left"></i>
                Back to Companies

            </a>

            <div class="lp-company-identity">

                <div class="lp-company-avatar">

                    {{ strtoupper(substr($company->company_name, 0, 1)) }}

                </div>

                <div class="lp-company-title-wrap">

                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">

                        <h1 class="lp-company-name">
                            {{ $company->company_name }}
                        </h1>

                        <x-feedback.status-badge :status="$company->status" />

                    </div>

                    <div class="lp-company-meta">

                        <span>
                            <i class="fa-solid fa-briefcase"></i>
                            {{ $company->industry ?: 'Industry not specified' }}
                        </span>

                        @if($company->city || $company->country)
                            <span>
                                <i class="fa-solid fa-location-dot"></i>
                                {{ collect([$company->city, $company->country])->filter()->join(', ') }}
                            </span>
                        @endif

                    </div>

                </div>

            </div>

            <div class="lp-company-actions">

                @if($company->website)
                    <a href="{{ $company->website }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="lp-company-action">

                        <span class="lp-company-action-icon lp-company-action-blue">
                            <i class="fa-solid fa-globe"></i>
                        </span>

                        <span>Website</span>

                    </a>
                @endif

                @if($company->email)
                    <a href="mailto:{{ $company->email }}"
                       class="lp-company-action">

                        <span class="lp-company-action-icon lp-company-action-purple">
                            <i class="fa-solid fa-envelope"></i>
                        </span>

                        <span>Email</span>

                    </a>
                @endif

                @if($company->phone)
                    <a href="tel:{{ $company->phone }}"
                       class="lp-company-action">

                        <span class="lp-company-action-icon lp-company-action-green">
                            <i class="fa-solid fa-phone"></i>
                        </span>

                        <span>Call</span>

                    </a>
                @endif

                @if($company->linkedin_url)
                    <a href="{{ $company->linkedin_url }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="lp-company-action">

                        <span class="lp-company-action-icon lp-company-action-linkedin">
                            <i class="fa-brands fa-linkedin-in"></i>
                        </span>

                        <span>LinkedIn</span>

                    </a>
                @endif

            </div>

        </div>

        <div class="lp-company-hero-right">

            <div class="lp-company-health-card">

                <div class="lp-company-health-icon">
                    <i class="fa-solid fa-heart-pulse"></i>
                </div>

                <div>
                    <small>Company Health</small>
                    <strong>
                        {{ $company->status === 'customer' ? 'Excellent' : ($company->status === 'qualified' ? 'Good' : 'Developing') }}
                    </strong>
                </div>

            </div>

            <div class="lp-company-primary-actions">

                <a href="{{ route('companies.edit', $company->uuid) }}"
                   class="lp-btn lp-btn-primary">

                    <i class="fa-solid fa-pen"></i>
                    Edit Company

                </a>

                <a href="{{ route('companies.show', [$company->uuid, 'tab' => 'meetings']) }}"
                   class="lp-btn lp-btn-soft">

                    <i class="fa-solid fa-calendar-plus"></i>
                    Schedule Meeting

                </a>

            </div>

        </div>

    </div>

    <div class="lp-company-metrics">

    <div class="lp-company-metric">
        <span class="lp-company-metric-icon lp-company-metric-blue">
            <i class="fa-solid fa-address-book"></i>
        </span>

        <div>
            <small>Contacts</small>
            <strong>{{ isset($contacts) ? $contacts->count() : 0 }}</strong>
        </div>
    </div>

    <div class="lp-company-metric">
        <span class="lp-company-metric-icon lp-company-metric-orange">
            <i class="fa-solid fa-list-check"></i>
        </span>

        <div>
            <small>Open Tasks</small>
            <strong>
                {{ isset($tasks)
                    ? $tasks->where('status', '!=', 'completed')->count()
                    : 0 }}
            </strong>
        </div>
    </div>

    <div class="lp-company-metric">
        <span class="lp-company-metric-icon lp-company-metric-green">
            <i class="fa-solid fa-calendar-days"></i>
        </span>

        <div>
            <small>Meetings</small>
            <strong>{{ isset($meetings) ? $meetings->count() : 0 }}</strong>
        </div>
    </div>

    <div class="lp-company-metric">
        <span class="lp-company-metric-icon lp-company-metric-purple">
            <i class="fa-solid fa-file-lines"></i>
        </span>

        <div>
            <small>Documents</small>
            <strong>{{ isset($documents) ? $documents->count() : 0 }}</strong>
        </div>
    </div>

</div>

</div>