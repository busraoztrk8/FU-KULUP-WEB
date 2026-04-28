<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\Club;
use App\Models\Event;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::active()->get();

        $galleryImages = GalleryImage::where('is_active', true)
            ->orderBy('order')
            ->get();

        $trendingEvents = Event::with(['category', 'club'])
            ->where('status', 'published')
            ->orderBy('start_time', 'asc')
            ->limit(6)
            ->get();

        $activeClubs = Club::with(['category', 'members', 'president'])
            ->withCount('approvedMembers')
            ->oldest()
            ->limit(8)
            ->get();

        $categories = \App\Models\Category::all();

        $stats = [
            'clubs' => Club::count(),
            'students' => '10k',
            'events' => Event::count(),
        ];

        return view('welcome', compact('sliders', 'galleryImages', 'stats', 'trendingEvents', 'activeClubs'));
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
                
                $unifiedHtml = view('partials.unified-event-card', compact('selectedEvents', 'date'))->render();
                
                return response()->json([
                    'html' => $unifiedHtml,
                    'club_html' => '' // Artık tüm bilgi 'html' içinde birleşti
                ]);
            }
            
            if ($viewType === 'calendar_month') {
                $calendarHtml = view('partials.calendar-month', [
                    'currentDateStr' => $date,
                    'events' => $events
                ])->render();
                return response()->json([
                    'html' => $calendarHtml
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

        // Initial grid events - show 15 in the "Tümü" tab (6 initially visible, then 15, then redirect)
        $gridEvents = Event::with(['category', 'club'])->where('status', 'published')->orderBy('start_time', 'asc')->limit(15)->get();
        $totalPublishedEvents = Event::where('status', 'published')->count();
        $latestNews = \App\Models\News::where('is_published', true)->latest()->take(3)->get();

        // Hero Slider: Gelecek 5 etkinlik
        $heroEvents = Event::with(['category', 'club'])
            ->where('status', 'published')
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->take(5)
            ->get();
            
        if ($heroEvents->isEmpty()) {
            $heroEvents = Event::with(['category', 'club'])
                ->where('status', 'published')
                ->latest()
                ->take(3)
                ->get();
        }
        
        return view('etkinlikler', compact('events', 'gridEvents', 'initialClubs', 'date', 'totalPublishedEvents', 'latestNews', 'heroEvents'));
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

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('description_en', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('location_en', 'like', "%{$search}%")
                  ->orWhereHas('club', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%");
                  });
            });
        }

        $filteredClub = null;
        if ($request->has('club')) {
            $filteredClub = Club::where('slug', $request->get('club'))->first();
            if ($filteredClub) {
                $query->where('club_id', $filteredClub->id);
            }
        }

        $events = $query->orderBy('start_time', 'asc')->paginate(9);

        if ($request->ajax()) {
            $html = view('partials.event-grid-list', compact('events'))->render();
            /** @var \Illuminate\Pagination\LengthAwarePaginator $events */
            $pagination = $events->links('partials.custom-pagination')->toHtml();
            
            if ($events->isEmpty()) {
                $html = '<div class="col-span-full text-center py-16 bg-slate-50 border border-dashed border-slate-200 rounded-3xl">
                            <span class="material-symbols-outlined text-6xl text-slate-300 mb-4 inline-block">event_busy</span>
                            <h3 class="text-xl font-bold text-slate-600 mb-2">' . __('site.no_events_found') . '</h3>
                            <p class="text-slate-400">' . __('site.no_results_match_search') . '</p>
                         </div>';
            }
            
            return response()->json([
                'html' => $html,
                'pagination' => $pagination,
                'total' => $events->total()
            ]);
        }

        $totalEventsCount = Event::where('status', 'published');
        if ($filteredClub) {
            $totalEventsCount->where('club_id', $filteredClub->id);
        }
        $totalEvents = $totalEventsCount->count();

        return view('tum-etkinlikler', compact('events', 'totalEvents', 'filteredClub'));
    }

    public function duyurular()
    {
        $announcements = \App\Models\Announcement::with('club')
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(15);

        $latestNews = \App\Models\News::with('club')
            ->where('is_published', true)
            ->latest()
            ->take(3)
            ->get();

        return view('duyurular', compact('announcements', 'latestNews'));
    }

    public function tumDuyurular(Request $request)
    {
        $query = \App\Models\Announcement::with('club')->where('is_published', true);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('content_en', 'like', "%{$search}%")
                  ->orWhereHas('club', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%");
                  });
            });
        }

        $announcements = $query->latest('published_at')->paginate(15);

        if ($request->ajax()) {
            return view('partials.announcement-grid-items', compact('announcements'))->render();
        }

        $totalAnnouncements = \App\Models\Announcement::where('is_published', true)->count();

        return view('tum-duyurular', compact('announcements', 'totalAnnouncements'));
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
            ->paginate(15);

        return view('haberler', compact('news'));
    }

    public function tumHaberler(Request $request)
    {
        $query = \App\Models\News::with('club')->where('is_published', true);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('content_en', 'like', "%{$search}%")
                  ->orWhereHas('club', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%");
                  });
            });
        }

        $filteredClub = null;
        if ($request->has('club')) {
            $filteredClub = Club::where('slug', $request->get('club'))->first();
            if ($filteredClub) {
                $query->where('club_id', $filteredClub->id);
            }
        }

        $news = $query->latest('published_at')->paginate(15);

        if ($request->ajax()) {
            return view('partials.news-grid-items', compact('news'))->render();
        }

        $totalNews = \App\Models\News::where('is_published', true);
        if ($filteredClub) {
            $totalNews->where('club_id', $filteredClub->id);
        }
        $totalNews = $totalNews->count();

        return view('tum-haberler', compact('news', 'totalNews', 'filteredClub'));
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

    public function kulupler(Request $request)
    {
        // Öne çıkan kulübü aktiviteye göre seç (Üye sayısı + Etkinlik sayısı)
        $featured = Club::where('is_active', true)
            ->withCount(['approvedMembers', 'events'])
            ->get()
                /** @var \App\Models\Club $club */
            ->sortByDesc(function($club) {
                return $club->approved_members_count + $club->events_count;
            })
            ->first();

        $clubs = Club::with('category')->withCount('approvedMembers')->where('is_active', true)->oldest()->paginate(12);
        $categories = \App\Models\Category::all();
        $latestNews = \App\Models\News::where('is_published', true)->latest()->take(3)->get();
        return view('kulupler', compact('clubs', 'categories', 'latestNews', 'featured'));
    }

    public function kulupDetay($slug)
    {
        $club = Club::with([
            'category',
            'president',
            'formFields',
            'images',
            'events' => function ($q) {
                $q->where('status', 'published')->latest()->take(4);
            }
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $membership = auth()->check()
            ? \App\Models\ClubMember::where('club_id', $club->id)
                ->where('user_id', auth()->id())
                ->first()
            : null;

        $news = \App\Models\News::where('club_id', $club->id)
            ->where('is_published', true)
            ->latest()
            ->take(6)
            ->get();

        return view('kulup-detay', compact('club', 'membership', 'news'));
    }

    public function kulupGaleri($slug)
    {
        $club = Club::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $images = $club->images()->paginate(12);

        return view('kulup-galeri', compact('club', 'images'));
    }

    public function etkinlikDetay($slug)
    {
        $event = Event::with(['club', 'category'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $similar = Event::with(['club', 'category'])
            ->where('status', 'published')
            ->where('id', '!=', $event->id)
            ->where('category_id', $event->category_id)
            ->latest()
            ->take(3)
            ->get();

        return view('etkinlik-detay', compact('event', 'similar'));
    }
}
