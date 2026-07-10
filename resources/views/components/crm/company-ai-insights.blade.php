@props([
    'company',
    'contacts' => collect(),
    'tasks' => collect(),
    'meetings' => collect(),
    'calls' => collect(),
    'notes' => collect(),
    'documents' => collect(),
])

@php
    $profileFields = collect([
        $company->website ?? null,
        $company->email ?? null,
        $company->phone ?? null,
        $company->industry ?? null,
        $company->city ?? null,
        $company->description ?? null,
    ]);

    $profileScore = (int) round(
        ($profileFields->filter()->count() / max($profileFields->count(), 1)) * 100
    );

    $openTasks = $tasks
        ->where('status', '!=', 'completed')
        ->count();

    $overdueTasks = $tasks
        ->filter(function ($task) {
            return $task->due_date
                && $task->due_date->isPast()
                && $task->status !== 'completed';
        })
        ->count();

    $upcomingMeetings = $meetings
        ->filter(function ($meeting) {
            return $meeting->meeting_date
                && $meeting->meeting_date->isToday()
                || (
                    $meeting->meeting_date
                    && $meeting->meeting_date->isFuture()
                );
        })
        ->count();

    $engagementPoints =
        min($contacts->count() * 8, 24)
        + min($calls->count() * 5, 20)
        + min($meetings->count() * 7, 28)
        + min($notes->count() * 3, 15)
        + min($documents->count() * 3, 13);

    $engagementScore = min($engagementPoints, 100);

    $healthScore = (int) round(
        ($profileScore * 0.55)
        + ($engagementScore * 0.45)
    );

    $healthLabel = match (true) {
        $healthScore >= 80 => 'Excellent',
        $healthScore >= 60 => 'Good',
        $healthScore >= 40 => 'Needs Attention',
        default => 'Low',
    };

    $healthClass = match (true) {
        $healthScore >= 80 => 'lp-ai-score-success',
        $healthScore >= 60 => 'lp-ai-score-primary',
        $healthScore >= 40 => 'lp-ai-score-warning',
        default => 'lp-ai-score-danger',
    };

    $nextAction = match (true) {
        $overdueTasks > 0 =>
            "Complete {$overdueTasks} overdue task" . ($overdueTasks > 1 ? 's' : ''),

        $contacts->count() === 0 =>
            'Add a decision-maker contact',

        $upcomingMeetings > 0 =>
            'Prepare for the upcoming meeting',

        $calls->count() === 0 =>
            'Log the first sales call',

        $documents->count() === 0 =>
            'Upload a proposal or quotation',

        default =>
            'Schedule the next follow-up',
    };

    $riskItems = collect();

    if (!$company->website) {
        $riskItems->push('Company website is missing.');
    }

    if (!$company->email) {
        $riskItems->push('Primary company email is missing.');
    }

    if ($contacts->count() === 0) {
        $riskItems->push('No company contacts are available.');
    }

    if ($overdueTasks > 0) {
        $riskItems->push(
            "{$overdueTasks} task" . ($overdueTasks > 1 ? 's are' : ' is') . ' overdue.'
        );
    }

    if ($meetings->count() === 0 && $calls->count() === 0) {
        $riskItems->push('No sales engagement has been recorded.');
    }
@endphp

