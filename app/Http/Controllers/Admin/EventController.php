<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Club;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['club', 'category']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $events     = $query->latest()->paginate(15);
        $categories = Category::all();
        $clubs      = Club::where('is_active', true)->get();

        return view('admin.etkinlikler', compact('events', 'categories', 'clubs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'short_description'=> 'nullable|string|max:500',
            'start_time'       => 'required|date',
            'end_time'         => 'nullable|date|after:start_time',
            'location'         => 'nullable|string|max:255',
            'location_url'     => 'nullable|url',
            'club_id'          => 'required|exists:clubs,id',
            'category_id'      => 'nullable|exists:categories,id',
            'max_participants'  => 'nullable|integer|min:1',
            'status'           => 'required|in:draft,published,cancelled,completed',
            'is_featured'      => 'boolean',
            'image'            => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']);

        Event::create($validated);

        return redirect()->route('admin.etkinlikler')->with('success', 'Etkinlik başarıyla oluşturuldu.');
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'short_description'=> 'nullable|string|max:500',
            'start_time'       => 'required|date',
            'end_time'         => 'nullable|date|after:start_time',
            'location'         => 'nullable|string|max:255',
            'location_url'     => 'nullable|url',
            'club_id'          => 'required|exists:clubs,id',
            'category_id'      => 'nullable|exists:categories,id',
            'max_participants'  => 'nullable|integer|min:1',
            'status'           => 'required|in:draft,published,cancelled,completed',
            'is_featured'      => 'boolean',
            'image'            => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.etkinlikler')->with('success', 'Etkinlik güncellendi.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.etkinlikler')->with('success', 'Etkinlik silindi.');
    }
}
