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
        $query = Club::with(['category', 'president']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $clubs      = $query->latest()->paginate(15);
        $categories = Category::all();
        $stats = [
            'total'    => Club::count(),
            'active'   => Club::where('is_active', true)->count(),
            'inactive' => Club::where('is_active', false)->count(),
        ];

        return view('admin.kulupler', compact('clubs', 'categories', 'stats'));
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
