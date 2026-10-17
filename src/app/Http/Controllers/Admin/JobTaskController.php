<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authority;
use App\Models\Client;
use App\Models\DocumentCategory;
use App\Models\Employee;
use App\Models\Expert;
use App\Models\JobStage;
use App\Models\JobTask;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class JobTaskController extends Controller
{
    public function index(Request $request)
    {
        $jobs = JobTask::with(['client', 'employee.user', 'documentCategory.parent.parent', 'stage'])
            ->when($request->filled('stage_id'), fn($q) => $q->where('stage_id', $request->stage_id))
            ->when($request->filled('client_id'), fn($q) => $q->where('client_id', $request->client_id))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhereHas('client', fn($c) => $c->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('documentCategory', fn($d) => $d->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('employee.user', fn($e) => $e->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $clients = Client::orderBy('name')->get();
        $stages = JobStage::orderBy('order_no')->get();

        return view('admin.jobs.index', compact('jobs', 'clients', 'stages'));
    }

    public function create()
    {
        return view('admin.jobs.create', $this->formOptions());
    }

    public function store(Request $request)
    {
        $data = $this->validateJob($request);

        $job = JobTask::create($data);

        $job->loadMissing('client', 'documentCategory', 'employee.user');
        $job->employee?->user?->pushNotification(
            title: 'Kamu ditugaskan pekerjaan baru',
            message: "{$job->client->name} — {$job->documentCategory->pathLabel()}",
            url: route('karyawan.dashboard'),
        );

        return redirect()->route('admin.jobs.index')->with('success', 'Pekerjaan berhasil ditambahkan.');
    }

    public function show(JobTask $job)
    {
        $job->load([
            'client',
            'expert',
            'employee.user',
            'documentCategory.parent.parent',
            'authority',
            'stage',
            'dailyReports' => fn($q) => $q->with('stage')->latest('report_date'),
        ]);

        return view('admin.jobs.show', compact('job'));
    }

    public function edit(JobTask $job)
    {
        $job->loadMissing('documentCategory.parent.parent');

        $selected = $this->ancestorSelection($job->documentCategory);

        return view('admin.jobs.edit', [...$this->formOptions(), 'job' => $job, 'selected' => $selected]);
    }

    public function update(Request $request, JobTask $job)
    {
        $data = $this->validateJob($request);

        $previousEmployeeId = $job->employee_id;

        $job->update($data);

        if ($job->employee_id !== $previousEmployeeId) {
            $job->loadMissing('client', 'documentCategory', 'employee.user');
            $job->employee?->user?->pushNotification(
                title: 'Kamu ditugaskan pekerjaan baru',
                message: "{$job->client->name} — {$job->documentCategory->pathLabel()}",
                url: route('karyawan.dashboard'),
            );
        }

        return redirect()->route('admin.jobs.index')->with('success', 'Pekerjaan berhasil diperbarui.');
    }

    public function destroy(JobTask $job)
    {
        $job->delete();

        return back()->with('success', 'Pekerjaan berhasil dihapus.');
    }

    private function validateJob(Request $request): array
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'expert_id' => ['nullable', 'exists:experts,id'],
            'employee_id' => ['required', 'exists:employees,id'],
            'document_category_id' => ['required', 'exists:document_categories,id'],
            'authority_id' => ['nullable', 'exists:authorities,id'],
            'stage_id' => ['required', 'exists:job_stages,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'deadline' => ['nullable', 'date'],
        ]);

        $category = DocumentCategory::findOrFail($data['document_category_id']);

        if ($category->childLevel() && $category->children()->exists()) {
            throw ValidationException::withMessages([
                'document_category_id' => 'Pilih jenis dokumen sampai level paling spesifik (bukan kategori/sub kategori).',
            ]);
        }

        if ($category->requires_authority && empty($data['authority_id'])) {
            throw ValidationException::withMessages([
                'authority_id' => 'Jenis dokumen ini wajib menentukan Status Kewenangan.',
            ]);
        }

        if (! $category->requires_authority) {
            $data['authority_id'] = null;
        }

        return $data;
    }

    /** Opsi dropdown untuk form create/edit, dikirim sebagai array PHP polos (aman untuk @json). */
    private function formOptions(): array
    {
        $clients = Client::where('status', 'aktif')->orderBy('name')->get();
        $experts = Expert::orderBy('name')->get();
        $employees = Employee::with('user')->get();
        $authorities = Authority::orderBy('sort_order')->get();
        $stages = JobStage::orderBy('order_no')->get();

        $kategoriList = DocumentCategory::where('level', 'kategori')->orderBy('sort_order')->get();

        $subKategoriOptionsJs = DocumentCategory::where('level', 'sub_kategori')
            ->withCount('children')
            ->orderBy('sort_order')->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'parent_id' => $c->parent_id,
                'has_children' => $c->children_count > 0,
                'requires_authority' => $c->requires_authority,
            ])->values()->all();

        $dokumenOptionsJs = DocumentCategory::where('level', 'dokumen')
            ->orderBy('sort_order')->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'parent_id' => $c->parent_id,
                'requires_authority' => $c->requires_authority,
            ])->values()->all();

        return compact(
            'clients',
            'experts',
            'employees',
            'authorities',
            'stages',
            'kategoriList',
            'subKategoriOptionsJs',
            'dokumenOptionsJs'
        );
    }

    /** Backtrack dari leaf category ke kategori_id & sub_kategori_id, untuk pre-fill form edit. */
    private function ancestorSelection(DocumentCategory $leaf): array
    {
        if ($leaf->level === 'dokumen') {
            $subKategori = $leaf->parent;

            return [
                'kategori_id' => $subKategori?->parent_id,
                'sub_kategori_id' => $subKategori?->id,
                'dokumen_id' => $leaf->id,
            ];
        }

        // level sub_kategori yang langsung jadi leaf (cabang Kontruksi).
        return [
            'kategori_id' => $leaf->parent_id,
            'sub_kategori_id' => $leaf->id,
            'dokumen_id' => null,
        ];
    }
}
