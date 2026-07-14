@props([
    'company',
    'aiProfile' => null,
    'websiteAnalysis' => null,
    'aiSalesConsultant' => null,
    'contacts' => collect(),
    'tasks' => collect(),
    'meetings' => collect(),
    'calls' => collect(),
    'notes' => collect(),
    'documents' => collect(),
    
])

<div class="lp-ai-dashboard">

    <div class="lp-module-card">

        <div class="lp-module-header">

            <div>
                <span class="lp-module-eyebrow">
                    Artificial Intelligence
                </span>

                <h4>Company Intelligence</h4>

                <p>
                    Website analysis, lead scoring and AI-generated company insights.
                </p>
            </div>

            <form
                method="POST"
                action="{{ route('companies.ai.analyze', $company->uuid) }}">

                @csrf

                <button
                    type="submit"
                    class="lp-btn lp-btn-primary">

                    <i class="fa-solid fa-robot"></i>

                    {{ $aiProfile ? 'Run Analysis Again' : 'Analyze Website' }}
                </button>

            </form>

        </div>

        <div class="lp-module-body">

            @if(!$aiProfile)

                <x-ui.empty-state
                    icon="fa-solid fa-robot"
                    title="AI Analysis Not Available"
                    message="Run the first website analysis to generate company intelligence, website scores and lead insights."
                />

            @else

                <div class="row g-4 mb-4">

                    <div class="col-xl-4 col-md-6">

                        <div class="lp-ai-score-card lp-ai-score-primary">

                            <div class="lp-ai-score-header">
                                <span>Lead Opportunity Score</span>
                                <i class="fa-solid fa-bullseye"></i>
                            </div>

                            <div
                                class="lp-ai-score-ring"
                                style="--lp-score: {{ $aiProfile->lead_score }}">

                                <div>
                                    <strong>{{ $aiProfile->lead_score }}</strong>
                                    <span>out of 100</span>
                                </div>

                            </div>

                            <p>
                                Grade {{ $aiProfile->lead_grade ?: 'N/A' }}
                            </p>

                        </div>

                    </div>

                    <div class="col-xl-4 col-md-6">

                        <div class="lp-ai-score-card lp-ai-score-success">

                            <div class="lp-ai-score-header">
                                <span>Website Score</span>
                                <i class="fa-solid fa-globe"></i>
                            </div>

                            <div
                                class="lp-ai-score-ring"
                                style="--lp-score: {{ $websiteAnalysis->website_score ?? 0 }}">

                                <div>
                                    <strong>
                                        {{ $websiteAnalysis->website_score ?? 0 }}
                                    </strong>

                                    <span>out of 100</span>
                                </div>

                            </div>

                            <p>
                                Overall website quality
                            </p>

                        </div>

                    </div>

                    <div class="col-xl-4 col-md-6">

                        <div class="lp-ai-score-card lp-ai-score-warning">

                            <div class="lp-ai-score-header">
                                <span>Confidence Score</span>
                                <i class="fa-solid fa-chart-line"></i>
                            </div>

                            <div
                                class="lp-ai-score-ring"
                                style="--lp-score: {{ $aiProfile->confidence_score }}">

                                <div>
                                    <strong>
                                        {{ $aiProfile->confidence_score }}
                                    </strong>

                                    <span>percent</span>
                                </div>

                            </div>

                            <p>
                                Data confidence level
                            </p>

                        </div>

                    </div>

                </div>

                <div class="row g-4">

                    <div class="col-xl-8">

                        <div class="lp-ai-insight-panel">

                            <div class="lp-ai-panel-heading">

                                <div>
                                    <span class="lp-ai-panel-icon lp-ai-panel-icon-blue">
                                        <i class="fa-solid fa-building"></i>
                                    </span>

                                    <div>
                                        <h5>Company Summary</h5>
                                        <p>AI-generated company profile</p>
                                    </div>
                                </div>

                            </div>

                            <div class="p-4">

                                <p class="mb-0 text-muted">
                                    {{ $aiProfile->company_summary ?: 'No company summary available.' }}
                                </p>

                            </div>

                        </div>

                    </div>

                    <div class="col-xl-4">

                        <div class="lp-ai-insight-panel">

                            <div class="lp-ai-panel-heading">

                                <div>
                                    <span class="lp-ai-panel-icon lp-ai-panel-icon-blue">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </span>

                                    <div>
                                        <h5>Business Profile</h5>
                                        <p>Core company details</p>
                                    </div>
                                </div>

                            </div>

                            <div class="p-4">

                                <div class="mb-3">
                                    <small class="text-muted">Industry</small>
                                    <strong class="d-block">
                                        {{ $aiProfile->industry ?: 'Not detected' }}
                                    </strong>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Business Type</small>
                                    <strong class="d-block">
                                        {{ $aiProfile->business_type ?: 'Not available' }}
                                    </strong>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Headquarters</small>
                                    <strong class="d-block">
                                        {{ $aiProfile->headquarters ?: 'Unknown' }}
                                    </strong>
                                </div>

                                <div>
                                    <small class="text-muted">Last Analysis</small>
                                    <strong class="d-block">
                                        {{ optional($aiProfile->last_analyzed_at)->diffForHumans() ?: 'Not available' }}
                                    </strong>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                @if($websiteAnalysis)

                    <div class="row g-4 mt-1">

                        <div class="col-xl-7">

                            <div class="lp-ai-insight-panel">

                                <div class="lp-ai-panel-heading">

                                    <div>
                                        <span class="lp-ai-panel-icon lp-ai-panel-icon-blue">
                                            <i class="fa-solid fa-globe"></i>
                                        </span>

                                        <div>
                                            <h5>Website Analysis</h5>
                                            <p>Technical and structural website data</p>
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
                                                    class="d-block text-decoration-none">

                                                    {{ $websiteAnalysis->website_url }}
                                                </a>
                                            @else
                                                <strong class="d-block">Not available</strong>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <small class="text-muted">SSL Enabled</small>
                                            <strong class="d-block">
                                                {{ $websiteAnalysis->ssl_enabled ? 'Yes' : 'No' }}
                                            </strong>
                                        </div>

                                        <div class="col-md-6">
                                            <small class="text-muted">Mobile Friendly</small>
                                            <strong class="d-block">
                                                {{ $websiteAnalysis->mobile_friendly ? 'Yes' : 'No' }}
                                            </strong>
                                        </div>

                                        <div class="col-md-4">
                                            <small class="text-muted">Images</small>
                                            <strong class="d-block">
                                                {{ number_format($websiteAnalysis->images) }}
                                            </strong>
                                        </div>

                                        <div class="col-md-4">
                                            <small class="text-muted">Forms</small>
                                            <strong class="d-block">
                                                {{ number_format($websiteAnalysis->forms) }}
                                            </strong>
                                        </div>

                                        <div class="col-md-4">
                                            <small class="text-muted">Word Count</small>
                                            <strong class="d-block">
                                                {{ number_format($websiteAnalysis->word_count) }}
                                            </strong>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-xl-5">

                            <div class="lp-ai-insight-panel">

                                <div class="lp-ai-panel-heading">

                                    <div>
                                        <span class="lp-ai-panel-icon lp-ai-panel-icon-blue">
                                            <i class="fa-solid fa-code"></i>
                                        </span>

                                        <div>
                                            <h5>Technology Stack</h5>
                                            <p>Detected website technologies</p>
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
                                            Detected Technologies
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

                    <div class="row g-4 mt-1">

                        <div class="col-md-4">
                            <div class="lp-ai-metric-card">
                                <div class="lp-ai-metric-icon lp-ai-metric-blue">
                                    <i class="fa-solid fa-magnifying-glass-chart"></i>
                                </div>

                                <small>SEO Score</small>
                                <strong>{{ $websiteAnalysis->seo_score }}</strong>

                                <div class="lp-ai-progress">
                                    <span style="width: {{ $websiteAnalysis->seo_score }}%"></span>
                                </div>

                                <p>Basic on-page SEO quality.</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="lp-ai-metric-card">
                                <div class="lp-ai-metric-icon lp-ai-metric-purple">
                                    <i class="fa-solid fa-gauge-high"></i>
                                </div>

                                <small>Performance Score</small>
                                <strong>{{ $websiteAnalysis->performance_score }}</strong>

                                <div class="lp-ai-progress lp-ai-progress-purple">
                                    <span style="width: {{ $websiteAnalysis->performance_score }}%"></span>
                                </div>

                                <p>Estimated front-end efficiency.</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="lp-ai-metric-card">
                                <div class="lp-ai-metric-icon lp-ai-metric-blue">
                                    <i class="fa-solid fa-chart-simple"></i>
                                </div>

                                <small>Website Score</small>
                                <strong>{{ $websiteAnalysis->website_score }}</strong>

                                <div class="lp-ai-progress">
                                    <span style="width: {{ $websiteAnalysis->website_score }}%"></span>
                                </div>

                                <p>Overall technical website health.</p>
                            </div>
                        </div>

                    </div>

                @endif

                @php
                    $aiRecommendations = $company->aiRecommendations ?? collect();
                    $totalEstimatedMin = $aiRecommendations->sum(fn ($item) => (int) ($item->estimated_value_min ?? 0));
                    $totalEstimatedMax = $aiRecommendations->sum(fn ($item) => (int) ($item->estimated_value_max ?? 0));
                    $averageBuyingProbability = $aiRecommendations->count()
                        ? (int) round($aiRecommendations->avg('buying_probability'))
                        : 0;
                @endphp

                <div class="lp-ai-insight-panel mt-4">
                    <div class="lp-ai-panel-heading">
                        <div>
                            <span class="lp-ai-panel-icon lp-ai-panel-icon-blue">
                                <i class="fa-solid fa-lightbulb"></i>
                            </span>
                            <div>
                                <h5>AI Recommendations & Opportunity Pipeline</h5>
                                <p>Actionable sales opportunities generated from the latest website analysis.</p>
                            </div>
                        </div>

                        @if($aiRecommendations->isNotEmpty())
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                {{ $aiRecommendations->count() }}
                                {{ \Illuminate\Support\Str::plural('opportunity', $aiRecommendations->count()) }}
                            </span>
                        @endif
                    </div>

                    <div class="p-4">
                        @if($aiRecommendations->isEmpty())
                            <x-ui.empty-state
                                icon="fa-solid fa-lightbulb"
                                title="No Recommendations Yet"
                                message="Run or repeat the website analysis to generate sales recommendations, buying probability and estimated project values."
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
                                            ₹{{ number_format($totalEstimatedMin) }} – ₹{{ number_format($totalEstimatedMax) }}
                                        </strong>
                                        <p class="mb-0">Combined value of all detected opportunities.</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="lp-ai-metric-card h-100">
                                        <div class="lp-ai-metric-icon lp-ai-metric-purple">
                                            <i class="fa-solid fa-chart-line"></i>
                                        </div>
                                        <small>Average Buying Probability</small>
                                        <strong>{{ $averageBuyingProbability }}%</strong>
                                        <div class="lp-ai-progress lp-ai-progress-purple">
                                            <span style="width: {{ min($averageBuyingProbability, 100) }}%"></span>
                                        </div>
                                        <p class="mb-0">Average probability across generated recommendations.</p>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="lp-ai-metric-card h-100">
                                        <div class="lp-ai-metric-icon lp-ai-metric-blue">
                                            <i class="fa-solid fa-fire"></i>
                                        </div>
                                        <small>High Priority</small>
                                        <strong>{{ $aiRecommendations->where('priority', 'high')->count() }}</strong>
                                        <p class="mb-0">Opportunities requiring immediate sales attention.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                @foreach($aiRecommendations as $recommendation)
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
                                                        <p>{{ $recommendation->recommended_service ?: 'Recommended service not specified' }}</p>
                                                    </div>
                                                </div>

                                                <span class="badge bg-{{ $priorityClass }}-subtle text-{{ $priorityClass }} border border-{{ $priorityClass }}-subtle">
                                                    {{ ucfirst($recommendation->priority) }} Priority
                                                </span>
                                            </div>

                                            <div class="p-4">
                                                @if($recommendation->description)
                                                    <p class="text-muted">{{ $recommendation->description }}</p>
                                                @endif

                                                <div class="row g-3 mb-4">
                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Priority Score</small>
                                                        <strong>{{ $recommendation->priority_score }}/100</strong>
                                                    </div>

                                                    <div class="col-6">
                                                        <small class="text-muted d-block">Buying Probability</small>
                                                        <strong>{{ $recommendation->buying_probability }}%</strong>
                                                    </div>

                                                    <div class="col-12">
                                                        <small class="text-muted d-block">Estimated Project Value</small>
                                                        <strong>
                                                            ₹{{ number_format((int) $recommendation->estimated_value_min) }} –
                                                            ₹{{ number_format((int) $recommendation->estimated_value_max) }}
                                                        </strong>
                                                    </div>
                                                </div>

                                                <div class="lp-ai-progress mb-4">
                                                    <span style="width: {{ min((int) $recommendation->buying_probability, 100) }}%"></span>
                                                </div>

                                                @if($recommendation->reason)
                                                    <div class="mb-4">
                                                        <small class="text-muted d-block mb-1">Why this opportunity exists</small>
                                                        <strong class="d-block">{{ $recommendation->reason }}</strong>
                                                    </div>
                                                @endif

                                                @if(!empty($recommendation->evidence))
                                                    <div class="mb-4">
                                                        <small class="text-muted d-block mb-2">Evidence</small>
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
                                                        <small class="text-muted d-block mb-2">Suggested Sales Actions</small>
                                                        <ul class="mb-0 ps-3">
                                                            @foreach($recommendation->suggested_actions as $action)
                                                                <li class="mb-2">{{ $action }}</li>
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

            @endif

        </div>

    </div>

</div>