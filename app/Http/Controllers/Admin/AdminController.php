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
}
