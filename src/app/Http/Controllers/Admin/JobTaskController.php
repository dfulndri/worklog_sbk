<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\DocumentType;
use App\Models\Employee;
use App\Models\Expert;
use App\Models\JobTask;
use Illuminate\Http\Request;

class JobTaskController extends Controller
{
    public function index(Request $request)
    {
        $jobs = JobTask::with(['client', 'employee.user', 'documentType'])
            ->when($request->filled('stage'), fn($q) => $q->where('stage', $request->stage))
            ->when($request->filled('client_id'), fn($q) => $q->where('client_id', $request->client_id))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $clients = Client::orderBy('name')->get();

        return view('admin.jobs.index', compact('jobs', 'clients'));
    }

    public function create()
    {
        $clients = Client::where('status', 'aktif')->orderBy('name')->get();
        $experts = Expert::orderBy('name')->get();
        $employees = Employee::with('user')->get();
        $documentTypes = DocumentType::orderBy('name')->get();

        return view('admin.jobs.create', compact('clients', 'experts', 'employees', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'expert_id' => ['nullable', 'exists:experts,id'],
            'employee_id' => ['required', 'exists:employees,id'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'stage' => ['required', 'in:draft,revisi,sidang,final'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'deadline' => ['nullable', 'date'],
        ]);

        JobTask::create($data);

        return redirect()->route('admin.jobs.index')->with('success', 'Pekerjaan berhasil ditambahkan.');
    }

    public function show(JobTask $job)
    {
        $job->load(['client', 'expert', 'employee.user', 'documentType', 'dailyReports' => fn($q) => $q->latest('report_date')]);

        return view('admin.jobs.show', compact('job'));
    }

    public function edit(JobTask $job)
    {
        $clients = Client::orderBy('name')->get();
        $experts = Expert::orderBy('name')->get();
        $employees = Employee::with('user')->get();
        $documentTypes = DocumentType::orderBy('name')->get();

        return view('admin.jobs.edit', compact('job', 'clients', 'experts', 'employees', 'documentTypes'));
    }

    public function update(Request $request, JobTask $job)
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'expert_id' => ['nullable', 'exists:experts,id'],
            'employee_id' => ['required', 'exists:employees,id'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'stage' => ['required', 'in:draft,revisi,sidang,final'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'deadline' => ['nullable', 'date'],
        ]);

        $job->update($data);

        return redirect()->route('admin.jobs.index')->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    public function destroy(JobTask $job)
    {
        $job->delete();

        return back()->with('success', 'Pekerjaan berhasil dihapus.');
    }
}
