<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
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

        $latestJobs = JobTask::with(['client', 'employee.user'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestJobs'));
    }
}
