<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\DailyReport;
use App\Models\Employee;
use App\Models\Expert;
use App\Models\JobTask;
use App\Support\ActivityChart;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Total master data — selalu global, tidak terikat periode.
        $stats = [
            'clients' => Client::count(),
            'experts' => Expert::count(),
            'employees' => Employee::count(),
            'jobs' => JobTask::count(),
        ];

        $stageBreakdown = JobTask::selectRaw('stage, count(*) as total')
            ->groupBy('stage')
            ->pluck('total', 'stage');

        $upcomingDeadlines = JobTask::with(['client', 'employee.user'])
            ->whereNotNull('deadline')
            ->where('deadline', '>=', now()->toDateString())
            ->where('deadline', '<=', now()->addDays(7)->toDateString())
            ->where('stage', '!=', 'final')
            ->orderBy('deadline')
            ->take(5)
            ->get();

        $latestJobs = JobTask::with(['client', 'employee.user'])
            ->latest()
            ->take(5)
            ->get();

        // Aktivitas periode terpilih (tahun/bulan).
        $year = (int) $request->input('year', now()->year);
        $month = $request->filled('month') ? (int) $request->input('month') : null;

        $periodReports = DailyReport::with(['user', 'jobTask.client'])
            ->whereYear('report_date', $year)
            ->when($month, fn($q) => $q->whereMonth('report_date', $month))
            ->get();

        $periodJobsCreated = JobTask::whereYear('created_at', $year)
            ->when($month, fn($q) => $q->whereMonth('created_at', $month))
            ->count();

        $periodStats = [
            'reports_in' => $periodReports->count(),
            'jobs_created' => $periodJobsCreated,
            'avg_progress' => $periodReports->count() ? (int) round($periodReports->avg('progress')) : 0,
        ];

        // Grafik aktivitas per karyawan: satu seri (warna) per karyawan.
        // Dibangun pakai foreach + array biasa (bukan Collection chain panjang)
        // supaya expresinya aman dipakai langsung di @json() Blade.
        $bucketLabels = ActivityChart::bucketLabels($year, $month);
        $palette = ActivityChart::palette();
        $employees = Employee::with('user')->orderBy('id')->get();

        $employeeDatasets = [];
        foreach ($employees as $index => $employee) {
            $userReports = $periodReports->where('user_id', $employee->user_id);

            $data = [];
            foreach ($bucketLabels as $i => $label) {
                $bucket = $i + 1;
                $data[] = $userReports->filter(
                    fn($r) => ActivityChart::bucketOf($r->report_date, $month) === $bucket
                )->count();
            }

            if (array_sum($data) === 0) {
                continue;
            }

            $employeeDatasets[] = [
                'label' => $employee->user->name ?? 'Karyawan',
                'data' => $data,
                'backgroundColor' => $palette[$index % count($palette)],
                'borderRadius' => 6,
                'maxBarThickness' => 22,
            ];
        }

        $recentReports = $periodReports->sortByDesc('report_date')->take(8)->values();

        $availableYears = DailyReport::selectRaw('DISTINCT YEAR(report_date) as year')
            ->pluck('year')
            ->push(now()->year)
            ->unique()
            ->sortDesc()
            ->values();

        return view('admin.dashboard', compact(
            'stats',
            'stageBreakdown',
            'upcomingDeadlines',
            'latestJobs',
            'periodStats',
            'bucketLabels',
            'employeeDatasets',
            'recentReports',
            'availableYears',
            'year',
            'month'
        ));
    }
}
