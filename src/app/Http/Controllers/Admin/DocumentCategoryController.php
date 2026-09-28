<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DocumentCategoryController extends Controller
{
    public function index(Request $request)
    {
        $all = DocumentCategory::orderBy('sort_order')->orderBy('id')->get();
        $byParent = $all->groupBy(fn($c) => $c->parent_id ?? 0);

        $rows = [];
        $walk = function (int $parentId, int $depth, array $path) use (&$walk, &$rows, $byParent) {
            foreach ($byParent->get($parentId, collect()) as $category) {
                $childPath = $depth === 0 ? [] : [...$path, $category->name];
                $rows[] = [
                    'category' => $category,
                    'depth' => $depth,
                    'path' => $depth === 0 ? $category->name : implode(' › ', $childPath),
                    'has_children' => $byParent->has($category->id),
                ];
                $walk($category->id, $depth + 1, $childPath);
            }
        };
        $walk(0, 0, []);

        $search = trim((string) $request->input('search'));
        if ($search !== '') {
            $rows = array_values(array_filter(
                $rows,
                fn($row) => mb_stripos($row['category']->name, $search) !== false
            ));
        }

        return view('admin.document-categories.index', compact('rows', 'search'));
    }

    public function create(Request $request)
    {
        $parents = $this->parentOptions();
        $selectedParent = $request->input('parent_id');

        return view('admin.document-categories.create', compact('parents', 'selectedParent'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'parent_id' => ['nullable', 'exists:document_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'requires_authority' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        $parent = $data['parent_id'] ? DocumentCategory::find($data['parent_id']) : null;
        $level = $parent ? $parent->childLevel() : 'kegiatan';

        if (! $level) {
            throw ValidationException::withMessages([
                'parent_id' => 'Level Jenis Dokumen adalah level terdalam dan tidak boleh punya turunan.',
            ]);
        }

        DocumentCategory::create([
            'parent_id' => $parent?->id,
            'name' => $data['name'],
            'level' => $level,
            'requires_authority' => $request->boolean('requires_authority'),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.document-categories.index')->with('success', 'Kategori dokumen berhasil ditambahkan.');
    }

    public function edit(DocumentCategory $document_category)
    {
        return view('admin.document-categories.edit', ['category' => $document_category]);
    }

    public function update(Request $request, DocumentCategory $document_category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'requires_authority' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        $document_category->update([
            'name' => $data['name'],
            'requires_authority' => $request->boolean('requires_authority'),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.document-categories.index')->with('success', 'Kategori dokumen berhasil diperbarui.');
    }

    public function destroy(DocumentCategory $document_category)
    {
        if ($document_category->children()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus: masih punya turunan. Hapus turunannya dulu.');
        }

        $document_category->delete();

        return back()->with('success', 'Kategori dokumen berhasil dihapus.');
    }

    /** Node yang masih boleh punya anak, lengkap dengan label jalur. */
    private function parentOptions(): array
    {
        $all = DocumentCategory::orderBy('sort_order')->orderBy('id')->get();
        $byParent = $all->groupBy(fn($c) => $c->parent_id ?? 0);

        $options = [];
        $walk = function (int $parentId, array $path) use (&$walk, &$options, $byParent) {
            foreach ($byParent->get($parentId, collect()) as $category) {
                $childPath = $category->parent_id ? [...$path, $category->name] : [];
                if ($category->childLevel()) {
                    $options[] = [
                        'id' => $category->id,
                        'label' => ($category->parent_id ? implode(' › ', $childPath) : $category->name)
                            . ' — tambah ' . DocumentCategory::LEVEL_LABELS[$category->childLevel()],
                    ];
                }
                $walk($category->id, $childPath);
            }
        };
        $walk(0, []);

        return $options;
    }
}
