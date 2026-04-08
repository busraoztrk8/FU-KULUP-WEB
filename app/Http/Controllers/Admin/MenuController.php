<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $mainMenus = Menu::whereNull('parent_id')->orderBy('order')->get();
        $allMenus = Menu::orderBy('label')->get();
        
        $stats = [
            'total' => Menu::count(),
            'main' => Menu::whereNull('parent_id')->count(),
            'active' => Menu::where('is_active', true)->count(),
        ];
        
        return view('admin.menu', compact('mainMenus', 'allMenus', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label'     => 'required|string|max:100',
            'url'       => 'required|string|max:500',
            'parent_id' => 'nullable|exists:menus,id',
            'target'    => 'nullable|in:_self,_blank',
            'order'     => 'nullable|integer',
        ]);

        Menu::create([
            'label'     => $request->label,
            'url'       => $request->url,
            'location'  => 'main',
            'parent_id' => $request->parent_id,
            'target'    => $request->target ?? '_self',
            'order'     => $request->order ?? Menu::where('parent_id', $request->parent_id)->max('order') + 1,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.menu')->with('success', 'Menü öğesi eklendi.');
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'label'     => 'required|string|max:100',
            'url'       => 'required|string|max:500',
            'parent_id' => 'nullable|exists:menus,id|different:id',
            'target'    => 'nullable|in:_self,_blank',
            'order'     => 'nullable|integer',
        ]);

        $menu->update([
            'label'     => $request->label,
            'url'       => $request->url,
            'parent_id' => $request->parent_id,
            'target'    => $request->target ?? '_self',
            'order'     => $request->order ?? $menu->order,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.menu')->with('success', 'Menü öğesi güncellendi.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menu')->with('success', 'Menü öğesi silindi.');
    }

    public function toggle(Menu $menu)
    {
        $menu->update(['is_active' => !$menu->is_active]);
        return response()->json(['success' => true]);
    }
}
