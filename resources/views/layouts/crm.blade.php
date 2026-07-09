<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $pageTitle ?? config('leadpilot.app_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    @livewireStyles
</head>

<body>

<aside class="lp-sidebar">
    <div class="lp-sidebar-brand">
        <div class="lp-logo">LP</div>
        <div>
            <div class="lp-sidebar-title">LeadPilot AI</div>
            <div class="lp-sidebar-subtitle">Growth Command Center</div>
        </div>
    </div>

    <div class="lp-nav-section">CRM</div>

    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fa-solid fa-chart-line"></i> Dashboard
    </a>

    <a href="{{ route('companies.index') }}" class="{{ request()->routeIs('companies.*') ? 'active' : '' }}">
        <i class="fa-solid fa-building"></i> Companies
    </a>

    <a href="#">
        <i class="fa-solid fa-address-book"></i> Contacts
    </a>

    <a href="{{ route('calendar.index') }}" class="{{ request()->routeIs('calendar.*') ? 'active' : '' }}">
        <i class="fa-solid fa-calendar-days"></i> Calendar
    </a>

    <div class="lp-nav-section">Sales</div>

    <a href="#">
        <i class="fa-solid fa-bullseye"></i> Leads
    </a>

    <a href="#">
        <i class="fa-solid fa-handshake"></i> Deals
    </a>

    <a href="#">
        <i class="fa-solid fa-list-check"></i> Tasks
    </a>

    <div class="lp-nav-section">AI</div>

    <a href="#">
        <i class="fa-solid fa-wand-magic-sparkles"></i> AI Workspace
    </a>

    <a href="#">
        <i class="fa-solid fa-magnifying-glass-chart"></i> Lead Discovery
    </a>

    <a href="#">
        <i class="fa-solid fa-globe"></i> Website Analyzer
    </a>

    <div class="lp-nav-section">Admin</div>

    <a href="#">
        <i class="fa-solid fa-chart-pie"></i> Reports
    </a>

    <a href="#">
        <i class="fa-solid fa-gear"></i> Settings
    </a>
</aside>

<div class="lp-main">
    <header class="lp-topbar">
        <div class="lp-search-wrap">
            <input
                id="globalSearch"
                class="form-control lp-search-input"
                placeholder="Search companies, contacts, tasks...  Ctrl + K">

            <div
                id="searchResults"
                class="list-group position-absolute w-100"
                style="display:none; z-index:9999; max-height:420px; overflow:auto;">
            </div>
        </div>

        <div class="lp-topbar-actions">
            <button class="lp-icon-btn" type="button">
                <i class="fa-solid fa-plus"></i>
            </button>

            <x-ui.notification-dropdown />

            <div class="lp-user-pill">
                <div class="lp-avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>

                <span>{{ auth()->user()->name ?? 'User' }}</span>

                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <button class="btn btn-sm btn-light">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="lp-content">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@livewireScripts
<x-ui.floating-quick-add />
</body>
</html>