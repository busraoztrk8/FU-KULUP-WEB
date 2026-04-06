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
        if ($request->ajax()) {
            try {
                $query = Event::with(['club', 'category']);

                if ($request->filled('status') && $request->status !== 'all') {
                    $query->where('status', $request->status);
                }

                $data = $query->latest()->get();

                return \Yajra\DataTables\Facades\DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('event_info', function($row) {
                        // Title and Image combo for DataTables
                        $img = $row->image ? asset('storage/' . $row->image) : null;
                        $imgHtml = $img ? '<img src="'.$img.'" class="w-10 h-10 rounded-lg object-cover shrink-0 shadow-sm" alt=""/>' : '<div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-primary text-[18px]">event</span></div>';
                        return '<div class="flex items-center gap-3">' . $imgHtml . '<span class="font-semibold text-slate-800">' . htmlspecialchars($row->title) . '</span></div>';
                    })
                    ->addColumn('category_name', function($row) {
                        return $row->category ? '<span class="badge badge-primary">'.htmlspecialchars($row->category->name).'</span>' : '<span class="text-slate-400 text-sm">—</span>';
                    })
                    ->addColumn('date', function($row) {
                        return '<span class="text-slate-500">' . ($row->start_time ? $row->start_time->format('d M Y') : '-') . '</span>';
                    })
                    ->addColumn('participants', function($row) {
                        $max = $row->max_participants ? '/'.$row->max_participants : ' / ∞';
                        return '<span class="font-semibold">'.$row->current_participants.'</span><span class="text-slate-400">'.$max.'</span>';
                    })
                    ->editColumn('status', function($row) {
                        $published = ($row->status === 'published');
                        $bgToggle = $published ? 'bg-green-600' : 'bg-slate-200';
                        $statusMap = [
                            'published'  => 'Aktif',
                            'draft'      => 'Pasif',
                            'cancelled'  => 'İptal',
                            'completed'  => 'Tamamlandı',
                        ];
                        $label = $statusMap[$row->status] ?? $row->status;
                        $lblColor = $published ? 'text-slate-700' : ($row->status === 'cancelled' ? 'text-red-500' : 'text-slate-500');
                            
                        return '<div class="flex items-center gap-3">
			      <label class="relative inline-flex items-center cursor-pointer m-0" onclick="event.preventDefault(); toggleStatus(\'event\', '.$row->id.')">
                                <div class="w-11 h-6 rounded-full relative transition-colors '.$bgToggle.'">
                                    <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform '.($published ? 'translate-x-[20px]' : '').'"></span>
                                </div>
                              </label>
			      <span class="text-sm font-semibold '.$lblColor.'">'.$label.'</span>
			    </div>';
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<div class="flex items-center justify-start gap-2">';
                        $btn .= '<button onclick="showEtkinlikDuzenle('.$row->id.', \''.addslashes($row->title).'\')" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100" title="Düzenle"><span class="material-symbols-outlined text-[16px]">edit_square</span></button>';
                        $btn .= '<button onclick="showDeleteModal('.$row->id.', \''.addslashes($row->title).'\')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors border border-red-100" title="Sil"><span class="material-symbols-outlined text-[16px]">delete</span></button>';
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->rawColumns(['event_info', 'category_name', 'date', 'participants', 'status', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error("DataTables Events Error: " . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        $categories = Category::all();
        $clubs      = Club::where('is_active', true)->get();

        return view('admin.etkinlikler', compact('categories', 'clubs'));
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
