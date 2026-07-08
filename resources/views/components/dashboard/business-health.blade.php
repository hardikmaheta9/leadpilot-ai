<div class="lp-business-health">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h5 class="mb-1">
                Business Health
            </h5>

            <small class="text-muted">

                Overall CRM quality

            </small>

        </div>

        <span class="badge bg-success">

            Good

        </span>

    </div>

    @php

        $websitePercent = $totalCompanies
            ? round(($companiesWithWebsite / $totalCompanies) * 100)
            : 0;

        $emailPercent = $totalCompanies
            ? round(($companiesWithEmail / $totalCompanies) * 100)
            : 0;

    @endphp

    <div class="mb-4">

        <div class="d-flex justify-content-between">

            <small>Website Completion</small>

            <strong>{{ $websitePercent }}%</strong>

        </div>

        <div class="progress mt-2">

            <div class="progress-bar bg-primary"

                style="width: {{ $websitePercent }}%">

            </div>

        </div>

    </div>

    <div>

        <div class="d-flex justify-content-between">

            <small>Email Completion</small>

            <strong>{{ $emailPercent }}%</strong>

        </div>

        <div class="progress mt-2">

            <div class="progress-bar bg-success"

                style="width: {{ $emailPercent }}%">

            </div>

        </div>

    </div>

</div>