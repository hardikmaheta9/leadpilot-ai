@extends('layouts.crm')

@section('content')

<x-page-header
    :title="$company->company_name"
    subtitle="Company Details"
/>

<x-card>

<div class="row">

    <div class="col-md-6 mb-4">

        <h6 class="text-muted">Company</h6>
        <p>{{ $company->company_name }}</p>

    </div>

    <div class="col-md-6 mb-4">

        <h6 class="text-muted">Website</h6>

        @if($company->website)
            <a href="{{ $company->website }}" target="_blank">
                {{ $company->website }}
            </a>
        @else
            -
        @endif

    </div>

    <div class="col-md-6 mb-4">

        <h6 class="text-muted">Email</h6>

        {{ $company->email ?? '-' }}

    </div>

    <div class="col-md-6 mb-4">

        <h6 class="text-muted">Phone</h6>

        {{ $company->phone ?? '-' }}

    </div>

    <div class="col-md-6 mb-4">

        <h6 class="text-muted">Industry</h6>

        {{ $company->industry ?? '-' }}

    </div>

    <div class="col-md-6 mb-4">

        <h6 class="text-muted">Status</h6>

        <x-status-badge :status="$company->status"/>

    </div>

</div>

<hr>

<div class="d-flex gap-2">

    <a href="{{ route('companies.edit',$company->uuid) }}"
       class="btn btn-primary">

        <i class="fa-solid fa-pen"></i>

        Edit

    </a>

    <a href="{{ route('companies.index') }}"
       class="btn btn-secondary">

        Back

    </a>

</div>

</x-card>

@endsection