<div class="lp-module-card">

    <div class="lp-module-header">
        <div>
            <span class="lp-module-eyebrow">Intelligence</span>
            <h4>AI Company Insights</h4>
            <p>
                Company health, engagement and recommended next actions.
            </p>
        </div>

        <span class="lp-ai-engine-badge">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
            AI Engine Ready
        </span>
    </div>

    <div class="lp-module-body">

        <div class="row g-4">

            <div class="col-xl-4 col-md-6">
                <div class="lp-ai-score-card {{ $healthClass }}">

                    <div class="lp-ai-score-header">
                        <span>Company Health</span>

                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>

                    <div
                        class="lp-ai-score-ring"
                        style="--lp-score: {{ $healthScore }}">

                        <div>
                            <strong>{{ $healthScore }}%</strong>
                            <span>{{ $healthLabel }}</span>
                        </div>

                    </div>

                    <p>
                        Combined profile quality and CRM engagement score.
                    </p>

                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="lp-ai-metric-card">

                    <div class="lp-ai-metric-icon lp-ai-metric-blue">
                        <i class="fa-solid fa-address-card"></i>
                    </div>

                    <div>
                        <small>Profile Completion</small>
                        <strong>{{ $profileScore }}%</strong>
                    </div>

                    <div class="lp-ai-progress">
                        <span style="width: {{ $profileScore }}%"></span>
                    </div>

                    <p>
                        Based on website, email, phone, industry, location and description.
                    </p>

                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="lp-ai-metric-card">

                    <div class="lp-ai-metric-icon lp-ai-metric-purple">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>

                    <div>
                        <small>Engagement Score</small>
                        <strong>{{ $engagementScore }}%</strong>
                    </div>

                    <div class="lp-ai-progress lp-ai-progress-purple">
                        <span style="width: {{ $engagementScore }}%"></span>
                    </div>

                    <p>
                        Calculated from contacts, calls, meetings, notes and documents.
                    </p>

                </div>
            </div>

        </div>

        <div class="row g-4 mt-1">

            <div class="col-xl-7">

                <div class="lp-ai-insight-panel">

                    <div class="lp-ai-panel-heading">
                        <div>
                            <span class="lp-ai-panel-icon lp-ai-panel-icon-blue">
                                <i class="fa-solid fa-bullseye"></i>
                            </span>

                            <div>
                                <h5>Next Best Action</h5>
                                <p>Recommended from current CRM activity.</p>
                            </div>
                        </div>
                    </div>

                    <div class="lp-ai-next-action">

                        <div class="lp-ai-next-action-icon">
                            <i class="fa-solid fa-arrow-trend-up"></i>
                        </div>

                        <div>
                            <small>Recommended Action</small>
                            <strong>{{ $nextAction }}</strong>

                            <p>
                                Completing this action should improve engagement and move the company forward.
                            </p>
                        </div>

                    </div>

                    <div class="lp-ai-stats-grid">

                        <div>
                            <span>
                                <i class="fa-solid fa-address-book"></i>
                            </span>

                            <small>Contacts</small>
                            <strong>{{ $contacts->count() }}</strong>
                        </div>

                        <div>
                            <span>
                                <i class="fa-solid fa-list-check"></i>
                            </span>

                            <small>Open Tasks</small>
                            <strong>{{ $openTasks }}</strong>
                        </div>

                        <div>
                            <span>
                                <i class="fa-solid fa-calendar-days"></i>
                            </span>

                            <small>Meetings</small>
                            <strong>{{ $meetings->count() }}</strong>
                        </div>

                        <div>
                            <span>
                                <i class="fa-solid fa-phone"></i>
                            </span>

                            <small>Calls</small>
                            <strong>{{ $calls->count() }}</strong>
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-xl-5">

                <div class="lp-ai-insight-panel">

                    <div class="lp-ai-panel-heading">
                        <div>
                            <span class="lp-ai-panel-icon lp-ai-panel-icon-red">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </span>

                            <div>
                                <h5>Risk Indicators</h5>
                                <p>Items requiring attention.</p>
                            </div>
                        </div>
                    </div>

                    <div class="lp-ai-risk-list">

                        @forelse($riskItems as $risk)
                            <div class="lp-ai-risk-item">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <span>{{ $risk }}</span>
                            </div>
                        @empty
                            <div class="lp-ai-no-risk">
                                <i class="fa-solid fa-circle-check"></i>

                                <div>
                                    <strong>No major risks found</strong>
                                    <span>Company data and activity look healthy.</span>
                                </div>
                            </div>
                        @endforelse

                    </div>

                </div>

            </div>

        </div>

        <div class="lp-ai-phase-banner">

            <div class="lp-ai-phase-icon">
                <i class="fa-solid fa-robot"></i>
            </div>

            <div>
                <span>Phase A Integration</span>

                <h5>Advanced AI analysis will connect here next.</h5>

                <p>
                    Website analysis, technology detection, opportunity scoring,
                    service recommendations, proposal generation and personalized outreach.
                </p>
            </div>

            <button type="button" class="lp-btn lp-btn-primary" disabled>
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                Coming in Phase A
            </button>

        </div>

    </div>

</div>