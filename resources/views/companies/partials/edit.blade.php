@extends('layouts.crm')

@section('content')

<x-layout.page-header
    title="Edit Company"
    subtitle="Update company information."
/>

<x-cards.card>
    <form method="POST" action="{{ route('companies.update', $company->uuid) }}">
        @method('PUT')

        @include('companies.partials.form')
    </form>
</x-cards.card>

@endsection