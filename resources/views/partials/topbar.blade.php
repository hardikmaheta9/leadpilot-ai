<header class="lp-topbar d-flex align-items-center justify-content-between px-4">
    <div>
        <strong>{{ config('leadpilot.motto') }}</strong>
    </div>

    <div class="d-flex align-items-center gap-3">
        <span class="text-muted">
            {{ auth()->user()->name ?? 'Guest' }}
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-outline-danger">
                Logout
            </button>
        </form>
    </div>
</header>