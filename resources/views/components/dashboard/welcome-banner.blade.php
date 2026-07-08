<div class="lp-welcome-banner mb-4">
    <div>
        <p class="text-muted mb-1">{{ now()->format('l, d F Y') }}</p>
        <h1 class="mb-2">Good Morning, {{ auth()->user()->name ?? 'User' }} 👋</h1>
        <p class="mb-0">Welcome back to LeadPilot AI. Here’s what’s happening today.</p>
    </div>

    <div class="lp-welcome-actions">
        <a href="{{ route('companies.create') }}" class="btn btn-light">
            <i class="fa-solid fa-plus me-1"></i> Add Company
        </a>
    </div>
</div>