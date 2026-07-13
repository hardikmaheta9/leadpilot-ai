<?php

namespace App\Http\Controllers;

use App\Models\CallLog;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Meeting;
use App\Models\Task;
use App\Models\CompanyAiProfile;
use App\Models\CompanyWebsiteAnalysis;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalCompanies = Company::count();
        $totalContacts = Contact::count();
        $totalTasks = Task::count();
        $totalMeetings = Meeting::count();
        $totalCalls = CallLog::count();

                /*
        |--------------------------------------------------------------------------
        | AI Dashboard Metrics
        |--------------------------------------------------------------------------
        */

        $totalAiAnalyzed = CompanyAiProfile::count();

        $highOpportunity = CompanyAiProfile::where('lead_score', '>=', 80)->count();

        $averageLeadScore = (int) CompanyAiProfile::avg('lead_score');

        $estimatedPipeline = CompanyAiProfile::sum('lead_score') * 5000;

        $topAiCompanies = CompanyAiProfile::with('company')
            ->orderByDesc('lead_score')
            ->take(5)
            ->get();

        $openTasks = Task::where('status', '!=', 'completed')->count();
        $todayMeetings = Meeting::whereDate('meeting_date', today())->count();

        /*
        |--------------------------------------------------------------------------
        | AI Analytics
        |--------------------------------------------------------------------------
        */

        $leadGradeDistribution = CompanyAiProfile::query()
            ->select('lead_grade', DB::raw('COUNT(*) as total'))
            ->whereNotNull('lead_grade')
            ->groupBy('lead_grade')
            ->pluck('total', 'lead_grade')
            ->toArray();

        $leadGradeChart = [
            'labels' => ['A', 'B', 'C', 'D', 'E'],
            'data' => [
                (int) ($leadGradeDistribution['A'] ?? 0),
                (int) ($leadGradeDistribution['B'] ?? 0),
                (int) ($leadGradeDistribution['C'] ?? 0),
                (int) ($leadGradeDistribution['D'] ?? 0),
                (int) ($leadGradeDistribution['E'] ?? 0),
            ],
        ];

        $websiteHealthChart = [
            'labels' => [
                'Excellent',
                'Good',
                'Average',
                'Poor',
            ],
            'data' => [
                CompanyWebsiteAnalysis::where('website_score', '>=', 80)->count(),

                CompanyWebsiteAnalysis::whereBetween(
                    'website_score',
                    [60, 79]
                )->count(),

                CompanyWebsiteAnalysis::whereBetween(
                    'website_score',
                    [40, 59]
                )->count(),

                CompanyWebsiteAnalysis::where(
                    'website_score',
                    '<',
                    40
                )->count(),
            ],
        ];

        $technologyDistribution = CompanyWebsiteAnalysis::query()
            ->select('cms', DB::raw('COUNT(*) as total'))
            ->whereNotNull('cms')
            ->where('cms', '!=', '')
            ->groupBy('cms')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        $technologyChart = [
            'labels' => $technologyDistribution
                ->pluck('cms')
                ->values()
                ->all(),

            'data' => $technologyDistribution
                ->pluck('total')
                ->map(fn ($value) => (int) $value)
                ->values()
                ->all(),
        ];

        $industryDistribution = CompanyAiProfile::query()
            ->select('industry', DB::raw('COUNT(*) as total'))
            ->whereNotNull('industry')
            ->where('industry', '!=', '')
            ->groupBy('industry')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        $industryChart = [
            'labels' => $industryDistribution
                ->pluck('industry')
                ->values()
                ->all(),

            'data' => $industryDistribution
                ->pluck('total')
                ->map(fn ($value) => (int) $value)
                ->values()
                ->all(),
        ];

        $averageWebsiteScore = (int) round(
            CompanyWebsiteAnalysis::avg('website_score') ?? 0
        );

        $averageSeoScore = (int) round(
            CompanyWebsiteAnalysis::avg('seo_score') ?? 0
        );

        $averagePerformanceScore = (int) round(
            CompanyWebsiteAnalysis::avg('performance_score') ?? 0
        );

        $topOpportunities = CompanyAiProfile::with([
                'company',
                'company.websiteAnalysis',
            ])
            ->where('lead_score', '>', 0)
            ->orderByDesc('lead_score')
            ->take(5)
            ->get();

        return view('dashboard.index', [
            'totalCompanies' => $totalCompanies,
            'totalContacts' => $totalContacts,
            'openTasks' => $openTasks,
            'todayMeetings' => $todayMeetings,
            'totalAiAnalyzed'   => $totalAiAnalyzed,
            'highOpportunity'   => $highOpportunity,
            'averageLeadScore'  => $averageLeadScore,
            'estimatedPipeline' => $estimatedPipeline,
            'topAiCompanies'    => $topAiCompanies,
            'leadGradeChart' => $leadGradeChart,
            'websiteHealthChart' => $websiteHealthChart,
            'technologyChart' => $technologyChart,
            'industryChart' => $industryChart,
            'averageWebsiteScore' => $averageWebsiteScore,
            'averageSeoScore' => $averageSeoScore,
            'averagePerformanceScore' => $averagePerformanceScore,
            'topOpportunities' => $topOpportunities,
            'upcomingMeetings' => Meeting::where('status', 'scheduled')
                ->whereDate('meeting_date', '>=', today())
                ->orderBy('meeting_date')
                ->orderBy('start_time')
                ->take(5)
                ->get(),

            'pendingTasks' => Task::where('status', '!=', 'completed')
                ->orderByRaw('due_date IS NULL')
                ->orderBy('due_date')
                ->take(5)
                ->get(),

            'recentCalls' => CallLog::latest('call_date')
                ->latest('call_time')
                ->take(5)
                ->get(),

            'chartData' => [
                'labels' => ['Companies', 'Contacts', 'Tasks', 'Meetings', 'Calls'],
                'data' => [
                    $totalCompanies,
                    $totalContacts,
                    $totalTasks,
                    $totalMeetings,
                    $totalCalls,
                ],
            ],
        ]);
    }
}