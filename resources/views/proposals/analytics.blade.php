@extends('layouts.crm')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap mb-4">

        <div>
            <span class="lp-module-eyebrow">Proposal Analytics</span>

            <h2 class="mb-1">
                {{ $company->company_name }}
            </h2>

            <p class="text-muted mb-0">
                Proposal engagement, delivery and conversion statistics.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            <a
                href="{{ route('companies.proposal.history', $company) }}"
                class="lp-btn lp-btn-light"
            >
                <i class="fa-solid fa-clock-rotate-left"></i>
                Proposal History
            </a>

            <a
                href="{{ route('companies.show', [
                    'uuid' => $company->uuid,
                    'tab' => 'ai',
                ]) }}"
                class="lp-btn lp-btn-light"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Back to AI Insights
            </a>

        </div>

    </div>

    <div class="row g-4 mb-4">

        @foreach([
            ['Total Proposals', $stats['total'], 'fa-solid fa-file-lines'],
            ['Sent', $stats['sent'], 'fa-solid fa-paper-plane'],
            ['Viewed', $stats['viewed'], 'fa-solid fa-eye'],
            ['Accepted', $stats['accepted'], 'fa-solid fa-circle-check'],
            ['Rejected', $stats['rejected'], 'fa-solid fa-circle-xmark'],
            ['Total Views', $stats['views'], 'fa-solid fa-chart-line'],
            ['Downloads', $stats['downloads'], 'fa-solid fa-download'],
        ] as [$label, $value, $icon])

            <div class="col-xl-3 col-md-6">

                <div class="lp-ai-metric-card h-100">

                    <div class="lp-ai-metric-icon lp-ai-metric-blue">
                        <i class="{{ $icon }}"></i>
                    </div>

                    <small>{{ $label }}</small>

                    <strong>
                        {{ number_format((int) $value) }}
                    </strong>

                </div>

            </div>

        @endforeach

    </div>

    <div class="lp-module-card">

        <div class="lp-module-header">
            <div>
                <span class="lp-module-eyebrow">Version Performance</span>

                <h4>Proposal Engagement</h4>

                <p>
                    Performance details for every generated proposal version.
                </p>
            </div>
        </div>

        <div class="lp-module-body">

            @if($proposals->isEmpty())

                <x-ui.empty-state
                    icon="fa-solid fa-chart-line"
                    title="No Proposal Analytics"
                    message="Generate and send a proposal to begin tracking engagement."
                />

            @else

                <div class="table-responsive">

                    <table class="table align-middle mb-0">

                        <thead>
                            <tr>
                                <th>Version</th>
                                <th>Status</th>
                                <th>Generated</th>
                                <th>Sent</th>
                                <th>Views</th>
                                <th>Downloads</th>
                                <th>Last Viewed</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($proposals->sortByDesc('version') as $proposal)

                                <tr>

                                    <td>
                                        <strong>
                                            V{{ $proposal->version }}
                                        </strong>

                                        @if($proposal->is_latest)
                                            <span class="badge bg-primary-subtle text-primary border ms-1">
                                                Latest
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ \Illuminate\Support\Str::headline(
                                            (string) ($proposal->proposal_status ?: 'draft')
                                        ) }}
                                    </td>

                                    <td>
                                        {{ optional($proposal->generated_at)->format('d M Y H:i') ?: '—' }}
                                    </td>

                                    <td>
                                        {{ optional($proposal->sent_at)->format('d M Y H:i') ?: '—' }}
                                    </td>

                                    <td>
                                        {{ number_format((int) ($proposal->view_count ?? 0)) }}
                                    </td>

                                    <td>
                                        {{ number_format((int) ($proposal->download_count ?? 0)) }}
                                    </td>

                                    <td>
                                        {{ optional($proposal->last_viewed_at)->format('d M Y H:i') ?: '—' }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
