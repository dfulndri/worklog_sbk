<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;

class DocumentCategoryController extends Controller
{
    private const TABS = ['kategori', 'sub_kategori', 'dokumen'];

    public function index(Request $request)
    {
        $tab = in_array($request->input('tab'), self::TABS, true) ? $request->input('tab') : 'kategori';

        $kategoriList = DocumentCategory::where('level', 'kategori')
            ->orderBy('sort_order')->orderBy('id')->get();

        $subKategoriList = DocumentCategory::where('level', 'sub_kategori')
            ->orderBy('sort_order')->orderBy('id')->get();

        $kategoriId = $request->input('kategori_id');
        $subKategoriId = $request->input('sub_kategori_id');
        $search = trim((string) $request->input('search'));

        $query = DocumentCategory::with(['parent.parent'])->where('level', $tab);

        if ($tab === 'sub_kategori' && $kategoriId) {
            $query->where('parent_id', $kategoriId);
        }

        if ($tab === 'dokumen') {
            if ($subKategoriId) {
                $query->where('parent_id', $subKategoriId);
            } elseif ($kategoriId) {
                $query->whereHas('parent', fn($q) => $q->where('parent_id', $kategoriId));
            }
        }

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $rows = $query->orderBy('sort_order')->orderBy('id')->get();

        // Sub kategori sudah difilter oleh kategori terpilih, dikirim sebagai array polos
        // (aman dipakai @json di Blade, tanpa koma bersarang dalam satu ekspresi).
        $subKategoriFiltered = $kategoriId
            ? $subKategoriList->where('parent_id', $kategoriId)->values()
            : $subKategoriList;

        $subKategoriOptionsJs = $subKategoriList->map(fn($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'parent_id' => $s->parent_id,
        ])->values()->all();

        return view('admin.document-categories.index', compact(
            'tab',
            'rows',
            'kategoriList',
            'subKategoriList',
            'subKategoriFiltered',
            'subKategoriOptionsJs',
            'kategoriId',
            'subKategoriId',
            'search'
        ));
    }

    public function create(Request $request)
    {
        $level = in_array($request->input('level'), self::TABS, true) ? $request->input('level') : 'kategori';

        $kategoriList = DocumentCategory::where('level', 'kategori')->orderBy('sort_order')->orderBy('id')->get();
        $subKategoriOptionsJs = DocumentCategory::where('level', 'sub_kategori')
            ->orderBy('sort_order')->orderBy('id')->get()
            ->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'parent_id' => $s->parent_id])
            ->values()->all();

        return view('admin.document-categories.create', compact('level', 'kategoriList', 'subKategoriOptionsJs'));
    }

    public function store(Request $request)
    {
        $level = in_array($request->input('level'), self::TABS, true) ? $request->input('level') : 'kategori';

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'requires_authority' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ];
        if ($level === 'sub_kategori') {
            $rules['kategori_id'] = ['required', 'exists:document_categories,id'];
        }
        if ($level === 'dokumen') {
            $rules['sub_kategori_id'] = ['required', 'exists:document_categories,id'];
        }
        $data = $request->validate($rules);

        $parentId = match ($level) {
            'kategori' => $this->rootId(),
            'sub_kategori' => $data['kategori_id'],
            'dokumen' => $data['sub_kategori_id'],
        };

        DocumentCategory::create([
            'parent_id' => $parentId,
            'name' => $data['name'],
            'level' => $level,
            'requires_authority' => $request->boolean('requires_authority'),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.document-categories.index', ['tab' => $level])
            ->with('success', 'Data berhasil ditambahkan.');
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

        return redirect()->route('admin.document-categories.index', ['tab' => $document_category->level])
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(DocumentCategory $document_category)
    {
        if ($document_category->children()->exists()) {
            return back()->with('error', 'Tidak bisa dihapus: masih punya turunan. Hapus turunannya dulu.');
        }

        $level = $document_category->level;
        $document_category->delete();

        return redirect()->route('admin.document-categories.index', ['tab' => $level])
            ->with('success', 'Data berhasil dihapus.');
    }

    private function rootId(): int
    {
        return DocumentCategory::where('level', 'kegiatan')->firstOrFail()->id;
    }
}
