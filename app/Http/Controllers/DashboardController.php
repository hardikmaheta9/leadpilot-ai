<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Company;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard.index', [
            'pageTitle' => 'Dashboard',
            'totalCompanies' => Company::count(),
            'prospectCompanies' => Company::where('status', 'prospect')->count(),
            'qualifiedCompanies' => Company::where('status', 'qualified')->count(),
            'customerCompanies' => Company::where('status', 'customer')->count(),
            'recentCompanies' => Company::latest()->take(5)->get(),
            'recentActivities' => Activity::latest()->take(8)->get(),
        ]);
    }
}