<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);
            
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
        $notification->markAsRead();
        
        return back()->with('success', 'Notification marked as read.');
    }

    public function open(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
        $notification->markAsRead();

        return redirect($notification->link ?? route('notifications.index'));
    }

    public function markAllAsRead()
    {
        Auth::user()
            ->unreadNotifications()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
            
        return back()->with('success', 'All notifications marked as read.');
    }
}