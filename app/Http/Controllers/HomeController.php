<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\Club;
use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $galleryImages = GalleryImage::where('is_active', true)
            ->orderBy('order')
            ->get();

        $trendingEvents = Event::with(['category', 'club'])
            ->where('status', 'published')
            ->orderBy('start_time', 'asc')
            ->limit(6)
            ->get();

        $activeClubs = Club::where('is_active', true)
            ->with('category')
            ->latest()
            ->limit(2)
            ->get();

        $categories = \App\Models\Category::all();

        $stats = [
            'clubs' => Club::count(),
            'students' => '10k',
            'events' => Event::count(),
        ];

        return view('welcome', compact('galleryImages', 'stats', 'trendingEvents', 'activeClubs'));
    }

    public function etkinlikler(Request $request)
    {
        $query = Event::with(['category', 'club'])->where('status', 'published');
        

        // Date filtering for calendar
        $date = $request->get('date', now()->format('Y-m-d'));
        
        $events = $query->orderBy('start_time', 'asc')->get();

        if ($request->ajax()) {
            $viewType = $request->get('view_type', 'calendar_list');
            
            if ($viewType === 'calendar_list') {
                $eventsByDate = $events->groupBy(function($e) {
                    return \Carbon\Carbon::parse($e->start_time)->format('Y-m-d');
                });
                $selectedEvents = $eventsByDate->get($date, collect());
                
                $clubs = $selectedEvents->pluck('club')->unique('id')->filter();
                
                return response()->json([
                    'html' => view('partials.event-list-items', compact('selectedEvents', 'date'))->render(),
                    'club_html' => $clubs->isNotEmpty() ? view('partials.club-mini-profile', compact('clubs'))->render() : ''
                ]);
            }
            
            if ($viewType === 'grid') {
                return view('partials.event-grid-items', compact('events'))->render();
            }
        }

        // For initial load, find the clubs for the default date
        $eventsByDate = $events->groupBy(function($e) {
            return \Carbon\Carbon::parse($e->start_time)->format('Y-m-d');
        });
        $selectedEvents = $eventsByDate->get($date, collect());
        $initialClubs = $selectedEvents->pluck('club')->unique('id')->filter();

        // Initial grid events - show 10 in the "Tümü" tab
        $gridEvents = Event::with(['category', 'club'])->where('status', 'published')->orderBy('start_time', 'asc')->limit(10)->get();
        $totalPublishedEvents = Event::where('status', 'published')->count();
        
        // Fetch recent announcements
        $announcements = \App\Models\Announcement::with('club')
            ->where('is_published', true)
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('etkinlikler', compact('events', 'gridEvents', 'initialClubs', 'date', 'totalPublishedEvents', 'announcements'));
    }

    public function dailyEvents($date)
    {
        $events = Event::with(['category', 'club'])
            ->where('status', 'published')
            ->whereDate('start_time', $date)
            ->orderBy('start_time', 'asc')
            ->get();
            
        $date = \Carbon\Carbon::parse($date);
        
        return view('daily-events', compact('events', 'date'));
    }

    public function tumEtkinlikler(Request $request)
    {
        $events = Event::with(['category', 'club'])
            ->where('status', 'published')
            ->orderBy('start_time', 'asc')
            ->paginate(9);

        $totalEvents = Event::where('status', 'published')->count();

        return view('tum-etkinlikler', compact('events', 'totalEvents'));
    }

    public function duyurular()
    {
        $announcements = \App\Models\Announcement::with('club')
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(9);

        return view('duyurular', compact('announcements'));
    }

    public function duyuruDetay($slug)
    {
        $duyuru = \App\Models\Announcement::with('club')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
            
        $relatedAnnouncements = \App\Models\Announcement::with('club')
            ->where('is_published', true)
            ->where('id', '!=', $duyuru->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('duyuru-detay', compact('duyuru', 'relatedAnnouncements'));
    }

    public function haberler()
    {
        $news = \App\Models\News::with('club')
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(9);

        return view('haberler', compact('news'));
    }

    public function haberDetay($slug)
    {
        $haber = \App\Models\News::with('club')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
            
        $relatedNews = \App\Models\News::with('club')
            ->where('is_published', true)
            ->where('id', '!=', $haber->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('haber-detay', compact('haber', 'relatedNews'));
    }
}
