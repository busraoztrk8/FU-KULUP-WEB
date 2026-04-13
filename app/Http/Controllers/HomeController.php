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
        
        // Handle AJAX Load More
        if ($request->ajax() && $request->has('offset')) {
            $offset = $request->get('offset', 0);
            $limit = $request->get('limit', 15);
            $events = $query->orderBy('start_time', 'asc')
                ->offset($offset)
                ->limit($limit)
                ->get();

            return response()->json([
                'html' => view('partials.event-grid-list', compact('events'))->render(),
                'has_more' => $events->count() >= $limit
            ]);
        }

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

        // Initial grid events should be limited for Load More functionality
        $gridEvents = $query->orderBy('start_time', 'asc')->limit(15)->get();

        return view('etkinlikler', compact('events', 'gridEvents', 'initialClubs', 'date'));
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
        $query = Event::with(['category', 'club'])->where('status', 'published');

        // Handle AJAX Load More
        if ($request->ajax() && $request->has('offset')) {
            $offset = $request->get('offset', 0);
            $limit = $request->get('limit', 15);
            $events = $query->orderBy('start_time', 'asc')
                ->offset($offset)
                ->limit($limit)
                ->get();

            return response()->json([
                'html' => view('partials.event-grid-list', compact('events'))->render(),
                'has_more' => $events->count() >= $limit,
                'count' => $events->count()
            ]);
        }

        // Initial page load: first 15 events
        $events = $query->orderBy('start_time', 'asc')->limit(15)->get();
        $totalEvents = Event::where('status', 'published')->count();

        return view('tum-etkinlikler', compact('events', 'totalEvents'));
    }
}
