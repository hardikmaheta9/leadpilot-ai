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
        return view('dashboard.index', [
            'totalCompanies' => Company::count(),
            'totalContacts' => Contact::count(),
            'openTasks' => Task::where('status', '!=', 'completed')->count(),
            'todayMeetings' => Meeting::whereDate('meeting_date', today())->count(),

            'upcomingMeetings' => Meeting::where('status', 'scheduled')
                ->whereDate('meeting_date', '>=', today())
                ->orderBy('meeting_date')
                ->take(5)
                ->get(),

            'pendingTasks' => Task::where('status', '!=', 'completed')
                ->orderBy('due_date')
                ->take(5)
                ->get(),

            'recentCalls' => CallLog::latest()
                ->take(5)
                ->get(),

            'chartData' => [
                'companies' => Company::count(),
                'contacts' => Contact::count(),
                'tasks' => Task::count(),
                'meetings' => Meeting::count(),
                'calls' => CallLog::count(),
            ],
        ]);
    }
}