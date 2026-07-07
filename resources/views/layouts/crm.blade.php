<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <title>{{ $pageTitle ?? config('leadpilot.app_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #F8FAFC;
            font-family: Inter, system-ui, Arial, sans-serif;
            color: #0F172A;
        }

        .lp-sidebar {
            width: 260px;
            min-height: 100vh;
            background: #0F172A;
            position: fixed;
            left: 0;
            top: 0;
        }

        .lp-sidebar .brand {
            padding: 22px;
            color: #fff;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .lp-sidebar a {
            color: #CBD5E1;
            text-decoration: none;
            display: block;
            padding: 12px 22px;
            font-size: 14px;
        }

        .lp-sidebar a:hover,
        .lp-sidebar a.active {
            background: #1E293B;
            color: #fff;
        }

        .lp-main {
            margin-left: 260px;
        }

        .lp-topbar {
            height: 64px;
            background: #fff;
            border-bottom: 1px solid #E2E8F0;
        }

        .lp-content {
            padding: 24px;
        }

        .lp-card {
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 14px;
            padding: 20px;
        }

        .lp-page-title {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .lp-badge {
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .lp-badge-prospect { background:#DBEAFE; color:#1D4ED8; }
        .lp-badge-qualified { background:#DCFCE7; color:#15803D; }
        .lp-badge-customer { background:#ECFDF5; color:#047857; }
        .lp-badge-inactive { background:#F1F5F9; color:#475569; }
        .lp-badge-blacklisted { background:#FEE2E2; color:#B91C1C; }
    </style>
    @livewireStyles
</head>
<body>

<aside class="lp-sidebar">
    <div class="brand">
        <h5 class="mb-0">LeadPilot AI</h5>
        <small class="text-secondary">v0.1 Alpha</small>
    </div>

    <a href="{{ route('dashboard') }}">
        <i class="fa-solid fa-chart-line me-2"></i> Dashboard
    </a>

    <a href="{{ route('companies.index') }}" class="active">
        <i class="fa-solid fa-building me-2"></i> Companies
    </a>

    <a href="#">
        <i class="fa-solid fa-address-book me-2"></i> Contacts
    </a>

    <a href="#">
        <i class="fa-solid fa-bullseye me-2"></i> Leads
    </a>

    <a href="#">
        <i class="fa-solid fa-wand-magic-sparkles me-2"></i> AI Workspace
    </a>

    <a href="#">
        <i class="fa-solid fa-chart-pie me-2"></i> Reports
    </a>

    <a href="#">
        <i class="fa-solid fa-gear me-2"></i> Settings
    </a>
</aside>

<div class="lp-main">
    <header class="lp-topbar d-flex align-items-center justify-content-between px-4">
        <div class="text-muted">
            {{ config('leadpilot.motto') }}
        </div>

        <div class="d-flex align-items-center gap-3">
            <span>{{ auth()->user()->name ?? 'User' }}</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-outline-danger">
                    Logout
                </button>
            </form>
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
</body>
</html>