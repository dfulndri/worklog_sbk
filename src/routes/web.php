<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
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

// Shared untuk admin & karyawan (Profile, Account Settings, Notifikasi).
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/cover', [ProfileController::class, 'updateCover'])->name('profile.cover');

    Route::get('/account-settings', [ProfileController::class, 'editAccount'])->name('account-settings.edit');
    Route::put('/account-settings', [ProfileController::class, 'updateAccount'])->name('account-settings.update');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
});

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

        Route::get('/reports/export-csv', [Admin\ReportController::class, 'exportCsv'])
            ->name('reports.export-csv');

        Route::get('/reports/export-pdf', [Admin\ReportController::class, 'exportPdf'])
            ->name('reports.export-pdf');
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
