<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Karyawan;

Route::get('/', fn() => redirect('/login'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('clients', Admin\ClientController::class);

        Route::resource('experts', Admin\ExpertController::class);

        Route::resource('employees', Admin\EmployeeController::class);

        Route::resource('document-types', Admin\DocumentTypeController::class);

        Route::resource('jobs', Admin\JobTaskController::class);

        Route::get('/reports', [Admin\ReportController::class, 'index'])
            ->name('reports.index');
    });

Route::middleware(['auth', 'role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        Route::get('/dashboard', [Karyawan\DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('daily-reports', Karyawan\DailyReportController::class)
            ->only(['index', 'create', 'store']);
    });
