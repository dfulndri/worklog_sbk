<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\JobTask;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        $jobs = $employee
            ? JobTask::with(['client', 'documentType'])
            ->where('employee_id', $employee->id)
            ->latest()
            ->get()
            : collect();

        $stats = [
            'total' => $jobs->count(),
            'final' => $jobs->where('stage', 'final')->count(),
            'avg_progress' => $jobs->count() > 0 ? (int) round($jobs->avg('progress')) : 0,
            'near_deadline' => $jobs->filter(function ($job) {
                return $job->deadline
                    && $job->stage !== 'final'
                    && $job->deadline->isFuture()
                    && $job->deadline->diffInDays(now()) <= 7;
            })->count(),
        ];

        // Progress per pekerjaan untuk bar chart (maksimal 6 item terbaru).
        $progressChart = $jobs->take(6)->map(function ($job) {
            return [
                'label' => \Illuminate\Support\Str::limit($job->client->name ?? '-', 12),
                'value' => $job->progress,
            ];
        });

        return view('karyawan.dashboard', compact('jobs', 'stats', 'progressChart'));
    }
}
