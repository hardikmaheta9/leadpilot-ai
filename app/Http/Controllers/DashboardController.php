<?php

namespace App\Http\Controllers;

use App\Models\CallLog;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Meeting;
use App\Models\Task;
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

        $openTasks = Task::where('status', '!=', 'completed')->count();
        $todayMeetings = Meeting::whereDate('meeting_date', today())->count();

        return view('dashboard.index', [
            'totalCompanies' => $totalCompanies,
            'totalContacts' => $totalContacts,
            'openTasks' => $openTasks,
            'todayMeetings' => $todayMeetings,

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