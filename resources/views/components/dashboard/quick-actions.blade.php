<x-ui.section-card
    title="Quick Actions"
    icon="fa-solid fa-bolt">

    <div class="row g-3">

        <div class="col-md-6">
            <a href="{{ route('companies.create') }}" class="lp-action-card">
                <i class="fa-solid fa-building"></i>
                <span>Add Company</span>
                <small>Create a new company profile</small>
            </a>
        </div>

        <div class="col-md-6">
            <a href="#" class="lp-action-card">
                <i class="fa-solid fa-user-plus"></i>
                <span>Add Contact</span>
                <small>Create a company contact</small>
            </a>
        </div>

        <div class="col-md-6">
            <a href="#" class="lp-action-card">
                <i class="fa-solid fa-bullseye"></i>
                <span>Create Lead</span>
                <small>Start a new opportunity</small>
            </a>
        </div>

        <div class="col-md-6">
            <a href="#" class="lp-action-card">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                <span>AI Research</span>
                <small>Analyze potential clients</small>
            </a>
        </div>

    </div>

</x-ui.section-card>