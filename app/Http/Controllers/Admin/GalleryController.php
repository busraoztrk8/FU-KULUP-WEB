<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $images = GalleryImage::orderBy('order')->get();
        return view('admin.gallery', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'images' => 'required|array',
            'images.*' => 'required|image|max:10240', // Her resim max 10MB
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('global/gallery', 'uploads');

                GalleryImage::create([
                    'title' => $request->title,
                    'image_path' => $path,
                    'order' => $request->order ?? 0,
                    'is_active' => true,
                ]);
            }
        }

        return redirect()->route('admin.gallery')->with('success', count($request->file('images')) . ' adet resim galeriye eklendi.');
    }

    public function update(Request $request, GalleryImage $gallery)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $gallery->update($request->only('title', 'order', 'is_active'));

        return redirect()->route('admin.gallery')->with('success', 'Resim güncellendi.');
    }

    public function destroy(GalleryImage $gallery)
    {
        // Delete file from storage
        if (Storage::disk('uploads')->exists($gallery->image_path)) {
            Storage::disk('uploads')->delete($gallery->image_path);
        } elseif (Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery')->with('success', 'Resim galeriden silindi.');
    }
}
