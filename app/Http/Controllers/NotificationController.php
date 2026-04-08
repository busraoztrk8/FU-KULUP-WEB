<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * JSON endpoint — Header dropdown için kullanıcının bildirimlerini döner.
     */
    public function index(Request $request)
    {
        $notifications = Notification::forUser(auth()->id())
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = Notification::forUser(auth()->id())->unread()->count();

        return response()->json([
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id'      => $n->id,
                    'type'    => $n->type,
                    'title'   => $n->title,
                    'message' => $n->message,
                    'icon'    => $n->icon,
                    'url'     => $n->url,
                    'read'    => !is_null($n->read_at),
                    'time'    => $n->created_at->diffForHumans(),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Tek bir bildirimi okundu olarak işaretle.
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Tüm bildirimleri okundu olarak işaretle.
     */
    public function markAllAsRead()
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
