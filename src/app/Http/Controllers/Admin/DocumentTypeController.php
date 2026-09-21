<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index(Request $request)
    {
        $documentTypes = DocumentType::when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.document-types.index', compact('documentTypes'));
    }

    public function create()
    {
        return view('admin.document-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);

        DocumentType::create($data);

        return redirect()->route('admin.document-types.index')->with('success', 'Jenis dokumen berhasil ditambahkan.');
    }

    public function edit(DocumentType $document_type)
    {
        return view('admin.document-types.edit', compact('document_type'));
    }

    public function update(Request $request, DocumentType $document_type)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);

        $document_type->update($data);

        return redirect()->route('admin.document-types.index')->with('success', 'Jenis dokumen berhasil diperbarui.');
    }

    public function destroy(DocumentType $document_type)
    {
        $document_type->delete();

        return back()->with('success', 'Jenis dokumen berhasil dihapus.');
    }
}
