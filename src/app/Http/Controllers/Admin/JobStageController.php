<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobStage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobStageController extends Controller
{
    public function index(Request $request)
    {
        $stages = JobStage::when($request->filled('search'), fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('order_no')->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.job-stages.index', compact('stages'));
    }

    public function create()
    {
        return view('admin.job-stages.create');
    }

    public function store(Request $request)
    {
        JobStage::create($this->validated($request));

        return redirect()->route('admin.job-stages.index')->with('success', 'Tahapan berhasil ditambahkan.');
    }

    public function edit(JobStage $job_stage)
    {
        return view('admin.job-stages.edit', ['stage' => $job_stage]);
    }

    public function update(Request $request, JobStage $job_stage)
    {
        $job_stage->update($this->validated($request));

        return redirect()->route('admin.job-stages.index')->with('success', 'Tahapan berhasil diperbarui.');
    }

    public function destroy(JobStage $job_stage)
    {
        $job_stage->delete();

        return back()->with('success', 'Tahapan berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'integer', 'min:0', 'max:100'],
            'group' => ['required', Rule::in(array_keys(JobStage::GROUPS))],
            'order_no' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);
        $data['order_no'] = $data['order_no'] ?? 0;

        return $data;
    }
}
