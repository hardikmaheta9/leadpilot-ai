@extends('layouts.crm')

@section('content')

<x-page-header
    title="Create Company"
    subtitle="Add a new company to your CRM."
/>

<x-card>

<form method="POST" action="{{ route('companies.store') }}">

    @include('companies.partials.form')

</form>

</x-card>

@endsection