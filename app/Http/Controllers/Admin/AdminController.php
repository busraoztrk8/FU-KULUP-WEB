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
        $isEditor = auth()->user()->isEditor();
        $clubId = auth()->user()->club_id;

        $stats = [
            'total_events'       => $isEditor ? Event::where('club_id', $clubId)->count() : Event::count(),
            'total_clubs'        => $isEditor ? 1 : Club::count(),
            'active_clubs'       => $isEditor ? Club::where('id', $clubId)->where('is_active', true)->count() : Club::where('is_active', true)->count(),
            'total_users'        => $isEditor ? \App\Models\ClubMember::where('club_id', $clubId)->where('status', 'approved')->count() : User::count(),
            'total_categories'   => Category::count(),
            'total_news'         => $isEditor ? News::where('club_id', $clubId)->count() : News::count(),
            'total_announcements'=> $isEditor ? Announcement::where('club_id', $clubId)->count() : Announcement::count(),
        ];

        $recentEventsQuery = Event::with('club');
        $recentNewsQuery = News::query();
        $recentAnnouncementsQuery = Announcement::query();

        if ($isEditor) {
            $recentEventsQuery->where('club_id', $clubId);
            $recentNewsQuery->where('club_id', $clubId);
            $recentAnnouncementsQuery->where('club_id', $clubId);
        }

        $recentEvents = $recentEventsQuery->latest()->take(5)->get();
        $recentNews = $recentNewsQuery->latest()->take(5)->get();
        $recentAnnouncements = $recentAnnouncementsQuery->latest()->take(5)->get();

        $recentMembers = [];
        if ($isEditor) {
            $recentMembers = \App\Models\ClubMember::with('user')
                ->where('club_id', $clubId)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('admin.index', compact('stats', 'recentNews', 'recentEvents', 'recentAnnouncements', 'recentMembers'));
    }

    public function toggleStatus(\Illuminate\Http\Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        try {
            if ($type === 'news') {
                $item = News::findOrFail($id);
                if (auth()->user()->isEditor() && $item->club_id !== auth()->user()->club_id) {
                    return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
                }
                $item->is_published = !$item->is_published;
                $item->save();
                return response()->json(['success' => true, 'status' => $item->is_published]);
            } elseif ($type === 'announcement') {
                $item = Announcement::findOrFail($id);
                if (auth()->user()->isEditor() && $item->club_id !== auth()->user()->club_id) {
                    return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
                }
                $item->is_published = !$item->is_published;
                $item->save();
                return response()->json(['success' => true, 'status' => $item->is_published]);
            } elseif ($type === 'club') {
                $item = Club::findOrFail($id);
                if (auth()->user()->isEditor() && $item->id !== auth()->user()->club_id) {
                    return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
                }
                $item->is_active = !$item->is_active;
                $item->save();
                return response()->json(['success' => true, 'status' => $item->is_active]);
            } elseif ($type === 'event') {
                $item = Event::findOrFail($id);
                if (auth()->user()->isEditor() && $item->club_id !== auth()->user()->club_id) {
                    return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
                }
                // Toggle between published and draft
                $item->status = ($item->status === 'published') ? 'draft' : 'published';
                $item->save();
                return response()->json(['success' => true, 'status' => $item->status]);
            }
            return response()->json(['success' => false, 'message' => 'Geçersiz tür']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
