<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\Club;
use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $galleryImages = GalleryImage::where('is_active', true)
            ->orderBy('order')
            ->get();

        // You might want to fetch other data here too
        $stats = [
            'clubs' => Club::count(),
            'students' => '10', // Hardcoded for now as per design
            'events' => Event::count(),
        ];

        return view('welcome', compact('galleryImages', 'stats'));
    }
}
