<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['layouts.admin', 'layouts.karyawan'], function ($view) {
            $user = Auth::user();

            $view->with([
                'navUnreadCount' => $user ? $user->unreadNotifications()->count() : 0,
                'navRecentNotifications' => $user ? $user->notifications()->take(5)->get() : collect(),
            ]);
        });
    }
}
