@extends('layouts.crm')

@section('content')

<x-page-header
    title="Edit Company"
    subtitle="Update company information."
/>

<x-card>
    <form method="POST" action="{{ route('companies.update', $company->uuid) }}">
        @method('PUT')

        @include('companies.partials.form')
    </form>
</x-card>

@endsection