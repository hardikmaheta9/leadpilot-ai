@props([
    'company',
    'aiProfile' => null,
    'websiteAnalysis' => null,
    'contacts' => collect(),
    'tasks' => collect(),
    'meetings' => collect(),
    'calls' => collect(),
    'notes' => collect(),
    'documents' => collect(),
])

@php
    $recommendations = $company->aiRecommendations ?? collect();

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

    $openTasks = $tasks->where('status', '!=', 'completed')->count();

    $overdueTasks = $tasks->filter(function ($task) {
        return $task->due_date
            && $task->due_date->isPast()
            && $task->status !== 'completed';
    })->count();

    $upcomingMeetings = $meetings->filter(function ($meeting) {
        return $meeting->meeting_date
            && (
                $meeting->meeting_date->isToday()
                || $meeting->meeting_date->isFuture()
            );
    })->count();

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

    $pipelineMin = $recommendations->sum(
        fn ($item) => (int) ($item->estimated_value_min ?? 0)
    );

    $pipelineMax = $recommendations->sum(
        fn ($item) => (int) ($item->estimated_value_max ?? 0)
    );

    $averageBuyingProbability = $recommendations->count()
        ? (int) round($recommendations->avg('buying_probability'))
        : 0;
@endphp

<div class="lp-module-card">

    <div class="lp-module-header">
        <div>
            <span class="lp-module-eyebrow">Intelligence</span>
            <h4>AI Company Insights</h4>
            <p>
                Company health, website intelligence and opportunity recommendations.
            </p>
        </div>

        <div class="d-flex gap-2 flex-wrap align-items-center">

            <span class="lp-ai-engine-badge">
                <i class="fa-solid fa-wand-magic-sparkles"></i>
                AI Engine Ready
            </span>

            <form
                method="POST"
                action="{{ route('companies.ai.analyze', $company->uuid) }}"
            >
                @csrf

                <button type="submit" class="lp-btn lp-btn-primary">
                    <i class="fa-solid fa-robot"></i>

                    {{ $websiteAnalysis ? 'Re-analyze Website' : 'Analyze Website' }}
                </button>
            </form>

        </div>
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
                        style="--lp-score: {{ $healthScore }}"
                    >
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

                    <small>Profile Completion</small>
                    <strong>{{ $profileScore }}%</strong>

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

                    <small>Engagement Score</small>
                    <strong>{{ $engagementScore }}%</strong>

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
                            <span><i class="fa-solid fa-address-book"></i></span>
                            <small>Contacts</small>
                            <strong>{{ $contacts->count() }}</strong>
                        </div>

                        <div>
                            <span><i class="fa-solid fa-list-check"></i></span>
                            <small>Open Tasks</small>
                            <strong>{{ $openTasks }}</strong>
                        </div>

                        <div>
                            <span><i class="fa-solid fa-calendar-days"></i></span>
                            <small>Meetings</small>
                            <strong>{{ $meetings->count() }}</strong>
                        </div>

                        <div>
                            <span><i class="fa-solid fa-phone"></i></span>
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

        <div class="lp-ai-insight-panel mt-4">

            <div class="lp-ai-panel-heading">
                <div>
                    <span class="lp-ai-panel-icon lp-ai-panel-icon-blue">
                        <i class="fa-solid fa-globe"></i>
                    </span>

                    <div>
                        <h5>Website Intelligence</h5>
                        <p>Technical analysis and detected technologies.</p>
                    </div>
                </div>
            </div>

            <div class="p-4">

                @if(!$websiteAnalysis)

                    <x-ui.empty-state
                        icon="fa-solid fa-globe"
                        title="Website Not Analyzed"
                        message="Use the Analyze Website button to generate website scores, technology detection and AI recommendations."
                    />

                @else

                    <div class="row g-4">

                        <div class="col-xl-4 col-md-6">
                            <div class="lp-ai-metric-card h-100">
                                <div class="lp-ai-metric-icon lp-ai-metric-blue">
                                    <i class="fa-solid fa-chart-simple"></i>
                                </div>

                                <small>Website Score</small>
                                <strong>{{ $websiteAnalysis->website_score }}</strong>

                                <div class="lp-ai-progress">
                                    <span style="width: {{ min((int) $websiteAnalysis->website_score, 100) }}%"></span>
                                </div>

                                <p class="mb-0">
                                    Overall technical website health.
                                </p>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6">
                            <div class="lp-ai-metric-card h-100">
                                <div class="lp-ai-metric-icon lp-ai-metric-purple">
                                    <i class="fa-solid fa-magnifying-glass-chart"></i>
                                </div>

                                <small>SEO Score</small>
                                <strong>{{ $websiteAnalysis->seo_score }}</strong>

                                <div class="lp-ai-progress lp-ai-progress-purple">
                                    <span style="width: {{ min((int) $websiteAnalysis->seo_score, 100) }}%"></span>
                                </div>

                                <p class="mb-0">
                                    Basic on-page SEO quality.
                                </p>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6">
                            <div class="lp-ai-metric-card h-100">
                                <div class="lp-ai-metric-icon lp-ai-metric-blue">
                                    <i class="fa-solid fa-gauge-high"></i>
                                </div>

                                <small>Performance Score</small>
                                <strong>{{ $websiteAnalysis->performance_score }}</strong>

                                <div class="lp-ai-progress">
                                    <span style="width: {{ min((int) $websiteAnalysis->performance_score, 100) }}%"></span>
                                </div>

                                <p class="mb-0">
                                    Estimated front-end efficiency.
                                </p>
                            </div>
                        </div>

                    </div>

                    <div class="row g-4 mt-1">

                        <div class="col-xl-7">
                            <div class="lp-ai-insight-panel h-100">

                                <div class="lp-ai-panel-heading">
                                    <div>
                                        <span class="lp-ai-panel-icon lp-ai-panel-icon-blue">
                                            <i class="fa-solid fa-globe"></i>
                                        </span>

                                        <div>
                                            <h5>Website Details</h5>
                                            <p>Technical and structural information.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4">

                                    <div class="row g-3">

                                        <div class="col-md-6">
                                            <small class="text-muted">Website Title</small>
                                            <strong class="d-block">
                                                {{ $websiteAnalysis->website_title ?: 'Not detected' }}
                                            </strong>
                                        </div>

                                        <div class="col-md-6">
                                            <small class="text-muted">Website URL</small>

                                            @if($websiteAnalysis->website_url)
                                                <a
                                                    href="{{ $websiteAnalysis->website_url }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="d-block text-decoration-none"
                                                >
                                                    {{ $websiteAnalysis->website_url }}
                                                </a>
                                            @else
                                                <strong class="d-block">Not available</strong>
                                            @endif
                                        </div>

                                        <div class="col-md-4">
                                            <small class="text-muted">SSL Enabled</small>
                                            <strong class="d-block">
                                                {{ $websiteAnalysis->ssl_enabled ? 'Yes' : 'No' }}
                                            </strong>
                                        </div>

                                        <div class="col-md-4">
                                            <small class="text-muted">Mobile Friendly</small>
                                            <strong class="d-block">
                                                {{ $websiteAnalysis->mobile_friendly ? 'Yes' : 'No' }}
                                            </strong>
                                        </div>

                                        <div class="col-md-4">
                                            <small class="text-muted">Word Count</small>
                                            <strong class="d-block">
                                                {{ number_format((int) $websiteAnalysis->word_count) }}
                                            </strong>
                                        </div>

                                        <div class="col-md-4">
                                            <small class="text-muted">Images</small>
                                            <strong class="d-block">
                                                {{ number_format((int) $websiteAnalysis->images) }}
                                            </strong>
                                        </div>

                                        <div class="col-md-4">
                                            <small class="text-muted">Forms</small>
                                            <strong class="d-block">
                                                {{ number_format((int) $websiteAnalysis->forms) }}
                                            </strong>
                                        </div>

                                        <div class="col-md-4">
                                            <small class="text-muted">Scripts</small>
                                            <strong class="d-block">
                                                {{ number_format((int) $websiteAnalysis->scripts) }}
                                            </strong>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-xl-5">
                            <div class="lp-ai-insight-panel h-100">

                                <div class="lp-ai-panel-heading">
                                    <div>
                                        <span class="lp-ai-panel-icon lp-ai-panel-icon-blue">
                                            <i class="fa-solid fa-code"></i>
                                        </span>

                                        <div>
                                            <h5>Technology Stack</h5>
                                            <p>Detected website technologies.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4">

                                    <div class="mb-3">
                                        <small class="text-muted">CMS</small>
                                        <strong class="d-block">
                                            {{ $websiteAnalysis->cms ?: 'Not detected' }}
                                        </strong>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Framework</small>
                                        <strong class="d-block">
                                            {{ $websiteAnalysis->framework ?: 'Not detected' }}
                                        </strong>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Server</small>
                                        <strong class="d-block">
                                            {{ $websiteAnalysis->server ?: 'Not detected' }}
                                        </strong>
                                    </div>

                                    <div>
                                        <small class="text-muted d-block mb-2">
                                            Technologies
                                        </small>

                                        <div class="d-flex flex-wrap gap-2">
                                            @forelse($websiteAnalysis->technologies ?? [] as $technology)
                                                <span class="badge bg-light text-dark border">
                                                    {{ $technology }}
                                                </span>
                                            @empty
                                                <span class="text-muted small">
                                                    No technologies detected.
                                                </span>
                                            @endforelse
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                @endif

            </div>
        </div>

        <div class="lp-ai-insight-panel mt-4">

            <div class="lp-ai-panel-heading">
                <div>
                    <span class="lp-ai-panel-icon lp-ai-panel-icon-blue">
                        <i class="fa-solid fa-lightbulb"></i>
                    </span>

                    <div>
                        <h5>AI Recommendations & Opportunity Pipeline</h5>
                        <p>Service opportunities generated from company and website signals.</p>
                    </div>
                </div>

                @if($recommendations->isNotEmpty())
                    <span class="badge bg-primary-subtle text-primary border">
                        {{ $recommendations->count() }}
                        {{ \Illuminate\Support\Str::plural('opportunity', $recommendations->count()) }}
                    </span>
                @endif
            </div>

            <div class="p-4">

                @if($recommendations->isEmpty())

                    <x-ui.empty-state
                        icon="fa-solid fa-lightbulb"
                        title="No Recommendations Yet"
                        message="Analyze the website to generate service recommendations, buying probability and estimated project values."
                    />

                @else

                    <div class="row g-3 mb-4">

                        <div class="col-md-4">
                            <div class="lp-ai-metric-card h-100">
                                <div class="lp-ai-metric-icon lp-ai-metric-blue">
                                    <i class="fa-solid fa-indian-rupee-sign"></i>
                                </div>

                                <small>Estimated Pipeline</small>

                                <strong class="fs-5">
                                    ₹{{ number_format($pipelineMin) }}
                                    –
                                    ₹{{ number_format($pipelineMax) }}
                                </strong>

                                <p class="mb-0">
                                    Combined value of detected opportunities.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="lp-ai-metric-card h-100">
                                <div class="lp-ai-metric-icon lp-ai-metric-purple">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>

                                <small>Buying Probability</small>
                                <strong>{{ $averageBuyingProbability }}%</strong>

                                <div class="lp-ai-progress lp-ai-progress-purple">
                                    <span style="width: {{ min($averageBuyingProbability, 100) }}%"></span>
                                </div>

                                <p class="mb-0">
                                    Average probability across recommendations.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="lp-ai-metric-card h-100">
                                <div class="lp-ai-metric-icon lp-ai-metric-blue">
                                    <i class="fa-solid fa-fire"></i>
                                </div>

                                <small>High Priority</small>
                                <strong>
                                    {{ $recommendations->where('priority', 'high')->count() }}
                                </strong>

                                <p class="mb-0">
                                    Opportunities requiring immediate attention.
                                </p>
                            </div>
                        </div>

                    </div>

                    <div class="row g-4">

                        @foreach($recommendations as $recommendation)

                            @php
                                $priorityClass = match($recommendation->priority) {
                                    'high' => 'danger',
                                    'medium' => 'warning',
                                    default => 'secondary',
                                };
                            @endphp

                            <div class="col-xl-6">
                                <div class="lp-ai-insight-panel h-100">

                                    <div class="lp-ai-panel-heading">

                                        <div>
                                            <span class="lp-ai-panel-icon lp-ai-panel-icon-blue">
                                                <i class="fa-solid fa-bullseye"></i>
                                            </span>

                                            <div>
                                                <h5>{{ $recommendation->title }}</h5>
                                                <p>
                                                    {{ $recommendation->recommended_service ?: 'Recommended service not specified' }}
                                                </p>
                                            </div>
                                        </div>

                                        <span class="badge bg-{{ $priorityClass }}-subtle text-{{ $priorityClass }} border">
                                            {{ ucfirst($recommendation->priority) }}
                                        </span>

                                    </div>

                                    <div class="p-4">

                                        @if($recommendation->description)
                                            <p class="text-muted">
                                                {{ $recommendation->description }}
                                            </p>
                                        @endif

                                        <div class="row g-3 mb-4">

                                            <div class="col-6">
                                                <small class="text-muted d-block">
                                                    Priority Score
                                                </small>

                                                <strong>
                                                    {{ $recommendation->priority_score }}/100
                                                </strong>
                                            </div>

                                            <div class="col-6">
                                                <small class="text-muted d-block">
                                                    Buying Probability
                                                </small>

                                                <strong>
                                                    {{ $recommendation->buying_probability }}%
                                                </strong>
                                            </div>

                                            <div class="col-12">
                                                <small class="text-muted d-block">
                                                    Estimated Project Value
                                                </small>

                                                <strong>
                                                    ₹{{ number_format((int) $recommendation->estimated_value_min) }}
                                                    –
                                                    ₹{{ number_format((int) $recommendation->estimated_value_max) }}
                                                </strong>
                                            </div>

                                        </div>

                                        <div class="lp-ai-progress mb-4">
                                            <span style="width: {{ min((int) $recommendation->buying_probability, 100) }}%"></span>
                                        </div>

                                        @if($recommendation->reason)
                                            <div class="mb-4">
                                                <small class="text-muted d-block mb-1">
                                                    Why this opportunity exists
                                                </small>

                                                <strong class="d-block">
                                                    {{ $recommendation->reason }}
                                                </strong>
                                            </div>
                                        @endif

                                        @if(!empty($recommendation->evidence))
                                            <div class="mb-4">
                                                <small class="text-muted d-block mb-2">
                                                    Evidence
                                                </small>

                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($recommendation->evidence as $key => $value)
                                                        <span class="badge bg-light text-dark border">
                                                            {{ \Illuminate\Support\Str::headline($key) }}:
                                                            {{ is_bool($value) ? ($value ? 'Yes' : 'No') : $value }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if(!empty($recommendation->suggested_actions))
                                            <div>
                                                <small class="text-muted d-block mb-2">
                                                    Suggested Sales Actions
                                                </small>

                                                <ul class="mb-0 ps-3">
                                                    @foreach($recommendation->suggested_actions as $action)
                                                        <li class="mb-2">
                                                            {{ $action }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                            </div>

                        @endforeach

                    </div>

                @endif

            </div>
        </div>

    </div>

</div>
