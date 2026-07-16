@extends('layouts.crm')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">
        <div>
            <span class="lp-module-eyebrow">Proposal Management</span>
            <h2 class="mb-1">Proposal History</h2>
            <p class="text-muted mb-0">{{ $company->company_name }}</p>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            <form method="POST" action="{{ route('companies.proposal.generate', $company) }}">
                @csrf
                <button type="submit" class="lp-btn lp-btn-primary">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Generate New Version
                </button>
            </form>

            <a href="{{ route('companies.show', ['uuid' => $company->uuid, 'tab' => 'ai']) }}" class="lp-btn lp-btn-light">
                <i class="fa-solid fa-arrow-left"></i> Back to AI Insights
            </a>
        </div>
    </div>

    <div class="lp-module-card">
        <div class="lp-module-header">
            <div>
                <span class="lp-module-eyebrow">Versions</span>
                <h4>All Generated Proposals</h4>
                <p>Review, download and send any proposal version.</p>
            </div>
        </div>

        <div class="lp-module-body">
            @if($proposals->isEmpty())
                <x-ui.empty-state icon="fa-solid fa-file-signature" title="No Proposals Found" message="Generate the first proposal for this company." />
            @else
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Version</th>
                                <th>Proposal</th>
                                <th>Status</th>
                                <th>Generated</th>
                                <th>Views</th>
                                <th>Downloads</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($proposals as $proposal)
                            @php
                                $status = strtolower((string) ($proposal->proposal_status ?? 'draft'));
                                $statusClass = match ($status) {
                                    'accepted' => 'success',
                                    'sent', 'viewed' => 'primary',
                                    'rejected' => 'danger',
                                    'archived', 'expired' => 'secondary',
                                    'changes_requested' => 'warning',
                                    default => 'warning',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <strong>V{{ $proposal->version }}</strong>
                                        @if($proposal->is_latest)
                                            <span class="badge bg-primary-subtle text-primary border">Latest</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <strong class="d-block">{{ $proposal->proposal_title }}</strong>
                                    <small class="text-muted">ID #{{ $proposal->id }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border">
                                        {{ \Illuminate\Support\Str::headline($status) }}
                                    </span>
                                </td>
                                <td>{{ optional($proposal->generated_at)->format('d M Y H:i') ?: 'Not available' }}</td>
                                <td>{{ number_format((int) ($proposal->view_count ?? 0)) }}</td>
                                <td>{{ number_format((int) ($proposal->download_count ?? 0)) }}</td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 flex-wrap justify-content-end">
                                        <a href="{{ route('companies.proposal.show', [$company, $proposal]) }}" target="_blank" rel="noopener noreferrer" class="lp-btn lp-btn-light">
                                            <i class="fa-solid fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('companies.proposal.pdf', [$company, $proposal]) }}" target="_blank" rel="noopener noreferrer" data-no-loader="true" class="lp-btn lp-btn-light">
                                            <i class="fa-solid fa-file-pdf"></i> PDF
                                        </a>
                                        <a href="{{ route('companies.proposal.docx', [$company, $proposal]) }}" target="_blank" rel="noopener noreferrer" data-no-loader="true" class="lp-btn lp-btn-light">
                                            <i class="fa-solid fa-file-word"></i> DOCX
                                        </a>
                                        <a href="{{ route('companies.proposal.send', [$company, $proposal]) }}" class="lp-btn lp-btn-light">
                                            <i class="fa-solid fa-paper-plane"></i> Send
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $proposals->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
