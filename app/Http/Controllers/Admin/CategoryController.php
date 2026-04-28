<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Category::withCount(['clubs', 'events']);

            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('name_info', function($row) {
                    return '<span class="font-semibold text-slate-800">' . e($row->name) . '</span>';
                })
                ->addColumn('icon_color', function($row) {
                    $html = '<div class="flex items-center gap-2">';
                    if ($row->color) {
                        $html .= '<span class="w-4 h-4 rounded-full shadow-sm" style="background-color: ' . e($row->color) . '"></span>';
                    }
                    $html .= '<span class="text-sm text-slate-500 font-medium">' . e($row->icon ?? "—") . '</span>';
                    $html .= '</div>';
                    return $html;
                })
                ->addColumn('clubs_count', function($row) {
                    return '<span class="font-semibold text-slate-600">' . $row->clubs_count . '</span>';
                })
                ->addColumn('status', function($row) {
                    $checked = $row->is_active ? 'checked' : '';
                    return '<label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" onchange="toggleStatus(\'category\', ' . $row->id . ')" ' . $checked . '>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>';
                })
                ->addColumn('action', function($row) {
                    $btn = '<div class="flex items-center justify-end gap-1">';
                    $btn .= '<button onclick="showEditCategoryModal(' . $row->id . ', \'' . e(addslashes($row->name)) . '\', \'' . e($row->icon) . '\', \'' . e($row->color) . '\')" class="action-btn text-slate-400 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[18px]">edit</span></button>';
                    $btn .= '<form action="' . route('admin.kategoriler.destroy', $row->id) . '" method="POST" class="inline" onsubmit="return confirm(\'Bu kategoriyi silmek istediğinize emin misiniz?\')">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="action-btn action-btn-danger transition-all" title="Sil"><span class="material-symbols-outlined text-[18px]">delete</span></button></form>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['name_info', 'icon_color', 'clubs_count', 'status', 'action'])
                ->make(true);
        }

        $query = Category::withCount(['clubs', 'events']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate(20);

        return view('admin.kategoriler', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100|unique:categories',
            'icon'  => 'nullable|string|max:100',
            'color' => 'nullable|string|max:100',
        ]);

        $category = Category::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'category' => $category,
                'message' => 'Kategori başarıyla oluşturuldu.'
            ]);
        }

        return redirect()->route('admin.kategoriler')->with('success', 'Kategori oluşturuldu.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:100|unique:categories,name,' . $category->id,
            'icon'  => 'nullable|string|max:100',
            'color' => 'nullable|string|max:100',
        ]);

        $category->update($validated);

        return redirect()->route('admin.kategoriler')->with('success', 'Kategori güncellendi.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.kategoriler')->with('success', 'Kategori silindi.');
    }
}
