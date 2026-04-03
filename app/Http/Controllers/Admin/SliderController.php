<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        return view('admin.slider');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'image'    => 'required|image|max:5120',
            'link'     => 'nullable|url',
            'order'    => 'nullable|integer',
            'is_active'=> 'boolean',
        ]);

        // Slider modeli olmadığı için session flash ile dönüyoruz
        return redirect()->route('admin.slider')->with('success', 'Slider eklendi.');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.slider')->with('success', 'Slider silindi.');
    }
}
