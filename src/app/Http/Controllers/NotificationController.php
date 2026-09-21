<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);

        $layout = Auth::user()->isAdmin() ? 'layouts.admin' : 'layouts.karyawan';

        return view('notifications.index', compact('notifications', 'layout'));
    }

    public function read(Notification $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 403);

        if (! $notification->isRead()) {
            $notification->update(['read_at' => now()]);
        }

        return redirect($notification->url ?? $this->dashboardRoute());
    }

    public function markAllRead(Request $request)
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);

        return back();
    }

    private function dashboardRoute(): string
    {
        return Auth::user()->isAdmin() ? route('admin.dashboard') : route('karyawan.dashboard');
    }
}
