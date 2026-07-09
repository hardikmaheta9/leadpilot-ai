@extends('layouts.crm')

@section('content')

<div class="lp-dashboard-hero">
    <div>
        <h1>Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 18 ? 'Afternoon' : 'Evening') }}, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
        <p>Your AI-powered CRM command center is ready.</p>

        <div class="lp-hero-actions">
            <a href="{{ route('companies.create') }}" class="lp-btn lp-btn-primary">
                <i class="fa-solid fa-plus"></i> Add Company
            </a>

            <a href="{{ route('calendar.index') }}" class="lp-btn lp-btn-light">
                <i class="fa-solid fa-calendar-days"></i> Open Calendar
            </a>
        </div>
    </div>

    <div class="lp-hero-date">
        <strong>{{ now()->format('d') }}</strong>
        <span>{{ now()->format('F Y') }}</span>
    </div>
</div>

<div class="row">
    <x-ui.stat-card title="Companies" :value="$totalCompanies" icon="fa-solid fa-building" color="blue" />
    <x-ui.stat-card title="Contacts" :value="$totalContacts" icon="fa-solid fa-address-book" color="purple" />
    <x-ui.stat-card title="Open Tasks" :value="$openTasks" icon="fa-solid fa-list-check" color="orange" />
    <x-ui.stat-card title="Meetings Today" :value="$todayMeetings" icon="fa-solid fa-calendar-days" color="green" />
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <x-ui.section-card title="Business Overview" icon="fa-solid fa-chart-line">
            <canvas id="dashboardChart" height="115"></canvas>
        </x-ui.section-card>
    </div>

    <div class="col-lg-4">
        <x-ui.section-card title="AI Command Center" icon="fa-solid fa-robot">
            <div class="lp-ai-command">
                <div class="lp-ai-command-top">
                    <div class="lp-ai-icon">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                    </div>

                    <div>
                        <h5>Growth Engine Ready</h5>
                        <p>AI discovery will start after CRM v1.0.</p>
                    </div>
                </div>

                <div class="lp-ai-suggestion">
                    <span>🔥</span>
                    <div>
                        <strong>{{ $openTasks }} follow-ups pending</strong>
                        <small>Complete these before importing new leads.</small>
                    </div>
                </div>

                <div class="lp-ai-suggestion">
                    <span>📅</span>
                    <div>
                        <strong>{{ $todayMeetings }} meetings today</strong>
                        <small>Review notes after each meeting.</small>
                    </div>
                </div>

                <div class="lp-ai-suggestion">
                    <span>🚀</span>
                    <div>
                        <strong>Lead Discovery next</strong>
                        <small>Automatic lead engine will connect here.</small>
                    </div>
                </div>

                <a href="#" class="lp-btn lp-btn-primary w-100 justify-content-center mt-3">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    Prepare AI Engine
                </a>
            </div>
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
    window.dashboardStats = @json($chartData);
</script>

@endsection