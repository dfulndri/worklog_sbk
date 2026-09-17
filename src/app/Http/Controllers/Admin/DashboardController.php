<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\DailyReport;
use App\Models\Employee;
use App\Models\Expert;
use App\Models\JobTask;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'clients' => Client::count(),
            'experts' => Expert::count(),
            'employees' => Employee::count(),
            'jobs' => JobTask::count(),
        ];

        $stageBreakdown = JobTask::selectRaw('stage, count(*) as total')
            ->groupBy('stage')
            ->pluck('total', 'stage');

        $latestJobs = JobTask::with(['client', 'employee.user'])
            ->latest()
            ->take(5)
            ->get();

        $upcomingDeadlines = JobTask::with(['client', 'employee.user'])
            ->whereNotNull('deadline')
            ->where('deadline', '>=', now()->toDateString())
            ->where('deadline', '<=', now()->addDays(7)->toDateString())
            ->where('stage', '!=', 'final')
            ->orderBy('deadline')
            ->take(5)
            ->get();

        $recentReports = DailyReport::with(['user', 'jobTask.client'])
            ->latest('report_date')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'stageBreakdown',
            'latestJobs',
            'upcomingDeadlines',
            'recentReports'
        ));
    }
}
