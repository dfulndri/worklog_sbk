<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\JobTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DailyReportController extends Controller
{
    public function index()
    {
        $reports = DailyReport::with(['jobTask.client', 'jobTask.documentType'])
            ->where('user_id', Auth::id())
            ->latest('report_date')
            ->paginate(10);

        return view('karyawan.daily-reports.index', compact('reports'));
    }

    public function create()
    {
        $employee = Auth::user()->employee;

        $jobs = $employee
            ? JobTask::with(['client', 'documentType'])
            ->where('employee_id', $employee->id)
            ->orderBy('deadline')
            ->get()
            : collect();

        return view('karyawan.daily-reports.create', compact('jobs'));
    }

    public function store(Request $request)
    {
        $employee = Auth::user()->employee;

        $data = $request->validate([
            'job_task_id' => ['required', 'exists:job_tasks,id'],
            'report_date' => ['required', 'date'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'description' => ['required', 'string'],
            'obstacle' => ['nullable', 'string'],
            'next_plan' => ['nullable', 'string'],
        ]);

        // Pastikan pekerjaan yang dipilih memang milik karyawan yang sedang login.
        $job = JobTask::where('id', $data['job_task_id'])
            ->where('employee_id', $employee?->id)
            ->firstOrFail();

        DB::transaction(function () use ($data, $job) {
            DailyReport::create([
                'user_id' => Auth::id(),
                'job_task_id' => $job->id,
                'report_date' => $data['report_date'],
                'progress' => $data['progress'],
                'description' => $data['description'],
                'obstacle' => $data['obstacle'] ?? null,
                'next_plan' => $data['next_plan'] ?? null,
            ]);

            // Progress pekerjaan selalu mengikuti laporan terbaru.
            $job->update(['progress' => $data['progress']]);
        });

        return redirect()->route('karyawan.daily-reports.index')->with('success', 'Daily report berhasil disimpan.');
    }
}
