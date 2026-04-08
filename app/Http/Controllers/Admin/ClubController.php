<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Category;
use App\Models\User;
use App\Models\Role;
use App\Models\ClubMember;
use App\Models\ClubImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClubController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $query = Club::with(['category', 'president']);

                if (auth()->user()->isEditor()) {
                    $query->where('id', auth()->user()->club_id);
                }

                if ($request->filled('category_id') && $request->category_id !== 'all') {
                    $query->where('category_id', $request->category_id);
                }

                $data = $query->latest()->get();

                return \Yajra\DataTables\Facades\DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('club_info', function($row) {
                        $img = $row->logo ? asset('storage/' . $row->logo) : null;
                        $imgHtml = $img ? '<img src="'.$img.'" class="w-10 h-10 rounded-lg object-cover shrink-0 shadow-sm" alt=""/>' : '<div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-primary text-[18px]">groups</span></div>';
                        return '<div class="flex items-center gap-3">' . $imgHtml . '<span class="font-semibold text-slate-800">' . htmlspecialchars($row->name) . '</span></div>';
                    })
                    ->addColumn('category_name', function($row) {
                        return $row->category ? '<span class="badge badge-primary">'.htmlspecialchars($row->category->name).'</span>' : '<span class="text-slate-400 text-sm">—</span>';
                    })
                    ->addColumn('president_name', function($row) {
                        return $row->president ? htmlspecialchars($row->president->name) : '<span class="text-slate-400">Atanmadı</span>';
                    })
                    ->addColumn('members_count', function($row) {
                        return '<span class="font-semibold">' . $row->member_count . '</span>';
                    })
                    ->addColumn('status', function($row) {
                        $bgToggle = $row->is_active ? 'bg-green-600' : 'bg-slate-200';
                        $lbl = $row->is_active ? 'Aktif' : 'Pasif';
                        $lblColor = $row->is_active ? 'text-slate-700' : 'text-slate-500';

                        if (!auth()->user()->isAdmin()) {
                            return '<div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full '.($row->is_active ? 'bg-green-500' : 'bg-slate-300').'"></span><span class="text-sm font-semibold '.$lblColor.'">'.$lbl.'</span></div>';
                        }

                        return '<div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer m-0" onclick="event.preventDefault(); toggleStatus(\'club\', '.$row->id.')">
                                <div class="w-11 h-6 rounded-full relative transition-colors '.$bgToggle.'">
                                    <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform '.($row->is_active ? 'translate-x-[20px]' : '').'"></span>
                                </div>
                            </label>
                            <span class="text-sm font-semibold '.$lblColor.'">'.$lbl.'</span>
                        </div>';
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<div class="flex items-center justify-start gap-2">';
                        $btn .= '<a href="/admin/kulupler/'.$row->id.'/uyeler" class="w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors border border-amber-100" title="Üyeler"><span class="material-symbols-outlined text-[16px]">group</span></a>';
                        $btn .= '<button onclick="showKulupDuzenle('.$row->id.')" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100" title="Düzenle"><span class="material-symbols-outlined text-[16px]">edit_square</span></button>';
                        
                        if (auth()->user()->isAdmin()) {
                            $btn .= '<button onclick="showDeleteModal('.$row->id.', \''.addslashes($row->name).'\')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors border border-red-100" title="Sil"><span class="material-symbols-outlined text-[16px]">delete</span></button>';
                        }
                        
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->rawColumns(['club_info', 'category_name', 'president_name', 'members_count', 'status', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error("DataTables Clubs Error: " . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        $categories = Category::all();
        $users = User::whereHas('role', function($q) {
            $q->whereIn('name', ['editor', 'student']);
        })->get();

        $stats = [
            'total'    => Club::count(),
            'active'   => Club::where('is_active', true)->count(),
            'inactive' => Club::where('is_active', false)->count(),
        ];

        return view('admin.kulupler', compact('categories', 'users', 'stats'));
    }

    public function show(Club $club)
    {
        return response()->json($club->load(['images', 'president']));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Kulüp oluşturma yetkiniz yok.');
        }

        $validated = $request->validate([
            'name'              => 'required|string|max:255|unique:clubs',
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id'       => 'nullable|exists:categories,id',
            'president_id'      => 'nullable|exists:users,id',
            'is_active'         => 'boolean',
            'logo'              => 'nullable|image|max:10240',
            'cover_image'       => 'nullable|image|max:10240',
            'website_url'       => 'nullable|url',
            'instagram_url'     => 'nullable|url',
            'youtube_url'       => 'nullable|url',
            'twitter_url'       => 'nullable|url',
            'facebook_url'      => 'nullable|url',
            'mission'           => 'nullable|string',
            'vision'            => 'nullable|string',
            'founder_name'      => 'nullable|string|max:255',
            'established_year'  => 'nullable|string|max:20',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clubs/logos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('clubs/covers', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']);

        $club = Club::create($validated);

        // Atanan başkanı o kulübün editörü yap
        if ($club->president_id) {
            $president = User::find($club->president_id);
            if ($president) {
                $editorRole = Role::where('name', 'editor')->first();
                $updateData = ['club_id' => $club->id];
                if (!$president->isAdmin() && $editorRole) {
                    $updateData['role_id'] = $editorRole->id;
                }
                $president->update($updateData);
            }
        }

        return redirect()->route('admin.kulupler')->with('success', 'Kulüp başarıyla oluşturuldu.');
    }

    public function update(Request $request, Club $club)
    {
        if (auth()->user()->isEditor() && $club->id !== auth()->user()->club_id) {
            abort(403, 'Bu kulübü düzenleme yetkiniz yok.');
        }

        $validated = $request->validate([
            'name'              => 'required|string|max:255|unique:clubs,name,' . $club->id,
            'description'       => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category_id'       => 'nullable|exists:categories,id',
            'president_id'      => 'nullable|exists:users,id',
            'is_active'         => 'boolean',
            'logo'              => 'nullable|image|max:10240',
            'cover_image'       => 'nullable|image|max:10240',
            'website_url'       => 'nullable|url',
            'instagram_url'     => 'nullable|url',
            'youtube_url'       => 'nullable|url',
            'twitter_url'       => 'nullable|url',
            'facebook_url'      => 'nullable|url',
            'mission'           => 'nullable|string',
            'vision'            => 'nullable|string',
            'founder_name'      => 'nullable|string|max:255',
            'established_year'  => 'nullable|string|max:20',
            'gallery'           => 'nullable|array',
            'gallery.*'         => 'image|max:10240',
        ]);

        if (auth()->user()->isEditor()) {
            unset($validated['is_active']);
            unset($validated['president_id']);
        }

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clubs/logos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('clubs/covers', 'public');
        }

        $oldPresidentId = $club->getOriginal('president_id');

        $club->update($validated);

        // Galeri yükleme
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('clubs/gallery', 'public');
                $club->images()->create(['image_path' => $path]);
            }
        }

        // Başkan değiştiyse yetkilerini güncelle
        if ($oldPresidentId != $club->president_id) {
            // Eski başkanı normale döndür
            if ($oldPresidentId) {
                $oldPresident = User::find($oldPresidentId);
                if ($oldPresident && !$oldPresident->isAdmin()) {
                    $userRole = Role::where('name', 'student')->first();
                    $oldPresident->update([
                        'role_id' => $userRole->id ?? $oldPresident->role_id,
                        'club_id' => null
                    ]);
                }
            }

            // Yeni başkanı editör yap
            if ($club->president_id) {
                $newPresident = User::find($club->president_id);
                if ($newPresident) {
                    $editorRole = Role::where('name', 'editor')->first();
                    $updateData = ['club_id' => $club->id];
                    if (!$newPresident->isAdmin() && $editorRole) {
                        $updateData['role_id'] = $editorRole->id;
                    }
                    $newPresident->update($updateData);
                }
            }
        }

        return redirect()->route('admin.kulupler')->with('success', 'Kulüp güncellendi.');
    }

    public function destroy(Club $club)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Kulüp silme yetkiniz yok.');
        }

        // Başkanı normale döndür
        if ($club->president_id) {
            $president = User::find($club->president_id);
            if ($president && !$president->isAdmin()) {
                $userRole = Role::where('name', 'student')->first();
                $president->update([
                    'role_id' => $userRole->id ?? $president->role_id,
                    'club_id' => null
                ]);
            }
        }

        $club->delete();
        return redirect()->route('admin.kulupler')->with('success', 'Kulüp silindi.');
    }

    public function members(Club $club)
    {
        if (auth()->user()->isEditor() && $club->id !== auth()->user()->club_id) {
            abort(403, 'Bu kulübün üyelerini görme yetkiniz yok.');
        }

        $filter = request('status', 'all');

        $query = ClubMember::with('user')
            ->where('club_id', $club->id);

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $members = $query->latest()->paginate(20)->appends(['status' => $filter]);

        $stats = [
            'total'    => ClubMember::where('club_id', $club->id)->count(),
            'pending'  => ClubMember::where('club_id', $club->id)->where('status', 'pending')->count(),
            'approved' => ClubMember::where('club_id', $club->id)->where('status', 'approved')->count(),
            'rejected' => ClubMember::where('club_id', $club->id)->where('status', 'rejected')->count(),
        ];

        return view('admin.kulup-uyeleri', compact('club', 'members', 'stats', 'filter'));
    }

    public function approveMember(ClubMember $member)
    {
        if (auth()->user()->isEditor() && $member->club_id !== auth()->user()->club_id) {
            abort(403, 'Bu üyeyi onaylama yetkiniz yok.');
        }

        if ($member->status !== 'approved') {
            $member->update(['status' => 'approved', 'approved_at' => now()]);
        }
        return back()->with('success', 'Üyelik onaylandı.');
    }

    public function rejectMember(ClubMember $member)
    {
        if (auth()->user()->isEditor() && $member->club_id !== auth()->user()->club_id) {
            abort(403, 'Bu üyeyi reddetme yetkiniz yok.');
        }

        if ($member->status === 'approved') {
            $member->club->decrement('member_count');
        }
        $member->update(['status' => 'rejected']);
        return back()->with('success', 'Üyelik reddedildi.');
    }

    public function removeMember(ClubMember $member)
    {
        if (auth()->user()->isEditor() && $member->club_id !== auth()->user()->club_id) {
            abort(403, 'Bu üyeyi silme yetkiniz yok.');
        }

        if ($member->status === 'approved' || $member->status === 'pending') {
            $member->club->decrement('member_count');
        }
        $member->delete();
        return back()->with('success', 'Üyelik kaydı silindi.');
    }

    public function setPresident(Club $club, User $user)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Başkan atama yetkiniz yok.');
        }

        // Kulübün yeni başkanını ata
        $club->update(['president_id' => $user->id]);

        // Kullanıcının rolünü editör yap (eğer admin değilse) ve kulüp ID'sini ata
        $updateData = ['club_id' => $club->id];
        if (!$user->isAdmin()) {
            $editorRole = Role::where('name', 'editor')->first();
            if ($editorRole) {
                $updateData['role_id'] = $editorRole->id;
            }
        }
        $user->update($updateData);

        return back()->with('success', $user->name . ' başarıyla yeni kulüp başkanı olarak atandı.');
    }

    public function updateMemberTitle(Request $request, ClubMember $member)
    {
        if (auth()->user()->isEditor() && $member->club_id !== auth()->user()->club_id) {
            abort(403, 'Bu üyenin unvanını değiştirme yetkiniz yok.');
        }

        $request->validate(['title' => 'nullable|string|max:255']);
        $member->update(['title' => $request->title]);

        return back()->with('success', 'Üye unvanı başarıyla güncellendi.');
    }

    public function deleteGalleryImage(ClubImage $image)
    {
        if (auth()->user()->isEditor() && $image->club->id !== auth()->user()->club_id) {
            abort(403, 'Bu resmi silme yetkiniz yok.');
        }

        // Dosyayı sil
        if (\Storage::disk('public')->exists($image->image_path)) {
            \Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();
        return response()->json(['success' => true]);
    }
}
