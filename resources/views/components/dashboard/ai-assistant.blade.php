<div class="lp-ai-card">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h5 class="mb-1">
                🤖 LeadPilot AI Assistant
            </h5>

            <small class="text-muted">
                Business recommendations for today
            </small>
        </div>

        <span class="badge bg-primary">
            BETA
        </span>

    </div>

    <div class="lp-ai-message">

        <p>👋 Good Morning, {{ auth()->user()->name }}.</p>

        <ul class="mb-4">

            @if($totalCompanies == 0)

                <li>Start by creating your first company.</li>

            @endif

            @if($prospectCompanies > 0)

                <li>
                    {{ $prospectCompanies }}
                    prospect companies need follow-up.
                </li>

            @endif

            @if($qualifiedCompanies == 0)

                <li>
                    You don't have any qualified companies yet.
                </li>

            @endif

            <li>
                Continue growing your CRM today 🚀
            </li>

        </ul>

    </div>

    <div class="d-grid gap-2">

        <a href="{{ route('companies.create') }}"
           class="btn btn-primary">

            <i class="fa-solid fa-plus me-1"></i>

            Add Company

        </a>

        <button class="btn btn-outline-primary">

            <i class="fa-solid fa-wand-magic-sparkles me-1"></i>

            AI Research (Coming Soon)

        </button>

    </div>

</div>