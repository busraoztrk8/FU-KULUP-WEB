<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Club;
use Str;
use Illuminate\Support\Facades\Storage;

class DuyuruController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Announcement::leftJoin('clubs', 'announcements.club_id', '=', 'clubs.id')
                    ->leftJoin('categories', 'clubs.category_id', '=', 'categories.id')
                    ->select('announcements.*')
                    ->with(['club.category']);

                if (auth()->user()->isEditor()) {
                    $query->where(function ($q) {
                        $q->where('announcements.club_id', auth()->user()->club_id)
                            ->orWhereNull('announcements.club_id');
                    });
                }

                // Filters
                if ($request->filled('club_id')) {
                    $query->where('announcements.club_id', $request->club_id);
                }
                if ($request->filled('category_id')) {
                    $query->where('clubs.category_id', $request->category_id);
                }

                // Filter by status
                if ($request->filled('status') && $request->status !== 'all') {
                    $query->where('announcements.is_published', (int) $request->status);
                }

                return \Yajra\DataTables\Facades\DataTables::of($query->orderBy('announcements.created_at', 'asc'))
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        $search = (string) data_get($request->input('search'), 'value', '');
                        $search = trim($search);
                        if ($search === '') return;

                        $query->where(function ($q) use ($search) {
                            $q->where('announcements.title', 'like', "%{$search}%")
                              ->orWhere('announcements.content', 'like', "%{$search}%")
                              ->orWhere('clubs.name', 'like', "%{$search}%")
                              ->orWhere('categories.name', 'like', "%{$search}%");
                        });
                    })
                    ->addColumn('announcement_info', function ($row) {
                        $url = $row->image_path;
                        if ($url) {
                            if (!str_starts_with($url, 'http')) {
                                if (file_exists(public_path('uploads/' . $url))) {
                                    $url = asset('uploads/' . $url);
                                } else {
                                    $url = asset('storage/' . $url);
                                }
                            }
                        } else {
                            $url = asset('images/logo_orj.png');
                        }
                        $t = e($row->title);
                        return '<div class="flex items-center gap-3 min-w-0 max-w-full">
                            <div class="w-12 h-10 bg-white border border-slate-100 p-1 flex items-center justify-center rounded-lg shadow-sm shrink-0">
                                <img src="' . $url . '" class="max-w-full max-h-full object-contain" alt="Görsel">
                            </div>
                            <span class="font-bold text-slate-700 min-w-0 truncate block flex-1" title="' . $t . '">' . $t . '</span>
                        </div>';
                    })
                    ->addColumn('club_name', function ($row) {
                        if (!$row->club) {
                            return '<span class="text-slate-400 text-xs">—</span>';
                        }
                        $n = e($row->club->name);
                        return '<span class="px-2 py-1 bg-primary/10 text-primary rounded-lg text-xs font-bold max-w-full truncate inline-block align-bottom" title="' . $n . '">' . $n . '</span>';
                    })
                    ->addColumn('status', function ($row) {
                        $bgToggle = $row->is_published ? 'bg-green-600' : 'bg-slate-200';
                        $lbl = $row->is_published ? 'Aktif' : 'Pasif';
                        $lblColor = $row->is_published ? 'text-slate-700' : 'text-slate-500';
                        $lblEsc = e($lbl);

                        return '<div class="flex items-center gap-2 justify-center min-w-0 max-w-full">
                            <label class="relative inline-flex items-center cursor-pointer m-0 shrink-0" onclick="event.preventDefault(); toggleStatus(\'announcement\', ' . $row->id . ')">
                                <div class="w-11 h-6 rounded-full relative transition-colors ' . $bgToggle . '">
                                    <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform ' . ($row->is_published ? 'translate-x-5' : '') . '"></span>
                                </div>
                            </label>
                            <span class="text-xs sm:text-sm font-semibold ' . $lblColor . ' min-w-0 truncate" title="' . $lblEsc . '">' . $lblEsc . '</span>
                        </div>';
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<div class="flex items-center justify-center gap-2 whitespace-nowrap">';
                        $btn .= '<button onclick="showDuyuruDuzenle(' . $row->id . ')" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 rounded hover:bg-blue-100 transition-colors border border-blue-100" title="Düzenle"><span class="material-symbols-outlined text-[16px]">edit_square</span></button>';
                        $btn .= '<button onclick="showDeleteModal(' . $row->id . ', \'' . e(addslashes($row->title)) . '\')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded hover:bg-red-100 transition-colors border border-red-100" title="Sil"><span class="material-symbols-outlined text-[16px]">delete</span></button>';
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->filterColumn('announcement_info', function($query, $keyword) {
                        $query->where('announcements.title', 'like', "%{$keyword}%");
                    })
                    ->filterColumn('club_name', function($query, $keyword) {
                        $query->where('clubs.name', 'like', "%{$keyword}%");
                    })
                    ->rawColumns(['announcement_info', 'club_name', 'status', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error("DataTables Error: " . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        $clubs = auth()->user()->isEditor()
            ? Club::where('id', auth()->user()->club_id)->get()
            : Club::where('is_active', true)->get();

        // İstatistikler
        $statsQuery = Announcement::query();
        if (auth()->user()->isEditor()) {
            $statsQuery->where(function ($q) {
                $q->where('club_id', auth()->user()->club_id)
                    ->orWhereNull('club_id');
            });
        }

        $stats = [
            'total' => (clone $statsQuery)->count(),
            'published' => (clone $statsQuery)->where('is_published', true)->count(),
            'draft' => (clone $statsQuery)->where('is_published', false)->count(),
        ];

        $categories = \App\Models\Category::all();

        return view('admin.duyurular', compact('clubs', 'stats', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string|max:5000',
            'is_published' => 'required|in:1,0,published,draft',
            'club_id' => auth()->user()->isAdmin() ? 'required|exists:clubs,id' : 'nullable',
            'image' => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $clubId = auth()->user()->isEditor() ? auth()->user()->club_id : $validated['club_id'];
            $club = $clubId ? Club::find($clubId) : null;
            $path = $club ? $club->slug : 'global';
            $validated['image_path'] = $request->file('image')->store($path . '/announcements', 'uploads');
        }

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . substr(md5(\Illuminate\Support\Str::uuid()), 0, 8);

        $validated['is_published'] = ($validated['is_published'] === '1' || $validated['is_published'] === 'published');

        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        if (auth()->user()->isEditor()) {
            $validated['club_id'] = auth()->user()->club_id;
        }

        Announcement::create($validated);

        return redirect()->route('admin.duyurular')->with('success', 'Duyuru başarıyla oluşturuldu.');
    }

    public function show($id)
    {
        $announcement = Announcement::findOrFail($id);

        if (auth()->user()->isEditor() && $announcement->club_id !== auth()->user()->club_id) {
            return response()->json(['error' => 'Bu duyuruyu görme yetkiniz yok.'], 403);
        }

        return response()->json($announcement);
    }

    public function update(Request $request, Announcement $announcement)
    {
        if (auth()->user()->isEditor() && $announcement->club_id !== auth()->user()->club_id) {
            abort(403, 'Bu duyuruyu düzenleme yetkiniz yok.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'content' => 'required|string|max:5000',
            'is_published' => 'required|in:1,0,published,draft',
            'club_id' => auth()->user()->isAdmin() ? 'required|exists:clubs,id' : 'nullable',
            'image' => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($announcement->image_path) {
                if (Storage::disk('uploads')->exists($announcement->image_path)) {
                    Storage::disk('uploads')->delete($announcement->image_path);
                } elseif (Storage::disk('public')->exists($announcement->image_path)) {
                    Storage::disk('public')->delete($announcement->image_path);
                }
            }

            $clubId = auth()->user()->isEditor() ? auth()->user()->club_id : (isset($validated['club_id']) ? $validated['club_id'] : $announcement->club_id);
            $club = $clubId ? Club::find($clubId) : null;
            $path = $club ? $club->slug : 'global';
            $validated['image_path'] = $request->file('image')->store($path . '/announcements', 'uploads');
        }

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . substr(md5(\Illuminate\Support\Str::uuid()), 0, 8);
        $validated['is_published'] = ($validated['is_published'] === '1' || $validated['is_published'] === 'published');

        if ($validated['is_published'] && !$announcement->is_published) {
            $validated['published_at'] = now();
        }

        if (auth()->user()->isEditor()) {
            $validated['club_id'] = auth()->user()->club_id;
        }

        $announcement->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Duyuru başarıyla güncellendi.']);
        }

        return redirect()->route('admin.duyurular')->with('success', 'Duyuru başarıyla güncellendi.');
    }

    public function destroy(Announcement $announcement)
    {
        if (auth()->user()->isEditor() && $announcement->club_id !== auth()->user()->club_id) {
            abort(403, 'Bu duyuruyu silme yetkiniz yok.');
        }

        if ($announcement->image_path) {
            if (Storage::disk('uploads')->exists($announcement->image_path)) {
                Storage::disk('uploads')->delete($announcement->image_path);
            } else {
                Storage::disk('public')->delete($announcement->image_path);
            }
        }
        $announcement->delete();
        return redirect()->route('admin.duyurular')->with('success', 'Duyuru başarıyla silindi.');
    }
}
