<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Club;
use Illuminate\Support\Facades\Storage;

class HaberController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = News::leftJoin('clubs', 'news.club_id', '=', 'clubs.id')
                    ->leftJoin('categories', 'clubs.category_id', '=', 'categories.id')
                    ->select('news.*')
                    ->with(['club.category']);

                if (auth()->user()->isEditor()) {
                    $query->where(function($q) {
                        $q->where('news.club_id', auth()->user()->club_id)
                          ->orWhereNull('news.club_id');
                    });
                }

                // Filter by club
                if ($request->filled('club_id')) {
                    $query->where('news.club_id', $request->club_id);
                }

                // Filter by category (via club)
                if ($request->filled('category_id')) {
                    $query->where('clubs.category_id', $request->category_id);
                }

                return \Yajra\DataTables\Facades\DataTables::of($query->orderBy('news.created_at', 'desc'))
                    ->addIndexColumn()
                    ->addColumn('news_info', function($row) {
                        $url = $row->image_path;
                        if ($url) {
                            // Eğer url içinde 'http' yoksa ve 'uploads/' veya 'storage/' ile başlamıyorsa, yeni yapıda olup olmadığını kontrol et
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
                                <img src="'.$url.'" class="max-w-full max-h-full object-contain" alt="Görsel">
                            </div>
                            <span class="font-bold text-slate-700 min-w-0 truncate" title="'.$t.'">'.$t.'</span>
                        </div>';
                    })
                    ->addColumn('club_name', function($row) {
                        if (!$row->club) {
                            return '<span class="text-slate-400 text-xs">—</span>';
                        }
                        $n = e($row->club->name);
                        return '<span class="px-2 py-1 bg-primary/10 text-primary rounded-lg text-xs font-bold max-w-full truncate inline-block align-bottom" title="'.$n.'">'.$n.'</span>';
                    })
                    ->addColumn('category_name', function($row) {
                        if (!$row->club || !$row->club->category) {
                            return '<span class="text-slate-400 text-xs">—</span>';
                        }
                        $n = e($row->club->category->name);
                        return '<span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-medium max-w-full truncate inline-block align-bottom" title="'.$n.'">'.$n.'</span>';
                    })
                    ->addColumn('date', function($row) {
                        return '<span class="text-slate-500 text-sm whitespace-nowrap">'.$row->created_at->format('d.m.Y').'</span>';
                    })
                    ->addColumn('status', function($row) {
                        $bgToggle = $row->is_published ? 'bg-green-600' : 'bg-slate-200';
                        $lbl = $row->is_published ? 'Aktif' : 'Pasif';
                        $lblColor = $row->is_published ? 'text-slate-700' : 'text-slate-500';
                        $lblEsc = e($lbl);
                        return '<div class="flex items-center gap-2 justify-center min-w-0 max-w-full">
                            <label class="relative inline-flex items-center cursor-pointer m-0 shrink-0" onclick="event.preventDefault(); toggleStatus(\'news\', '.$row->id.')">
                                <div class="w-11 h-6 rounded-full relative transition-colors '.$bgToggle.'">
                                    <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform '.($row->is_published ? 'translate-x-5' : '').'"></span>
                                </div>
                            </label>
                            <span class="text-xs sm:text-sm font-semibold '.$lblColor.' min-w-0 truncate" title="'.$lblEsc.'">'.$lblEsc.'</span>
                        </div>';
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<div class="flex items-center justify-center gap-2 whitespace-nowrap">';
                        $btn .= '<button onclick="showHaberDuzenle('.$row->id.')" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 rounded hover:bg-blue-100 transition-colors border border-blue-100" title="Düzenle"><span class="material-symbols-outlined text-[16px]">edit_square</span></button>';
                        $btn .= '<button onclick="showDeleteModal('.$row->id.', \''.e(addslashes($row->title)).'\')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded hover:bg-red-100 transition-colors border border-red-100" title="Sil"><span class="material-symbols-outlined text-[16px]">delete</span></button>';
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->filterColumn('news_info', function($query, $keyword) {
                        $query->where('news.title', 'like', "%{$keyword}%");
                    })
                    ->filterColumn('club_name', function($query, $keyword) {
                        $query->where('clubs.name', 'like', "%{$keyword}%");
                    })
                    ->rawColumns(['news_info', 'club_name', 'category_name', 'date', 'status', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error("DataTables Haber Error: " . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        $clubs = auth()->user()->isEditor() 
            ? Club::where('id', auth()->user()->club_id)->get() 
            : Club::where('is_active', true)->get();

        // İstatistikler için sorgu
        $statsQuery = News::query();
        if (auth()->user()->isEditor()) {
            $statsQuery->where(function($q) {
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

        return view('admin.haberler', compact('clubs', 'stats', 'categories'));
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
            $validated['image_path'] = $request->file('image')->store($path . '/news', 'uploads');
        }

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . substr(md5(\Illuminate\Support\Str::uuid()), 0, 8);
        $validated['is_published'] = ($validated['is_published'] === '1' || $validated['is_published'] === 'published');
        
        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        if (auth()->user()->isEditor()) {
            $validated['club_id'] = auth()->user()->club_id;
        }

        News::create($validated);

        return redirect()->route('admin.haberler')->with('success', 'Haber başarıyla oluşturuldu.');
    }

    public function show($id)
    {
        $haber = News::findOrFail($id);

        if (auth()->user()->isEditor() && $haber->club_id !== auth()->user()->club_id) {
            return response()->json(['error' => 'Bu haberi görme yetkiniz yok.'], 403);
        }

        return response()->json($haber);
    }

    public function update(Request $request, News $news)
    {
        if (auth()->user()->isEditor() && $news->club_id !== auth()->user()->club_id) {
            abort(403, 'Bu haberi düzenleme yetkiniz yok.');
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
            if ($news->image_path) {
                if (Storage::disk('uploads')->exists($news->image_path)) {
                    Storage::disk('uploads')->delete($news->image_path);
                } elseif (Storage::disk('public')->exists($news->image_path)) {
                    Storage::disk('public')->delete($news->image_path);
                }
            }

            $clubId = auth()->user()->isEditor() ? auth()->user()->club_id : (isset($validated['club_id']) ? $validated['club_id'] : $news->club_id);
            $club = $clubId ? Club::find($clubId) : null;
            $path = $club ? $club->slug : 'global';
            $validated['image_path'] = $request->file('image')->store($path . '/news', 'uploads');
        }

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']) . '-' . substr(md5(\Illuminate\Support\Str::uuid()), 0, 8);
        $validated['is_published'] = ($validated['is_published'] === '1' || $validated['is_published'] === 'published');
        
        if (auth()->user()->isEditor()) {
            $validated['club_id'] = auth()->user()->club_id;
        }

        $news->update($validated);

        return redirect()->route('admin.haberler')->with('success', 'Haber başarıyla güncellendi.');
    }

    public function destroy(News $news)
    {
        if (auth()->user()->isEditor() && $news->club_id !== auth()->user()->club_id) {
            abort(403, 'Bu haberi silme yetkiniz yok.');
        }

        if ($news->image_path) {
            if (Storage::disk('uploads')->exists($news->image_path)) {
                Storage::disk('uploads')->delete($news->image_path);
            } else {
                Storage::disk('public')->delete($news->image_path);
            }
        }
        $news->delete();
        return redirect()->route('admin.haberler')->with('success', 'Haber başarıyla silindi.');
    }
}
