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

        $trendingEvents = Event::where('is_featured', true)
            ->with(['category', 'club'])
            ->where('status', 'published')
            ->latest()
            ->limit(3)
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
                
                return view('partials.event-list-items', compact('selectedEvents'))->render();
            }
            
            if ($viewType === 'grid') {
                return view('partials.event-grid-items', compact('events'))->render();
            }
        }

        return view('etkinlikler', compact('events'));
    }

    public function galeri()
    {
        $galleryImages = \App\Models\GalleryImage::where('is_active', true)
            ->orderBy('order')
            ->get();
            
        return view('galeri', compact('galleryImages'));
    }
}
