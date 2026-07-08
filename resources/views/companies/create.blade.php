@extends('layouts.crm')

@section('content')

<x-layout.page-header
    title="Create Company"
    subtitle="Add a new company to your CRM."
/>

<x-cards.card>

<form method="POST" action="{{ route('companies.store') }}">

    @include('companies.partials.form')

</form>

</x-cards.card>

@endsection