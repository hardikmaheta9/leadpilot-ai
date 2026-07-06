<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('leadpilot.app_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        body { background:#F8FAFC; font-family: Inter, system-ui, Arial, sans-serif; }
        .lp-sidebar { width:260px; min-height:100vh; background:#0F172A; position:fixed; left:0; top:0; }
        .lp-sidebar a { color:#CBD5E1; text-decoration:none; display:block; padding:12px 20px; }
        .lp-sidebar a:hover, .lp-sidebar a.active { background:#1E293B; color:#fff; }
        .lp-main { margin-left:260px; }
        .lp-topbar { height:64px; background:#fff; border-bottom:1px solid #E2E8F0; }
        .lp-content { padding:24px; }
        .lp-card { background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:20px; }
    </style>
</head>
<body>

@include('partials.sidebar')

<div class="lp-main">
    @include('partials.topbar')

    <main class="lp-content">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>