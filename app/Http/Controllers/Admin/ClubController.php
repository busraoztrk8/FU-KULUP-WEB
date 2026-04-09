<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Category;
use App\Models\User;
use App\Models\Role;
use App\Models\ClubMember;
use App\Models\ClubImage;
use App\Models\ClubDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


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

                $data = $query->oldest()->get();

                return \Yajra\DataTables\Facades\DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('club_info', function($row) {
                        $img = $row->logo ? asset('storage/' . $row->logo) : null;
                        $imgHtml = $img ? '<img src="'.$img.'" class="w-10 h-10 rounded-lg object-cover shrink-0 shadow-sm" alt=""/>' : '<div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-primary text-[18px]">groups</span></div>';
                        return '<div class="flex items-center gap-3">' . $imgHtml . '<span class="font-semibold text-slate-800">' . e($row->name) . '</span></div>';
                    })
                    ->addColumn('category_name', function($row) {
                        return $row->category ? '<span class="badge badge-primary">'.e($row->category->name).'</span>' : '<span class="text-slate-400 text-sm">—</span>';
                    })
                    ->addColumn('president_name', function($row) {
                        return $row->president ? e($row->president->name) : '<span class="text-slate-400">Atanmadı</span>';
                    })
                    ->addColumn('members_count', function($row) {
                        return '<span class="font-semibold">' . (int)$row->member_count . '</span>';
                    })
                    ->addColumn('status', function($row) {
                        $bgToggle = $row->is_active ? 'bg-green-600' : 'bg-slate-200';
                        $lbl = $row->is_active ? 'Aktif' : 'Pasif';
                        $lblColor = $row->is_active ? 'text-slate-700' : 'text-slate-500';

                        if (!auth()->user()->isAdmin()) {
                            return '<div class="flex items-center gap-2"><span class="w-2 h-2 rounded-full '.($row->is_active ? 'bg-green-500' : 'bg-slate-300').'"></span><span class="text-sm font-semibold '.$lblColor.'">'.e($lbl).'</span></div>';
                        }

                        return '<div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer m-0" onclick="event.preventDefault(); toggleStatus(\'club\', '.$row->id.')">
                                <div class="w-11 h-6 rounded-full relative transition-colors '.$bgToggle.'">
                                    <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform '.($row->is_active ? 'translate-x-[20px]' : '').'"></span>
                                </div>
                            </label>
                            <span class="text-sm font-semibold '.$lblColor.'">'.e($lbl).'</span>
                        </div>';
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<div class="flex items-center justify-start gap-2">';
                        $btn .= '<a href="/admin/kulupler/'.$row->id.'/uyeler" class="w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors border border-amber-100" title="Üyeler"><span class="material-symbols-outlined text-[16px]">group</span></a>';
                        $btn .= '<button onclick="showKulupDuzenle('.$row->id.')" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100" title="Düzenle"><span class="material-symbols-outlined text-[16px]">edit_square</span></button>';
                        
                        if (auth()->user()->isAdmin()) {
                            $btn .= '<button onclick="showDeleteModal('.$row->id.', \''.e(addslashes($row->name)).'\')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors border border-red-100" title="Sil"><span class="material-symbols-outlined text-[16px]">delete</span></button>';
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

    public function checkPresident(User $user)
    {
        $club = Club::where('president_id', $user->id)->first();
        return response()->json([
            'is_president' => !!$club,
            'club_id' => $club?->id,
            'club_name' => $club?->name
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Kulüp oluşturma yetkiniz yok.');
        }

        $validated = $request->validate([
            'name'              => 'required|string|max:100|unique:clubs',
            'description'       => 'nullable|string|max:800',
            'short_description' => 'nullable|string|max:150',
            'category_id'       => 'nullable|exists:categories,id',
            'president_id'      => 'nullable|exists:users,id',
            'is_active'         => 'boolean',
            'logo'              => 'nullable|image|max:10240',
            'cover_image'       => 'nullable|image|max:10240',
            'website_url'       => 'nullable|url|max:255',
            'instagram_url'     => 'nullable|url|max:255',
            'youtube_url'       => 'nullable|url|max:255',
            'twitter_url'       => 'nullable|url|max:255',
            'facebook_url'      => 'nullable|url|max:255',
            'mission'           => 'nullable|string|max:300',
            'vision'            => 'nullable|string|max:300',
            'founder_name'      => 'nullable|string|max:100',
            'established_year'  => 'nullable|string|max:10',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clubs/logos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('clubs/covers', 'public');
        }

        try {
            return DB::transaction(function() use ($validated, $request) {
                \Log::info("Club Store Started", ['name' => $validated['name'], 'president_id' => $validated['president_id'] ?? 'none']);
                
                // Bir kullanıcı sadece bir kulübün başkanı olabilir mantığı
                if (!empty($validated['president_id'])) {
                    Club::where('president_id', $validated['president_id'])->update(['president_id' => null]);
                }

                $club = Club::create($validated);

                if ($club->president_id) {
                    $this->syncPresidentRole($club);
                }

                return redirect()->route('admin.kulupler')->with('success', 'Kulüp başarıyla oluşturuldu.');
            });
        } catch (\Exception $e) {
            \Log::error("Club Store Error: " . $e->getMessage());
            return back()->withInput()->with('error', 'Kulüp oluşturulurken bir hata oluştu: ' . $e->getMessage());
        }

    }

    public function update(Request $request, Club $club)
    {
        if (auth()->user()->isEditor() && $club->id !== auth()->user()->club_id) {
            abort(403, 'Bu kulübü düzenleme yetkiniz yok.');
        }

        $validated = $request->validate([
            'name'              => 'required|string|max:100|unique:clubs,name,' . $club->id,
            'description'       => 'nullable|string|max:800',
            'short_description' => 'nullable|string|max:150',
            'category_id'       => 'nullable|exists:categories,id',
            'president_id'      => 'nullable|exists:users,id',
            'is_active'         => 'boolean',
            'logo'              => 'nullable|image|max:10240',
            'cover_image'       => 'nullable|image|max:10240',
            'website_url'       => 'nullable|url|max:255',
            'instagram_url'     => 'nullable|url|max:255',
            'youtube_url'       => 'nullable|url|max:255',
            'twitter_url'       => 'nullable|url|max:255',
            'facebook_url'      => 'nullable|url|max:255',
            'mission'           => 'nullable|string|max:300',
            'vision'            => 'nullable|string|max:300',
            'founder_name'      => 'nullable|string|max:100',
            'established_year'  => 'nullable|string|max:10',
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

        try {
            return DB::transaction(function() use ($validated, $club, $request) {
                $oldPresidentId = $club->getOriginal('president_id');
                \Log::info("Club Update Started", ['club_id' => $club->id, 'old_pres' => $oldPresidentId, 'new_pres' => $validated['president_id'] ?? 'none']);

                // Bir kullanıcı sadece bir kulübün başkanı olabilir mantığı (Veri temizliği sağlar)
                if (!empty($validated['president_id'])) {
                    Club::where('president_id', $validated['president_id'])
                        ->where('id', '!=', $club->id)
                        ->update(['president_id' => null]);
                }

                $club->update($validated);

                // Galeri yükleme
                if ($request->hasFile('gallery')) {
                    foreach ($request->file('gallery') as $image) {
                        $path = $image->store('clubs/gallery', 'public');
                        $club->images()->create(['image_path' => $path]);
                    }
                }

                // Başkan değiştiyse veya mevcut başkanın rolünün senkronize edilmesi gerekiyorsa
                if ($oldPresidentId != $club->president_id || $club->president_id) {
                    $this->syncPresidentRole($club, $oldPresidentId);
                }

                return redirect()->route('admin.kulupler')->with('success', 'Kulüp güncellendi.');
            });
        } catch (\Exception $e) {
            \Log::error("Club Update Error: " . $e->getMessage());
            return back()->withInput()->with('error', 'Kulüp güncellenirken bir hata oluştu: ' . $e->getMessage());
        }

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

        try {
            DB::transaction(function() use ($club, $user) {
                $oldPresidentId = $club->president_id;
                
                // Kulübün yeni başkanını ata
                $club->update(['president_id' => $user->id]);

                // Rolleri senkronize et
                $this->syncPresidentRole($club, $oldPresidentId);
            });

            return back()->with('success', $user->name . ' başarıyla yeni kulüp başkanı olarak atandı.');
        } catch (\Exception $e) {
            \Log::error("Set President Error: " . $e->getMessage());
            return back()->with('error', 'Başkan atanırken bir hata oluştu.');
        }
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
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();
        return response()->json(['success' => true]);
    }

    /**
     * Kulüp dosyalarını listele
     */
    public function documents(Request $request)
    {
        $club = null;
        if (auth()->user()->isEditor()) {
            $club = Club::findOrFail(auth()->user()->club_id);
        } elseif ($request->has('club_id')) {
            $club = Club::findOrFail($request->club_id);
        }

        if (!$club) {
            // Admin için tüm kulüplerin listesini getir veya bir kulüp seçmesini iste
            $clubs = Club::all();
            return view('admin.kulup-dosyalari', compact('clubs'));
        }

        $documents = $club->documents()->latest()->paginate(20);
        return view('admin.kulup-dosyalari', compact('club', 'documents'));
    }

    /**
     * Yeni dosya yükle
     */
    public function storeDocument(Request $request)
    {
        $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'file'    => 'required|file|max:20480', // Max 20MB
        ]);

        $club = Club::findOrFail($request->club_id);
        
        if (auth()->user()->isEditor() && $club->id !== auth()->user()->club_id) {
            abort(403, 'Bu kulübe dosya yükleme yetkiniz yok.');
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $path = $file->store('clubs/documents/' . $club->id, 'public');

            ClubDocument::create([
                'club_id'   => $club->id,
                'file_name' => $fileName,
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
            ]);

            return back()->with('success', 'Dosya başarıyla yüklendi.');
        }

        return back()->with('error', 'Dosya yüklenemedi.');
    }

    /**
     * Dosyayı sil (Soft delete yok - Hard delete)
     */
    public function destroyDocument(ClubDocument $document)
    {
        if (auth()->user()->isEditor() && $document->club_id !== auth()->user()->club_id) {
            abort(403, 'Bu dosyayı silme yetkiniz yok.');
        }

        // Fiziksel dosyayı sil
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // DB kaydını sil (Hard delete)
        $document->forceDelete();

        return back()->with('success', 'Dosya kalıcı olarak silindi.');
    }

    /**
     * Kulüp başkanı rollerini senkronize eder.
     * Yeni başkanı editör yapar, eski başkanı (eğer başka kulübü yoksa) öğrenci yapar.
     */
    private function syncPresidentRole(Club $club, $oldPresidentId = null)
    {
        \Log::info("Syncing President Roles", [
            'club_id' => $club->id, 
            'current_pres' => $club->president_id, 
            'old_pres' => $oldPresidentId
        ]);

        // 1. Eski başkanı işle (eğer değiştiyse)
        if ($oldPresidentId && $oldPresidentId != $club->president_id) {
            $oldPresident = User::find($oldPresidentId);
            if ($oldPresident && !$oldPresident->isAdmin()) {
                // Sadece başka kulüplerde başkan değilse rolünü 'student' yap
                $isStillPresident = Club::where('president_id', $oldPresidentId)
                                      ->where('id', '!=', $club->id)
                                      ->exists();
                if (!$isStillPresident) {
                    $studentRole = Role::where('name', 'student')->first();
                    $oldPresident->update([
                        'role_id' => $studentRole->id ?? $oldPresident->role_id,
                        'club_id' => null
                    ]);
                    \Log::info("Old president demoted to student", ['user_id' => $oldPresidentId]);
                }
            }
        }

        // 2. Mevcut/Yeni başkanı işle
        if ($club->president_id) {
            $user = User::find($club->president_id);
            if ($user) {
                $updateData = ['club_id' => $club->id];
                
                // Admin değilse rolünü 'editor' yap
                if (!$user->isAdmin()) {
                    $editorRole = Role::where('name', 'editor')->first();
                    if ($editorRole) {
                        $updateData['role_id'] = $editorRole->id;
                    }
                }
                
                $user->update($updateData);
                \Log::info("President synchronized", [
                    'user_id' => $user->id, 
                    'role_id' => $user->role_id, 
                    'club_id' => $user->club_id
                ]);
            }
        }
    }
}

