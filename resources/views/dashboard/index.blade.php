@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h2 class="fw-bold">Dashboard</h2>
        <p class="text-muted">Welcome to LeadPilot AI — your business development command center.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="lp-card">
                <small class="text-muted">Today’s Leads</small>
                <h3 class="mt-2">0</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="lp-card">
                <small class="text-muted">Hot Leads</small>
                <h3 class="mt-2">0</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="lp-card">
                <small class="text-muted">Pending Follow-ups</small>
                <h3 class="mt-2">0</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="lp-card">
                <small class="text-muted">AI Opportunities</small>
                <h3 class="mt-2">0</h3>
            </div>
        </div>
    </div>
@endsection