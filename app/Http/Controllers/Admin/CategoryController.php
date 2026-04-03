<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
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

        Category::create($validated);

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
