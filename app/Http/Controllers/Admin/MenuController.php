<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $mainMenus   = Menu::where('location', 'main')->orderBy('order')->get();
        $footerMenus = Menu::where('location', 'footer')->orderBy('order')->get();
        return view('admin.menu', compact('mainMenus', 'footerMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label'    => 'required|string|max:100',
            'url'      => 'required|string|max:500',
            'location' => 'required|in:main,footer',
            'target'   => 'nullable|in:_self,_blank',
            'order'    => 'nullable|integer',
        ]);

        Menu::create([
            'label'     => $request->label,
            'url'       => $request->url,
            'location'  => $request->location,
            'target'    => $request->target ?? '_self',
            'order'     => $request->order ?? Menu::where('location', $request->location)->max('order') + 1,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.menu')->with('success', 'Menü öğesi eklendi.');
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'label'    => 'required|string|max:100',
            'url'      => 'required|string|max:500',
            'location' => 'required|in:main,footer',
            'target'   => 'nullable|in:_self,_blank',
            'order'    => 'nullable|integer',
        ]);

        $menu->update([
            'label'     => $request->label,
            'url'       => $request->url,
            'location'  => $request->location,
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
        return back()->with('success', 'Durum güncellendi.');
    }
}
