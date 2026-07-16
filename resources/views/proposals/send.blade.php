@extends('layouts.crm')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
        <div>
            <span class="lp-module-eyebrow">Proposal Delivery</span>
            <h2 class="mb-1">Send Proposal</h2>
            <p class="text-muted mb-0">{{ $proposal->proposal_title }} · Version {{ $proposal->version }}</p>
        </div>
        <a href="{{ route('companies.proposal.show', [$company, $proposal]) }}" class="lp-btn lp-btn-light">
            <i class="fa-solid fa-arrow-left"></i> Back to Proposal
        </a>
    </div>

    <div class="lp-module-card">
        <div class="lp-module-header">
            <div>
                <span class="lp-module-eyebrow">Email</span>
                <h4>Compose Proposal Email</h4>
                <p>A PDF copy and secure public proposal link will be included.</p>
            </div>
        </div>

        <div class="lp-module-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('companies.proposal.send.store', [$company, $proposal]) }}">
                @csrf

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Recipient Email</label>
                        <input type="email" name="to" class="form-control" value="{{ old('to', $defaultTo) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">CC</label>
                        <input type="text" name="cc" class="form-control" value="{{ old('cc') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">BCC</label>
                        <input type="text" name="bcc" class="form-control" value="{{ old('bcc') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Subject</label>
                        <input type="text" name="subject" class="form-control" value="{{ old('subject', $proposal->proposal_title) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea name="message" rows="11" class="form-control" required>{{ old('message', "Dear Sir/Madam,

Please find attached our proposal for your review. You can also use the secure proposal link in this email to view, download, accept, reject, or request changes.

Regards,
WebApp Infoway") }}</textarea>
                    </div>
                </div>

                <div class="d-flex gap-2 flex-wrap mt-4">
                    <button type="submit" class="lp-btn lp-btn-primary">
                        <i class="fa-solid fa-paper-plane"></i> Send Proposal
                    </button>
                    <a href="{{ route('companies.proposal.show', [$company, $proposal]) }}" class="lp-btn lp-btn-light">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
