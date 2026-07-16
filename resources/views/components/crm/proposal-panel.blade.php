@php
    $latestProposal = $aiProposals->firstWhere('is_latest', true)
        ?? $aiProposals->sortByDesc('version')->first();

    $status = strtolower((string) ($latestProposal?->proposal_status ?? 'draft'));

    $statusClass = match ($status) {
        'accepted' => 'success',
        'sent', 'viewed' => 'primary',
        'rejected' => 'danger',
        'archived', 'expired' => 'secondary',
        'changes_requested' => 'warning',
        default => 'warning',
    };
@endphp

<div class="lp-ai-insight-panel mt-4">
    <div class="lp-ai-panel-heading">
        <div>
            <span class="lp-ai-panel-icon lp-ai-panel-icon-blue">
                <i class="fa-solid fa-file-signature"></i>
            </span>
            <div>
                <h5>AI Proposal Management</h5>
                <p>Latest version, delivery, tracking and proposal history.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('companies.proposal.generate', $company) }}">
            @csrf
            <button class="lp-btn lp-btn-primary" type="submit">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                {{ $latestProposal ? 'Generate New Version' : 'Generate Proposal' }}
            </button>
        </form>
    </div>

    <div class="p-4">
        @if(!$latestProposal)
            <x-ui.empty-state icon="fa-solid fa-file-signature" title="No Proposal Generated" message="Generate the first proposal for this company." />
        @else
            <div class="lp-ai-insight-panel">
                <div class="lp-ai-panel-heading">
                    <div>
                        <span class="lp-ai-panel-icon lp-ai-panel-icon-blue">
                            <i class="fa-solid fa-file-lines"></i>
                        </span>
                        <div>
                            <h5>{{ $latestProposal->proposal_title }}</h5>
                            <p>Version {{ $latestProposal->version }} · {{ optional($latestProposal->generated_at)->diffForHumans() }}</p>
                        </div>
                    </div>

                    <span class="badge bg-{{ $statusClass }}-subtle text-{{ $statusClass }} border">
                        {{ \Illuminate\Support\Str::headline($status) }}
                    </span>
                </div>

                <div class="p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6"><small class="text-muted d-block">Version</small><strong>{{ $latestProposal->version }}</strong></div>
                        <div class="col-md-3 col-6"><small class="text-muted d-block">Views</small><strong>{{ number_format((int) ($latestProposal->view_count ?? 0)) }}</strong></div>
                        <div class="col-md-3 col-6"><small class="text-muted d-block">Downloads</small><strong>{{ number_format((int) ($latestProposal->download_count ?? 0)) }}</strong></div>
                        <div class="col-md-3 col-6"><small class="text-muted d-block">Status</small><strong>{{ \Illuminate\Support\Str::headline($status) }}</strong></div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <a class="lp-btn lp-btn-primary" target="_blank" rel="noopener noreferrer" href="{{ route('companies.proposal.show', [$company, $latestProposal]) }}">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                        <a class="lp-btn lp-btn-light" target="_blank" rel="noopener noreferrer" data-no-loader="true" href="{{ route('companies.proposal.pdf', [$company, $latestProposal]) }}">
                            <i class="fa-solid fa-file-pdf"></i> PDF
                        </a>
                        <a class="lp-btn lp-btn-light" target="_blank" rel="noopener noreferrer" data-no-loader="true" href="{{ route('companies.proposal.docx', [$company, $latestProposal]) }}">
                            <i class="fa-solid fa-file-word"></i> DOCX
                        </a>
                        <a class="lp-btn lp-btn-light" href="{{ route('companies.proposal.send', [$company, $latestProposal]) }}">
                            <i class="fa-solid fa-paper-plane"></i> Send
                        </a>
                        <a class="lp-btn lp-btn-light" href="{{ route('companies.proposal.history', $company) }}">
                            <i class="fa-solid fa-clock-rotate-left"></i> History
                        </a>
                        <a class="lp-btn lp-btn-light" href="{{ route('companies.proposal.analytics', $company) }}">
                            <i class="fa-solid fa-chart-line"></i> Analytics
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
