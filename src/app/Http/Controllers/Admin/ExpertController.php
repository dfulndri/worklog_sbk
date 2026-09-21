<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use Illuminate\Http\Request;

class ExpertController extends Controller
{
    public function index(Request $request)
    {
        $experts = Expert::when($request->filled('search'), function ($q) use ($request) {
            $search = $request->search;
            $q->where(function ($sub) use ($search) {
                $sub->where('name', 'like', "%{$search}%")
                    ->orWhere('field', 'like', "%{$search}%");
            });
        })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.experts.index', compact('experts'));
    }

    public function create()
    {
        return view('admin.experts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'field' => ['nullable', 'string', 'max:255'],
        ]);

        Expert::create($data);

        return redirect()->route('admin.experts.index')->with('success', 'Data Tenaga Ahli berhasil ditambahkan.');
    }

    public function edit(Expert $expert)
    {
        return view('admin.experts.edit', compact('expert'));
    }

    public function update(Request $request, Expert $expert)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'field' => ['nullable', 'string', 'max:255'],
        ]);

        $expert->update($data);

        return redirect()->route('admin.experts.index')->with('success', 'Data Tenaga Ahli berhasil diperbarui.');
    }

    public function destroy(Expert $expert)
    {
        $expert->delete();

        return back()->with('success', 'Data Tenaga Ahli berhasil dihapus.');
    }
}
