<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Club;

class DuyuruController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Announcement::query();

                if (auth()->user()->isEditor()) {
                    $query->where('club_id', auth()->user()->club_id);
                }

                $data = $query->latest()->get();
                return \Yajra\DataTables\Facades\DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('image', function($row) {
                        $url = $row->image_path ? asset('storage/'.$row->image_path) : asset('images/logo_orj.png');
                        return '<div class="w-16 h-12 bg-white border border-slate-100 p-1 flex items-center justify-center rounded-lg shadow-sm"><img src="'.$url.'" class="max-w-full max-h-full object-contain" alt="Görsel"></div>';
                    })
                    ->editColumn('title', function($row) {
                        return '<span class="font-medium text-slate-700">'.e($row->title).'</span>';
                    })
                    ->addColumn('order', function($row) {
                        return '<span class="text-slate-600 font-medium">0</span>';
                    })
                    ->addColumn('author', function($row) {
                        return '<span class="text-slate-600 font-medium">Admin</span>';
                    })
                    ->addColumn('status', function($row) {
                        $checked = $row->is_published ? 'checked' : '';
                        $bgToggle = $row->is_published ? 'bg-green-600' : 'bg-slate-200';
                        $lbl = $row->is_published ? 'Aktif' : 'Pasif';
                        $lblColor = $row->is_published ? 'text-slate-700' : 'text-slate-500';
                        
                        return '<div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer m-0" onclick="event.preventDefault(); toggleStatus(\'announcement\', '.$row->id.')">
                                <div class="w-11 h-6 rounded-full relative transition-colors '.$bgToggle.'">
                                    <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform '.($row->is_published ? 'translate-x-5' : '').'"></span>
                                </div>
                            </label>
                            <span class="text-sm font-semibold '.$lblColor.'">'.$lbl.'</span>
                        </div>';
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<div class="flex items-center justify-start gap-2">';
                        $btn .= '<button onclick="showDuyuruDuzenle('.$row->id.')" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 rounded hover:bg-blue-100 transition-colors border border-blue-100" title="Düzenle"><span class="material-symbols-outlined text-[16px]">edit_square</span></button>';
                        $btn .= '<button onclick="showDeleteModal('.$row->id.', \''.addslashes($row->title).'\')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded hover:bg-red-100 transition-colors border border-red-100" title="Sil"><span class="material-symbols-outlined text-[16px]">delete</span></button>';
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->rawColumns(['image', 'title', 'order', 'author', 'status', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error("DataTables Error: " . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return view('admin.duyurular');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'required|in:1,0,published,draft',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('announcements', 'public');
        }

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'required|in:1,0,published,draft',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('announcements', 'public');
        }

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        $validated['is_published'] = ($validated['is_published'] === '1' || $validated['is_published'] === 'published');
        
        if ($validated['is_published'] && !$announcement->is_published) {
            $validated['published_at'] = now();
        }

        if (auth()->user()->isEditor()) {
            $validated['club_id'] = auth()->user()->club_id;
        }

        $announcement->update($validated);

        return redirect()->route('admin.duyurular')->with('success', 'Duyuru başarıyla güncellendi.');
    }

    public function destroy(Announcement $announcement)
    {
        if (auth()->user()->isEditor() && $announcement->club_id !== auth()->user()->club_id) {
            abort(403, 'Bu duyuruyu silme yetkiniz yok.');
        }

        $announcement->delete();
        return redirect()->route('admin.duyurular')->with('success', 'Duyuru başarıyla silindi.');
    }
}
