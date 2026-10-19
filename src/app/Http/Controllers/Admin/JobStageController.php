<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\DocumentCategory;
use App\Models\JobStage;
use App\Models\JobTask;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobStageController extends Controller
{
    public function index(Request $request)
    {
        $kategoriList = DocumentCategory::where('level', 'kategori')->orderBy('sort_order')->orderBy('id')->get();
        $kategoriId = (int) ($request->input('kategori_id') ?: $kategoriList->first()?->id);

        $stages = JobStage::where('category_id', $kategoriId)
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('order_no')->orderBy('id')
            ->get();

        return view('admin.job-stages.index', compact('stages', 'kategoriList', 'kategoriId'));
    }

    public function create(Request $request)
    {
        $kategoriList = DocumentCategory::where('level', 'kategori')->orderBy('sort_order')->orderBy('id')->get();
        $kategoriId = $request->input('kategori_id');

        return view('admin.job-stages.create', compact('kategoriList', 'kategoriId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', Rule::exists('document_categories', 'id')->where('level', 'kategori')],
            'name' => ['required', 'string', 'max:255'],
            'phase' => ['required', Rule::in(array_keys(JobStage::PHASES))],
            'order_no' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);
        $data['order_no'] = $data['order_no'] ?? 0;

        JobStage::create($data);

        return redirect()->route('admin.job-stages.index', ['kategori_id' => $data['category_id']])
            ->with('success', 'Tahapan berhasil ditambahkan.');
    }

    public function edit(JobStage $job_stage)
    {
        return view('admin.job-stages.edit', ['stage' => $job_stage->load('category')]);
    }

    public function update(Request $request, JobStage $job_stage)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phase' => ['required', Rule::in(array_keys(JobStage::PHASES))],
            'order_no' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);
        $data['order_no'] = $data['order_no'] ?? 0;

        $job_stage->update($data);

        return redirect()->route('admin.job-stages.index', ['kategori_id' => $job_stage->category_id])
            ->with('success', 'Tahapan berhasil diperbarui.');
    }

    public function destroy(JobStage $job_stage)
    {
        if (JobTask::where('stage_id', $job_stage->id)->exists()
            || DailyReport::where('stage_id', $job_stage->id)->exists()) {
            return back()->with('error', 'Tidak bisa dihapus: tahapan ini masih dipakai pekerjaan/daily report.');
        }

        $categoryId = $job_stage->category_id;
        $job_stage->delete();

        return redirect()->route('admin.job-stages.index', ['kategori_id' => $categoryId])
            ->with('success', 'Tahapan berhasil dihapus.');
    }
}
