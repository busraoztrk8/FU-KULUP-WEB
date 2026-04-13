<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Club;

class HaberController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = News::query();

                if (auth()->user()->isEditor()) {
                    $query->where(function($q) {
                        $q->where('club_id', auth()->user()->club_id)
                          ->orWhereNull('club_id');
                    });
                }

                return \Yajra\DataTables\Facades\DataTables::of($query->oldest())
                    ->addIndexColumn()
                    ->addColumn('news_info', function($row) {
                        $url = $row->image_path ? asset('storage/'.$row->image_path) : asset('images/logo_orj.png');
                        return '<div class="flex items-center gap-3">
                            <div class="w-12 h-10 bg-white border border-slate-100 p-1 flex items-center justify-center rounded-lg shadow-sm shrink-0">
                                <img src="'.$url.'" class="max-w-full max-h-full object-contain" alt="Görsel">
                            </div>
                            <span class="font-bold text-slate-700">'.e($row->title).'</span>
                        </div>';
                    })
                    ->addColumn('club_name', function($row) {
                        return $row->club ? '<span class="px-3 py-1 bg-primary/10 text-primary rounded-lg text-xs font-bold">'.e($row->club->name).'</span>' : '<span class="text-slate-400 text-xs">—</span>';
                    })
                    ->addColumn('status', function($row) {
                        $bgToggle = $row->is_published ? 'bg-green-600' : 'bg-slate-200';
                        $lbl = $row->is_published ? 'Aktif' : 'Pasif';
                        $lblColor = $row->is_published ? 'text-slate-700' : 'text-slate-500';
                        
                        return '<div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer m-0" onclick="event.preventDefault(); toggleStatus(\'news\', '.$row->id.')">
                                <div class="w-11 h-6 rounded-full relative transition-colors '.$bgToggle.'">
                                    <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform '.($row->is_published ? 'translate-x-5' : '').'"></span>
                                </div>
                            </label>
                            <span class="text-sm font-semibold '.$lblColor.'">'.$lbl.'</span>
                        </div>';
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<div class="flex items-center justify-start gap-2">';
                        $btn .= '<button onclick="showHaberDuzenle('.$row->id.')" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 rounded hover:bg-blue-100 transition-colors border border-blue-100" title="Düzenle"><span class="material-symbols-outlined text-[16px]">edit_square</span></button>';
                        $btn .= '<button onclick="showDeleteModal('.$row->id.', \''.e(addslashes($row->title)).'\')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded hover:bg-red-100 transition-colors border border-red-100" title="Sil"><span class="material-symbols-outlined text-[16px]">delete</span></button>';
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->filterColumn('news_info', function($query, $keyword) {
                        $query->where('title', 'like', "%{$keyword}%");
                    })
                    ->filterColumn('club_name', function($query, $keyword) {
                        $query->whereHas('club', function($q) use ($keyword) {
                            $q->where('name', 'like', "%{$keyword}%");
                        });
                    })
                    ->rawColumns(['news_info', 'club_name', 'status', 'action'])
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

        return view('admin.haberler', compact('clubs', 'stats'));
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
            $validated['image_path'] = $request->file('image')->store('news', 'public');
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
            $validated['image_path'] = $request->file('image')->store('news', 'public');
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

        $news->delete();
        return redirect()->route('admin.haberler')->with('success', 'Haber başarıyla silindi.');
    }
}
