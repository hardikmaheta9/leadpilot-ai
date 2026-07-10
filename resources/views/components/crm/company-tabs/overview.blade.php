@php
    $healthItems = [
        [
            'label' => 'Website',
            'available' => !empty($company->website),
            'icon' => 'fa-solid fa-globe',
        ],
        [
            'label' => 'Email',
            'available' => !empty($company->email),
            'icon' => 'fa-solid fa-envelope',
        ],
        [
            'label' => 'Phone',
            'available' => !empty($company->phone),
            'icon' => 'fa-solid fa-phone',
        ],
        [
            'label' => 'Industry',
            'available' => !empty($company->industry),
            'icon' => 'fa-solid fa-briefcase',
        ],
        [
            'label' => 'Location',
            'available' => !empty($company->city) || !empty($company->country),
            'icon' => 'fa-solid fa-location-dot',
        ],
    ];

    $completedHealthItems = collect($healthItems)
        ->where('available', true)
        ->count();

    $healthScore = count($healthItems) > 0
        ? (int) round(($completedHealthItems / count($healthItems)) * 100)
        : 0;

    $healthLabel = match (true) {
        $healthScore >= 80 => 'Excellent',
        $healthScore >= 60 => 'Good',
        $healthScore >= 40 => 'Needs Attention',
        default => 'Incomplete',
    };
@endphp

<div class="row g-4">

    <div class="col-xl-8">

        <div class="lp-overview-card">

            <div class="lp-overview-card-header">

                <div>
                    <span class="lp-overview-eyebrow">Company Profile</span>

                    <h4>Company Information</h4>

                    <p>
                        Core business and contact information for this company.
                    </p>
                </div>

                <a href="{{ route('companies.edit', $company->uuid) }}"
                   class="lp-btn lp-btn-soft">

                    <i class="fa-solid fa-pen"></i>
                    Edit Details

                </a>

            </div>

            <div class="lp-company-info-grid">

                <div class="lp-company-info-item">

                    <div class="lp-company-info-icon lp-info-blue">
                        <i class="fa-solid fa-globe"></i>
                    </div>

                    <div>
                        <span>Website</span>

                        @if($company->website)
                            <a href="{{ $company->website }}"
                               target="_blank"
                               rel="noopener noreferrer">

                                {{ $company->website }}

                            </a>
                        @else
                            <strong>Not provided</strong>
                        @endif
                    </div>

                </div>

                <div class="lp-company-info-item">

                    <div class="lp-company-info-icon lp-info-purple">
                        <i class="fa-solid fa-envelope"></i>
                    </div>

                    <div>
                        <span>Email</span>

                        @if($company->email)
                            <a href="mailto:{{ $company->email }}">
                                {{ $company->email }}
                            </a>
                        @else
                            <strong>Not provided</strong>
                        @endif
                    </div>

                </div>

                <div class="lp-company-info-item">

                    <div class="lp-company-info-icon lp-info-green">
                        <i class="fa-solid fa-phone"></i>
                    </div>

                    <div>
                        <span>Phone</span>

                        @if($company->phone)
                            <a href="tel:{{ $company->phone }}">
                                {{ $company->phone }}
                            </a>
                        @else
                            <strong>Not provided</strong>
                        @endif
                    </div>

                </div>

                <div class="lp-company-info-item">

                    <div class="lp-company-info-icon lp-info-orange">
                        <i class="fa-solid fa-briefcase"></i>
                    </div>

                    <div>
                        <span>Industry</span>
                        <strong>{{ $company->industry ?: 'Not specified' }}</strong>
                    </div>

                </div>

                <div class="lp-company-info-item">

                    <div class="lp-company-info-icon lp-info-red">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>

                    <div>
                        <span>Location</span>

                        <strong>
                            {{ collect([
                                $company->city ?? null,
                                $company->state ?? null,
                                $company->country ?? null
                            ])->filter()->join(', ') ?: 'Not specified' }}
                        </strong>
                    </div>

                </div>

                <div class="lp-company-info-item">

                    <div class="lp-company-info-icon lp-info-indigo">
                        <i class="fa-solid fa-signal"></i>
                    </div>

                    <div>
                        <span>Status</span>

                        <div class="mt-1">
                            <x-feedback.status-badge :status="$company->status" />
                        </div>
                    </div>

                </div>

            </div>

            <div class="lp-company-summary">

                <div class="lp-company-summary-icon">
                    <i class="fa-solid fa-align-left"></i>
                </div>

                <div>
                    <span>Company Summary</span>

                    <p>
                        {{ $company->description
                            ?? $company->notes
                            ?? 'No company description has been added yet.' }}
                    </p>
                </div>

            </div>

        </div>

    </div>

    <div class="col-xl-4">

        <div class="lp-overview-card lp-health-panel">

            <div class="lp-overview-card-header">

                <div>
                    <span class="lp-overview-eyebrow">Data Quality</span>
                    <h4>Company Health</h4>
                    <p>Completeness of the company profile.</p>
                </div>

            </div>

            <div class="lp-health-score">

                <div class="lp-health-score-ring"
                     style="--lp-health-score: {{ $healthScore }}">

                    <div>
                        <strong>{{ $healthScore }}%</strong>
                        <span>{{ $healthLabel }}</span>
                    </div>

                </div>

            </div>

            <div class="lp-health-list">

                @foreach($healthItems as $item)

                    <div class="lp-health-item">

                        <div class="lp-health-item-left">

                            <span class="lp-health-item-icon">
                                <i class="{{ $item['icon'] }}"></i>
                            </span>

                            <strong>{{ $item['label'] }}</strong>

                        </div>

                        <span class="{{ $item['available']
                            ? 'lp-health-status-success'
                            : 'lp-health-status-missing' }}">

                            <i class="fa-solid {{ $item['available']
                                ? 'fa-check'
                                : 'fa-xmark' }}"></i>

                            {{ $item['available'] ? 'Available' : 'Missing' }}

                        </span>

                    </div>

                @endforeach

            </div>

            @if($healthScore < 100)

                <a href="{{ route('companies.edit', $company->uuid) }}"
                   class="lp-btn lp-btn-primary w-100 justify-content-center mt-3">

                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    Improve Company Profile

                </a>

            @endif

        </div>

    </div>

</div>