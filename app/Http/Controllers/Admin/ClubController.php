<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClubController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Club::with(['category', 'president']);

                if ($request->filled('category_id') && $request->category_id !== 'all') {
                    $query->where('category_id', $request->category_id);
                }

                $data = $query->latest()->get();

                return \Yajra\DataTables\Facades\DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('club_info', function($row) {
                        $img = $row->logo ? asset('storage/' . $row->logo) : null;
                        $imgHtml = $img ? '<img src="'.$img.'" class="w-10 h-10 rounded-lg object-cover shrink-0 shadow-sm" alt=""/>' : '<div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-primary text-[18px]">groups</span></div>';
                        return '<div class="flex items-center gap-3">' . $imgHtml . '<span class="font-semibold text-slate-800">' . htmlspecialchars($row->name) . '</span></div>';
                    })
                    ->addColumn('category_name', function($row) {
                        return $row->category ? '<span class="badge badge-primary">'.htmlspecialchars($row->category->name).'</span>' : '<span class="text-slate-400 text-sm">—</span>';
                    })
                    ->addColumn('president_name', function($row) {
                        return $row->president ? htmlspecialchars($row->president->name) : '<span class="text-slate-400">Atanmadı</span>';
                    })
                    ->addColumn('members_count', function($row) {
                        return '<span class="font-semibold">' . $row->member_count . '</span>';
                    })
                    ->addColumn('status', function($row) {
                        $checked = $row->is_active ? 'checked' : '';
                        $bgToggle = $row->is_active ? 'bg-green-600' : 'bg-slate-200';
                        $lbl = $row->is_active ? 'Aktif' : 'Pasif';
                        $lblColor = $row->is_active ? 'text-slate-700' : 'text-slate-500';

                        return '<div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer m-0" onclick="event.preventDefault(); toggleStatus(\'club\', '.$row->id.')">
                                <div class="w-11 h-6 rounded-full relative transition-colors '.$bgToggle.'">
                                    <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform '.($row->is_active ? 'translate-x-[20px]' : '').'"></span>
                                </div>
                            </label>
                            <span class="text-sm font-semibold '.$lblColor.'">'.$lbl.'</span>
                        </div>';
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<div class="flex items-center justify-start gap-2">';
                        $btn .= '<button onclick="showKulupDuzenle('.$row->id.')" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100" title="Düzenle"><span class="material-symbols-outlined text-[16px]">edit_square</span></button>';
                        $btn .= '<button onclick="showDeleteModal('.$row->id.', \''.addslashes($row->name).'\')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors border border-red-100" title="Sil"><span class="material-symbols-outlined text-[16px]">delete</span></button>';
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->rawColumns(['club_info', 'category_name', 'president_name', 'members_count', 'status', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error("DataTables Clubs Error: " . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        $categories = Category::all();
        $stats = [
            'total'    => Club::count(),
            'active'   => Club::where('is_active', true)->count(),
            'inactive' => Club::where('is_active', false)->count(),
        ];

        return view('admin.kulupler', compact('categories', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255|unique:clubs',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id'       => 'nullable|exists:categories,id',
            'president_id'      => 'nullable|exists:users,id',
            'is_active'         => 'boolean',
            'logo'              => 'nullable|image|max:2048',
            'cover_image'       => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clubs/logos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('clubs/covers', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']);

        Club::create($validated);

        return redirect()->route('admin.kulupler')->with('success', 'Kulüp başarıyla oluşturuldu.');
    }

    public function update(Request $request, Club $club)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255|unique:clubs,name,' . $club->id,
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id'       => 'nullable|exists:categories,id',
            'president_id'      => 'nullable|exists:users,id',
            'is_active'         => 'boolean',
            'logo'              => 'nullable|image|max:2048',
            'cover_image'       => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clubs/logos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('clubs/covers', 'public');
        }

        $club->update($validated);

        return redirect()->route('admin.kulupler')->with('success', 'Kulüp güncellendi.');
    }

    public function destroy(Club $club)
    {
        $club->delete();
        return redirect()->route('admin.kulupler')->with('success', 'Kulüp silindi.');
    }
}
