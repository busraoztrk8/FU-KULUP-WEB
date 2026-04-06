<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use App\Models\News;
use App\Models\Announcement;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_events'       => Event::count(),
            'total_clubs'        => Club::count(),
            'active_clubs'       => Club::where('is_active', true)->count(),
            'total_users'        => User::count(),
            'total_categories'   => Category::count(),
            'total_news'         => News::count(),
            'total_announcements'=> Announcement::count(),
        ];

        $recentEvents = Event::with('club')->latest()->take(5)->get();
        $recentNews = News::latest()->take(5)->get();
        $recentAnnouncements = Announcement::latest()->take(5)->get();

        return view('admin.index', compact('stats', 'recentEvents', 'recentNews', 'recentAnnouncements'));
    }

    public function toggleStatus(\Illuminate\Http\Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        try {
            if ($type === 'news') {
                $item = News::findOrFail($id);
                $item->is_published = !$item->is_published;
                $item->save();
                return response()->json(['success' => true, 'status' => $item->is_published]);
            } elseif ($type === 'announcement') {
                $item = Announcement::findOrFail($id);
                $item->is_published = !$item->is_published;
                $item->save();
                return response()->json(['success' => true, 'status' => $item->is_published]);
            } elseif ($type === 'club') {
                $item = Club::findOrFail($id);
                $item->is_active = !$item->is_active;
                $item->save();
                return response()->json(['success' => true, 'status' => $item->is_active]);
            } elseif ($type === 'event') {
                $item = Event::findOrFail($id);
                // Toggle between published and draft
                $item->status = ($item->status === 'published') ? 'draft' : 'published';
                $item->save();
                return response()->json(['success' => true, 'status' => $item->status]);
            }
            return response()->json(['success' => false, 'message' => 'Invalid type']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
