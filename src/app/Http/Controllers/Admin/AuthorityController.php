<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Authority;
use Illuminate\Http\Request;

class AuthorityController extends Controller
{
    public function index(Request $request)
    {
        $authorities = Authority::when($request->filled('search'), fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderBy('sort_order')->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.authorities.index', compact('authorities'));
    }

    public function create()
    {
        return view('admin.authorities.create');
    }

    public function store(Request $request)
    {
        Authority::create($this->validated($request));

        return redirect()->route('admin.authorities.index')->with('success', 'Status kewenangan berhasil ditambahkan.');
    }

    public function edit(Authority $authority)
    {
        return view('admin.authorities.edit', compact('authority'));
    }

    public function update(Request $request, Authority $authority)
    {
        $authority->update($this->validated($request));

        return redirect()->route('admin.authorities.index')->with('success', 'Status kewenangan berhasil diperbarui.');
    }

    public function destroy(Authority $authority)
    {
        $authority->delete();

        return back()->with('success', 'Status kewenangan berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
