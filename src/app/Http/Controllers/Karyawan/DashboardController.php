<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\JobTask;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;

        $jobs = $employee
            ? JobTask::with(['client', 'documentType'])
            ->where('employee_id', $employee->id)
            ->latest()
            ->get()
            : collect();

        return view('karyawan.dashboard', compact('jobs'));
    }
}
