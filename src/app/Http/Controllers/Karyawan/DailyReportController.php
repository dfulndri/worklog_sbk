<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\JobStage;
use App\Models\JobTask;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = DailyReport::with(['jobTask.client', 'jobTask.documentCategory', 'stage'])
            ->where('user_id', Auth::id())
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('description', 'like', "%{$search}%")
                        ->orWhereHas('jobTask.client', fn($c) => $c->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest('report_date')
            ->paginate(10)
            ->withQueryString();

        return view('karyawan.daily-reports.index', compact('reports'));
    }

    public function create()
    {
        $jobs = $this->myJobs();
        $stages = JobStage::orderBy('order_no')->get();

        return view('karyawan.daily-reports.create', compact('jobs', 'stages'));
    }

    public function store(Request $request)
    {
        $job = $this->ownedJob($request->input('job_task_id'));

        $data = $this->validated($request);

        DB::transaction(function () use ($data, $job) {
            DailyReport::create([
                'user_id' => Auth::id(),
                'job_task_id' => $job->id,
                'stage_id' => $data['stage_id'],
                'report_date' => $data['report_date'],
                'progress' => $data['progress'],
                'description' => $data['description'],
                'obstacle' => $data['obstacle'] ?? null,
                'next_plan' => $data['next_plan'] ?? null,
            ]);

            // Progress & tahapan pekerjaan selalu mengikuti laporan terbaru.
            $job->update(['progress' => $data['progress'], 'stage_id' => $data['stage_id']]);

            // Beri tahu semua admin ada aktivitas daily report baru.
            $job->loadMissing('client', 'documentCategory');
            $karyawanName = Auth::user()->name;

            User::where('role', 'admin')->get()->each(function (User $admin) use ($karyawanName, $data, $job) {
                $admin->pushNotification(
                    title: "{$karyawanName} melaporkan progress {$data['progress']}%",
                    message: "{$job->client->name} — {$job->documentCategory->name}",
                    url: route('admin.jobs.show', $job),
                );
            });
        });

        return redirect()->route('karyawan.daily-reports.index')->with('success', 'Daily report berhasil disimpan.');
    }

    public function edit(DailyReport $daily_report)
    {
        abort_unless($daily_report->user_id === Auth::id(), 403);

        $jobs = $this->myJobs();
        $stages = JobStage::orderBy('order_no')->get();

        return view('karyawan.daily-reports.edit', ['report' => $daily_report, 'jobs' => $jobs, 'stages' => $stages]);
    }

    public function update(Request $request, DailyReport $daily_report)
    {
        abort_unless($daily_report->user_id === Auth::id(), 403);

        $job = $this->ownedJob($request->input('job_task_id'));
        $data = $this->validated($request);

        DB::transaction(function () use ($data, $job, $daily_report) {
            $previousJobId = $daily_report->job_task_id;

            $daily_report->update([
                'job_task_id' => $job->id,
                'stage_id' => $data['stage_id'],
                'report_date' => $data['report_date'],
                'progress' => $data['progress'],
                'description' => $data['description'],
                'obstacle' => $data['obstacle'] ?? null,
                'next_plan' => $data['next_plan'] ?? null,
            ]);

            // Progress & tahapan pekerjaan selalu mengikuti laporan terbaru yang tersisa.
            $this->recalculateJob($job);

            if ($previousJobId !== $job->id) {
                $previousJob = JobTask::find($previousJobId);
                if ($previousJob) {
                    $this->recalculateJob($previousJob);
                }
            }
        });

        return redirect()->route('karyawan.daily-reports.index')->with('success', 'Daily report berhasil diperbarui.');
    }

    public function destroy(DailyReport $daily_report)
    {
        abort_unless($daily_report->user_id === Auth::id(), 403);

        DB::transaction(function () use ($daily_report) {
            $job = $daily_report->jobTask;
            $daily_report->delete();

            if ($job) {
                $this->recalculateJob($job);
            }
        });

        return back()->with('success', 'Daily report berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'job_task_id' => ['required', 'exists:job_tasks,id'],
            'stage_id' => ['required', 'exists:job_stages,id'],
            'report_date' => ['required', 'date'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'description' => ['required', 'string'],
            'obstacle' => ['nullable', 'string'],
            'next_plan' => ['nullable', 'string'],
        ]);
    }

    private function myJobs()
    {
        $employee = Auth::user()->employee;

        return $employee
            ? JobTask::with(['client', 'documentCategory'])
            ->where('employee_id', $employee->id)
            ->orderBy('deadline')
            ->get()
            : collect();
    }

    /** Pastikan pekerjaan yang dipilih memang milik karyawan yang sedang login. */
    private function ownedJob(?string $jobTaskId): JobTask
    {
        $employee = Auth::user()->employee;

        return JobTask::where('id', $jobTaskId)
            ->where('employee_id', $employee?->id)
            ->firstOrFail();
    }

    /**
     * Progress & tahapan pekerjaan selalu mengikuti laporan terbaru (by tanggal) yang masih tersisa.
     * Kalau tidak ada laporan tersisa, data pekerjaan dibiarkan seperti sebelumnya.
     */
    private function recalculateJob(JobTask $job): void
    {
        $latest = DailyReport::where('job_task_id', $job->id)
            ->latest('report_date')
            ->latest('id')
            ->first();

        if ($latest) {
            $job->update(['progress' => $latest->progress, 'stage_id' => $latest->stage_id]);
        }
    }
}
