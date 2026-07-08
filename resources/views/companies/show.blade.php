@extends('layouts.crm')

@section('content')

<x-crm.company-header :company="$company" />
<x-crm.company-tabs />
<div class="row">
    <div class="col-lg-8">
        <x-cards.card>
            <h5 class="mb-4">Company Information</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Website</strong>
                    <p>{{ $company->website ?: '-' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Email</strong>
                    <p>{{ $company->email ?: '-' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Phone</strong>
                    <p>{{ $company->phone ?: '-' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Industry</strong>
                    <p>{{ $company->industry ?: '-' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>City</strong>
                    <p>{{ $company->city ?: '-' }}</p>
                </div>

                <div class="col-md-6 mb-3">
                    <strong>Status</strong><br>
                    <x-feedback.status-badge :status="$company->status" />
                </div>
            </div>
        </x-cards.card>
    </div>

    <div class="col-lg-4">
        <x-cards.card>
            <h5>Company Health</h5>
            <hr>

            <div class="mb-3">Website: {{ $company->website ? '✅' : '❌' }}</div>
            <div class="mb-3">Email: {{ $company->email ? '✅' : '❌' }}</div>
            <div>Phone: {{ $company->phone ? '✅' : '❌' }}</div>
        </x-cards.card>
    </div>
</div>
<div class="row mt-4">
    <div class="col-lg-12">
        <x-crm.activity-timeline :activities="$activities" />
    </div>
</div>
@endsection