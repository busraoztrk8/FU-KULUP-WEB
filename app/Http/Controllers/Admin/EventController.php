<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Club;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Event::leftJoin('clubs', 'events.club_id', '=', 'clubs.id')
                    ->leftJoin('categories', 'events.category_id', '=', 'categories.id')
                    ->select('events.*')
                    ->with(['club', 'category']);

                if (auth()->user()->isEditor()) {
                    $query->where(function($q) {
                        $q->where('events.club_id', auth()->user()->club_id)
                          ->orWhereNull('events.club_id');
                    });
                }

                // Filter by Status
                if ($request->filled('status') && $request->status !== 'all') {
                    $query->where('events.status', $request->status);
                }

                // Filter by club
                if ($request->filled('club_id')) {
                    $query->where('events.club_id', $request->club_id);
                }

                // Filter by category
                if ($request->filled('category_id')) {
                    $query->where('events.category_id', $request->category_id);
                }

                return \Yajra\DataTables\Facades\DataTables::of($query->orderBy('events.created_at', 'desc'))
                    ->addIndexColumn()
                    ->addColumn('event_info', function($row) {
                        // Title and Image combo for DataTables
                        $img = $row->image;
                        if ($img) {
                             if (!str_starts_with($img, 'http')) {
                                if (file_exists(public_path('uploads/' . $img))) {
                                    $img = asset('uploads/' . $img);
                                } else {
                                    $img = asset('storage/' . $img);
                                }
                            }
                        }
                        $imgHtml = $img ? '<img src="'.$img.'" class="w-10 h-10 rounded-lg object-cover shrink-0 shadow-sm" alt=""/>' : '<div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-primary text-[18px]">event</span></div>';
                        $t = e($row->title);
                        return '<div class="flex items-center gap-3 min-w-0 max-w-full">' . $imgHtml . '<span class="font-semibold text-slate-800 min-w-0 truncate" title="'.$t.'">' . $t . '</span></div>';
                    })
                    ->addColumn('club_name', function($row) {
                        if (!$row->club) {
                            return '<span class="text-slate-400 text-xs">—</span>';
                        }
                        $n = e($row->club->name);
                        return '<span class="px-2 py-1 bg-primary/10 text-primary rounded-lg text-xs font-bold max-w-full truncate inline-block align-bottom" title="'.$n.'">'.$n.'</span>';
                    })
                    ->addColumn('category_name', function($row) {
                        if (!$row->category) {
                            return '<span class="text-slate-400 text-sm">—</span>';
                        }
                        $n = e($row->category->name);
                        return '<span class="badge badge-primary max-w-full truncate inline-block align-bottom" title="'.$n.'">'.$n.'</span>';
                    })
                    ->addColumn('date', function($row) {
                        $d = $row->start_time ? $row->start_time->format('d M Y') : '-';
                        return '<span class="text-slate-500 text-sm whitespace-nowrap">'.e($d).'</span>';
                    })
                    ->addColumn('participants', function($row) {
                        $max = $row->max_participants ? '/'.$row->max_participants : ' / ∞';
                        return '<div class="text-center whitespace-nowrap tabular-nums"><span class="font-semibold">'.(int)$row->current_participants.'</span><span class="text-slate-400">'.e($max).'</span></div>';
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
                            
                        $lblEsc = e($label);
                        return '<div class="flex items-center gap-2 justify-center min-w-0 max-w-full">
			      <label class="relative inline-flex items-center cursor-pointer m-0 shrink-0" onclick="event.preventDefault(); toggleStatus(\'event\', '.$row->id.')">
                                <div class="w-11 h-6 rounded-full relative transition-colors '.$bgToggle.'">
                                    <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform '.($published ? 'translate-x-[20px]' : '').'"></span>
                                </div>
                              </label>
			      <span class="text-xs sm:text-sm font-semibold '.$lblColor.' min-w-0 truncate" title="'.$lblEsc.'">'.$lblEsc.'</span>
			    </div>';
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<div class="flex items-center justify-center gap-2 whitespace-nowrap">';
                        $btn .= '<button onclick="showEtkinlikDuzenle('.$row->id.')" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100" title="Düzenle"><span class="material-symbols-outlined text-[16px]">edit_square</span></button>';
                        $btn .= '<button onclick="showDeleteModal('.$row->id.', \''.e(addslashes($row->title)).'\')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors border border-red-100" title="Sil"><span class="material-symbols-outlined text-[16px]">delete</span></button>';
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->filterColumn('event_info', function($query, $keyword) {
                        $query->where('events.title', 'like', "%{$keyword}%");
                    })
                    ->filterColumn('club_name', function($query, $keyword) {
                        $query->where('clubs.name', 'like', "%{$keyword}%");
                    })
                    ->filterColumn('category_name', function($query, $keyword) {
                        $query->where('categories.name', 'like', "%{$keyword}%");
                    })
                    ->rawColumns(['event_info', 'club_name', 'category_name', 'date', 'participants', 'status', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error("DataTables Events Error: " . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        $categories = Category::all();
        $clubs      = auth()->user()->isEditor() 
                      ? Club::where('id', auth()->user()->club_id)->get() 
                      : Club::where('is_active', true)->get();

        // İstatistikler
        $statsQuery = Event::query();
        if (auth()->user()->isEditor()) {
            $statsQuery->where(function($q) {
                $q->where('club_id', auth()->user()->club_id)
                  ->orWhereNull('club_id');
            });
        }

        $stats = [
            'total'     => (clone $statsQuery)->count(),
            'active'    => (clone $statsQuery)->where('status', 'published')->count(),
            'completed' => (clone $statsQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $statsQuery)->where('status', 'cancelled')->count(),
        ];

        return view('admin.etkinlikler', compact('categories', 'clubs', 'stats'));
    }

    public function show(Event $event)
    {
        return response()->json($event->load(['speakers', 'program']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:100',
            'description'      => 'required|string|max:800',
            'short_description'=> 'nullable|string|max:150',
            'start_time'       => 'required|date',
            'end_time'         => 'nullable|date|after:start_time',
            'location'         => 'nullable|string|max:100',
            'location_url'     => 'nullable|url|max:255',
            'club_id'          => 'required|exists:clubs,id',
            'category_id'      => 'nullable|exists:categories,id',
            'max_participants'  => 'nullable|integer|min:1',
            'status'           => 'required|in:draft,published,cancelled,completed',
            'is_featured'      => 'boolean',
            'image'            => 'nullable|image|max:10240',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        if (auth()->user()->isEditor()) {
            $validated['club_id'] = auth()->user()->club_id;
        }

        if ($request->hasFile('image')) {
            $club = Club::findOrFail($validated['club_id']);
            $validated['image'] = $request->file('image')->store($club->slug . '/events', 'uploads');
        }

        $event = Event::create($validated);

        $this->syncEventDetails($event, $request);

        return redirect()->route('admin.etkinlikler')->with('success', 'Etkinlik başarıyla oluşturuldu.');
    }

    public function update(Request $request, Event $event)
    {
        if (auth()->user()->isEditor() && $event->club_id !== auth()->user()->club_id) {
            abort(403, 'Bu etkinliği düzenleme yetkiniz yok.');
        }

        $validated = $request->validate([
            'title'            => 'required|string|max:100',
            'description'      => 'required|string|max:800',
            'short_description'=> 'nullable|string|max:150',
            'start_time'       => 'required|date',
            'end_time'         => 'nullable|date|after:start_time',
            'location'         => 'nullable|string|max:100',
            'location_url'     => 'nullable|url|max:255',
            'club_id'          => 'required|exists:clubs,id',
            'category_id'      => 'nullable|exists:categories,id',
            'max_participants'  => 'nullable|integer|min:1',
            'status'           => 'required|in:draft,published,cancelled,completed',
            'is_featured'      => 'boolean',
            'image'            => 'nullable|image|max:10240',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        if (auth()->user()->isEditor()) {
            $validated['club_id'] = auth()->user()->club_id;
        }

        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($event->image) {
                if (Storage::disk('uploads')->exists($event->image)) {
                    Storage::disk('uploads')->delete($event->image);
                } elseif (Storage::disk('public')->exists($event->image)) {
                    Storage::disk('public')->delete($event->image);
                }
            }

            $club = Club::findOrFail($validated['club_id']);
            $validated['image'] = $request->file('image')->store($club->slug . '/events', 'uploads');
        }

        $event->update($validated);

        $this->syncEventDetails($event, $request);

        return redirect()->route('admin.etkinlikler')->with('success', 'Etkinlik güncellendi.');
    }

    public function destroy(Event $event)
    {
        if (auth()->user()->isEditor() && $event->club_id !== auth()->user()->club_id) {
            abort(403, 'Bu etkinliği silme yetkiniz yok.');
        }

        if ($event->image) {
            if (Storage::disk('uploads')->exists($event->image)) {
                Storage::disk('uploads')->delete($event->image);
            } else {
                Storage::disk('public')->delete($event->image);
            }
        }
        $event->delete();
        return redirect()->route('admin.etkinlikler')->with('success', 'Etkinlik silindi.');
    }

    public function registrations(Event $event)
    {
        if (auth()->user()->isEditor() && $event->club_id !== auth()->user()->club_id) {
            abort(403, 'Bu etkinliğin kayıtlarını görme yetkiniz yok.');
        }

        $registrations = \App\Models\EventRegistration::with('user')
            ->where('event_id', $event->id)
            ->latest()
            ->paginate(20);
        return view('admin.etkinlik-kayitlari', compact('event', 'registrations'));
    }

    private function syncEventDetails(Event $event, Request $request)
    {
        // Speakers
        if ($request->has('speakers')) {
            // If it's an update, we might want to handle existing vs new. 
            // Simplified: Clear and Re-add (or keep if IDs provided). 
            // For now, let's clear and re-add for simplicity in this admin UI context, 
            // but handle images carefully.
            
            $existing_speaker_images = $event->speakers()->pluck('image', 'id')->toArray();
            $event->speakers()->delete();
            
            foreach ($request->speakers as $index => $speakerData) {
                if (empty($speakerData['name'])) continue;

                $imagePath = $speakerData['existing_image'] ?? null;
                if ($request->hasFile("speakers.{$index}.image")) {
                    $clubSlug = $event->club->slug;
                    $imagePath = $request->file("speakers.{$index}.image")->store($clubSlug . '/speakers', 'uploads');
                }

                $event->speakers()->create([
                    'name' => $speakerData['name'],
                    'title' => $speakerData['title'] ?? null,
                    'image' => $imagePath,
                    'order' => $index
                ]);
            }
        }

        // Program
        if ($request->has('program')) {
            $event->program()->delete();
            foreach ($request->program as $index => $programData) {
                if (empty($programData['title'])) continue;

                $event->program()->create([
                    'time' => $programData['time'] ?? null,
                    'title' => $programData['title'],
                    'location' => $programData['location'] ?? null,
                    'order' => $index
                ]);
            }
        }
    }
}
