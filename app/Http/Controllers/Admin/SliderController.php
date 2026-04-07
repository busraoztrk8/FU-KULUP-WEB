<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order')->get();
        return view('admin.slider', compact('sliders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'       => 'required|image|max:5120',
            'title'       => 'nullable|string|max:255',
            'subtitle'    => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_url'  => 'nullable|string|max:500',
            'order'       => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
        ]);

        $path = $request->file('image')->store('sliders', 'public');

        Slider::create([
            'image_path'  => $path,
            'title'       => $request->title,
            'subtitle'    => $request->subtitle,
            'button_text' => $request->button_text,
            'button_url'  => $request->button_url,
            'order'       => $request->order ?? Slider::max('order') + 1,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.slider')->with('success', 'Slide eklendi.');
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'title'       => 'nullable|string|max:255',
            'subtitle'    => 'nullable|string|max:500',
            'button_text' => 'nullable|string|max:100',
            'button_url'  => 'nullable|string|max:500',
            'order'       => 'nullable|integer',
            'is_active'   => 'nullable|boolean',
            'image'       => 'nullable|image|max:5120',
        ]);

        $data = $request->only(['title', 'subtitle', 'button_text', 'button_url', 'order']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($slider->image_path);
            $data['image_path'] = $request->file('image')->store('sliders', 'public');
        }

        $slider->update($data);

        return redirect()->route('admin.slider')->with('success', 'Slide güncellendi.');
    }

    public function destroy(Slider $slider)
    {
        Storage::disk('public')->delete($slider->image_path);
        $slider->delete();
        return redirect()->route('admin.slider')->with('success', 'Slide silindi.');
    }

    public function reorder(Request $request)
    {
        foreach ($request->order as $index => $id) {
            Slider::where('id', $id)->update(['order' => $index + 1]);
        }
        return response()->json(['success' => true]);
    }
}
