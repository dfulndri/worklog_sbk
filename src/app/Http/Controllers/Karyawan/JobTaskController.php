<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\JobTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobTaskController extends Controller
{
    public function index(Request $request)
    {
        $employee = Auth::user()->employee;

        $jobs = JobTask::with(['client', 'documentType'])
            ->when($employee, fn($q) => $q->where('employee_id', $employee->id), fn($q) => $q->whereRaw('1 = 0'))
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->whereHas('client', fn($c) => $c->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('documentType', fn($d) => $d->where('name', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('karyawan.jobs.index', compact('jobs'));
    }
}
