<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Support\ActivityChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $year = (int) $request->input('year', now()->year);
        $month = $request->filled('month') ? (int) $request->input('month') : null;

        $periodReports = DailyReport::with('jobTask')
            ->where('user_id', $userId)
            ->whereYear('report_date', $year)
            ->when($month, fn($q) => $q->whereMonth('report_date', $month))
            ->get();

        $stats = [
            'total_reports' => $periodReports->count(),
            'avg_progress' => $periodReports->count() ? (int) round($periodReports->avg('progress')) : 0,
            'jobs_touched' => $periodReports->pluck('job_task_id')->unique()->count(),
            'with_obstacle' => $periodReports->filter(fn($r) => filled($r->obstacle))->count(),
        ];

        $chart = ActivityChart::build($periodReports, $month);

        $availableYears = DailyReport::where('user_id', $userId)
            ->selectRaw('DISTINCT YEAR(report_date) as year')
            ->pluck('year')
            ->push(now()->year)
            ->unique()
            ->sortDesc()
            ->values();

        return view('karyawan.dashboard', compact('stats', 'chart', 'availableYears', 'year', 'month'));
    }
}
