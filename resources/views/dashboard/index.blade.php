@extends('layouts.crm')

@section('content')

<div class="lp-dashboard-hero">

    <div class="lp-hero-left">

        <span class="lp-ai-badge">
            <i class="fa-solid fa-sparkles"></i>
            AI Sales Intelligence
        </span>

        <h1>
            Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 18 ? 'Afternoon' : 'Evening') }},
            {{ explode(' ', auth()->user()->name)[0] }} 👋
        </h1>

        <p class="lp-hero-subtitle">

            I analyzed

            <strong>{{ number_format($totalAiAnalyzed) }}</strong>

            companies.

            There are

            <strong>{{ number_format($highOpportunity) }}</strong>

            high opportunity prospects ready for follow-up.

        </p>

        <div class="lp-ai-brief">

            <div class="lp-ai-brief-item">

                <span>🎯</span>

                <div>

                    <strong>{{ number_format($highOpportunity) }}</strong>

                    <small>High Opportunity Companies</small>

                </div>

            </div>

            <div class="lp-ai-brief-item">

                <span>📈</span>

                <div>

                    <strong>{{ $averageLeadScore }}/100</strong>

                    <small>Average Lead Score</small>

                </div>

            </div>

            <div class="lp-ai-brief-item">

                <span>💰</span>

                <div>

                    <strong>₹{{ number_format($estimatedPipeline) }}</strong>

                    <small>Estimated Pipeline</small>

                </div>

            </div>

        </div>

        <div class="lp-hero-actions">

            <a
                href="{{ route('companies.create') }}"
                class="lp-btn lp-btn-primary">

                <i class="fa-solid fa-plus"></i>

                Add Company

            </a>

            <a
                href="{{ route('calendar.index') }}"
                class="lp-btn lp-btn-light">

                <i class="fa-solid fa-calendar-days"></i>

                Calendar

            </a>

        </div>

    </div>

    <div class="lp-hero-right">

        <div class="lp-ai-score-circle">

            <span>{{ $averageLeadScore }}</span>

            <small>AI Score</small>

        </div>

        <div class="lp-hero-date">

            <strong>{{ now()->format('d') }}</strong>

            <span>{{ now()->format('F Y') }}</span>

        </div>

    </div>

</div>

<div class="row">
    <x-ui.stat-card title="Companies" :value="$totalCompanies" icon="fa-solid fa-building" color="blue" />
    <x-ui.stat-card title="Contacts" :value="$totalContacts" icon="fa-solid fa-address-book" color="purple" />
    <x-ui.stat-card title="Open Tasks" :value="$openTasks" icon="fa-solid fa-list-check" color="orange" />
    <x-ui.stat-card title="Meetings Today" :value="$todayMeetings" icon="fa-solid fa-calendar-days" color="green" />
</div>

<div class="row mt-4">

    <div class="col-xl-8">

        <x-ui.section-card
            title="AI Analytics"
            icon="fa-solid fa-chart-line">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="lp-analytics-card">

                        <h6>Lead Grade Distribution</h6>

                        <canvas id="leadGradeChart"></canvas>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="lp-analytics-card">

                        <h6>Website Health</h6>

                        <canvas id="websiteHealthChart"></canvas>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="lp-analytics-card">

                        <h6>Technology Distribution</h6>

                        <canvas id="technologyChart"></canvas>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="lp-analytics-card">

                        <h6>Industry Distribution</h6>

                        <canvas id="industryChart"></canvas>

                    </div>

                </div>

            </div>

        </x-ui.section-card>

    </div>

    <div class="col-xl-4">

        <x-ui.section-card
            title="Top Opportunities"
            icon="fa-solid fa-fire">

            @forelse($topOpportunities as $item)

                <div class="lp-opportunity-card">

                    <div>

                        <strong>

                            {{ $item->company->company_name }}

                        </strong>

                        <small>

                            Grade {{ $item->lead_grade }}

                            ·

                            Website

                            {{ optional($item->company->websiteAnalysis)->website_score ?? 0 }}

                        </small>

                    </div>

                    <div class="lp-opportunity-score">

                        {{ $item->lead_score }}

                    </div>

                </div>

            @empty

                <x-ui.empty-state

                    icon="fa-solid fa-fire"

                    title="No AI Opportunities"

                    subtitle="Run AI analysis for companies."

                />

            @endforelse

        </x-ui.section-card>

    </div>

</div>

<div class="row mt-4">

    <div class="col-lg-6">

        <x-dashboard.quick-actions />

    </div>

    <div class="col-lg-6">

        <x-dashboard.recent-activity />

    </div>

</div>

<div class="row mt-4">

    <div class="col-lg-4">
        <x-ui.section-card title="Upcoming Meetings" icon="fa-solid fa-calendar-days">
            @forelse($upcomingMeetings as $meeting)
                <div class="lp-dashboard-item">
                    <div class="lp-dashboard-item-icon lp-blue">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>

                    <div>
                        <strong>{{ $meeting->title }}</strong>
                        <small>
                            {{ $meeting->meeting_date->format('d M Y') }}
                            · {{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}
                        </small>
                    </div>
                </div>
            @empty
                <x-ui.empty-state
                    icon="fa-solid fa-calendar-days"
                    title="No Meetings"
                    subtitle="You're free today."
                />
            @endforelse
        </x-ui.section-card>
    </div>

    <div class="col-lg-4">
        <x-ui.section-card title="Pending Tasks" icon="fa-solid fa-list-check">
            @forelse($pendingTasks as $task)
                <div class="lp-dashboard-item">
                    <div class="lp-dashboard-item-icon {{ $task->due_date && $task->due_date->isPast() ? 'lp-red' : 'lp-orange' }}">
                        <i class="fa-solid fa-list-check"></i>
                    </div>

                    <div>
                        <strong>{{ $task->title }}</strong>
                        <small class="{{ $task->due_date && $task->due_date->isPast() ? 'text-danger fw-bold' : '' }}">
                            {{ $task->due_date ? $task->due_date->format('d M Y') : 'No due date' }}
                        </small>
                    </div>
                </div>
            @empty
                <x-ui.empty-state
                    icon="fa-solid fa-check"
                    title="Nothing Pending"
                    subtitle="Great job!"
                />
            @endforelse
        </x-ui.section-card>
    </div>

    <div class="col-lg-4">
        <x-ui.section-card title="Recent Calls" icon="fa-solid fa-phone">
            @forelse($recentCalls as $call)
                <div class="lp-dashboard-item">
                    <div class="lp-dashboard-item-icon {{ $call->call_type === 'incoming' ? 'lp-green' : 'lp-purple' }}">
                        <i class="fa-solid fa-phone"></i>
                    </div>

                    <div>
                        <strong>{{ $call->subject }}</strong>
                        <small>
                            {{ $call->call_date->format('d M Y') }}
                            · {{ ucfirst($call->call_type) }}
                        </small>
                    </div>
                </div>
            @empty
                <x-ui.empty-state
                    icon="fa-solid fa-phone"
                    title="No Calls"
                    subtitle="Nothing recorded yet."
                />
            @endforelse
        </x-ui.section-card>
    </div>

</div>

<script>

window.dashboardStats=@json($chartData);

window.aiCharts={

leadGrade:@json($leadGradeChart),

websiteHealth:@json($websiteHealthChart),

technology:@json($technologyChart),

industry:@json($industryChart)

};

</script>

@endsection