<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Event;
use App\Models\User;
use App\Models\News;
use App\Models\Announcement;
use App\Models\ClubMember;
use App\Models\EventRegistration;
use App\Models\Category;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $isEditor = auth()->user()->isEditor();
        $clubId = auth()->user()->club_id;

        // ── GENEL İSTATİSTİKLER ──
        $stats = [
            'total_users'        => $isEditor ? ClubMember::where('club_id', $clubId)->where('status', 'approved')->count() : User::count(),
            'total_clubs'        => $isEditor ? 1 : Club::count(),
            'active_clubs'       => $isEditor ? 1 : Club::where('is_active', true)->count(),
            'total_events'       => $isEditor ? Event::where('club_id', $clubId)->count() : Event::count(),
            'total_news'         => $isEditor ? News::where('club_id', $clubId)->count() : News::count(),
            'total_announcements'=> $isEditor ? Announcement::where('club_id', $clubId)->count() : Announcement::count(),
            'total_memberships'  => $isEditor ? ClubMember::where('club_id', $clubId)->count() : ClubMember::count(),
            'pending_memberships'=> $isEditor ? ClubMember::where('club_id', $clubId)->where('status', 'pending')->count() : ClubMember::where('status', 'pending')->count(),
        ];

        // ── AYLIK KAYIT GRAFİĞİ (Son 6 ay) ──
        $monthlyUsers = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthLabel = $date->translatedFormat('M');
            
            if ($isEditor) {
                $count = ClubMember::where('club_id', $clubId)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            } else {
                $count = User::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count();
            }
            
            $monthlyUsers[] = ['label' => $monthLabel, 'count' => $count];
        }

        // ── KATEGORİLERE GÖRE KULÜPLER ──
        $categoryStats = Category::withCount('clubs')->get()->map(function($cat) {
            return ['name' => $cat->name, 'count' => $cat->clubs_count];
        })->sortByDesc('count')->values()->take(6);
        $totalClubsInCategories = $categoryStats->sum('count');

        // ── EN ÇOK KATILIM GÖSTERİLEN ETKİNLİKLER ──
        $topEventsQuery = Event::with('category');
        if ($isEditor) {
            $topEventsQuery->where('club_id', $clubId);
        }
        $topEvents = $topEventsQuery
            ->orderBy('current_participants', 'desc')
            ->take(5)
            ->get();

        // ── EN POPÜLER KULÜPLER (Üye Sayısına Göre) ──
        $topClubs = Club::where('is_active', true)
            ->orderBy('member_count', 'desc')
            ->take(5)
            ->get();

        // ── SON BAŞVURULAR ──
        $recentMembershipsQuery = ClubMember::with(['user', 'club'])->latest();
        if ($isEditor) {
            $recentMembershipsQuery->where('club_id', $clubId);
        }
        $recentMemberships = $recentMembershipsQuery->take(5)->get();

        // ── AYLIK ETKİNLİK GRAFİĞİ ──
        $monthlyEvents = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthLabel = $date->translatedFormat('M');

            $query = Event::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month);
            if ($isEditor) {
                $query->where('club_id', $clubId);
            }
            $monthlyEvents[] = ['label' => $monthLabel, 'count' => $query->count()];
        }

        return view('admin.raporlar', compact(
            'stats', 'monthlyUsers', 'categoryStats', 'totalClubsInCategories',
            'topEvents', 'topClubs', 'recentMemberships', 'monthlyEvents'
        ));
    }
}
