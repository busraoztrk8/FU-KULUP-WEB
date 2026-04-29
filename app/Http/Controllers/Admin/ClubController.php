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
use App\Models\ClubFormField;
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
                $query = Club::leftJoin('categories', 'clubs.category_id', '=', 'categories.id')
                    ->leftJoin('users as presidents', 'clubs.president_id', '=', 'presidents.id')
                    ->select('clubs.*')
                    ->with(['category', 'president'])
                    ->withCount('approvedMembers');

                if (auth()->user()->isEditor()) {
                    $query->where('clubs.id', auth()->user()->club_id);
                }

                if ($request->filled('category_id') && $request->category_id !== 'all') {
                    $query->where('clubs.category_id', $request->category_id);
                }

                if ($request->filled('status') && $request->status !== 'all') {
                    $query->where('clubs.is_active', $request->status === 'active');
                }

                return \Yajra\DataTables\Facades\DataTables::of($query->oldest('clubs.id'))
                    ->addIndexColumn()
                    ->filter(function ($query) use ($request) {
                        $search = (string) data_get($request->input('search'), 'value', '');
                        $search = trim($search);
                        if ($search === '') return;

                        $query->where(function ($q) use ($search) {
                            $q->where('clubs.name', 'like', "%{$search}%")
                              ->orWhere('clubs.short_description', 'like', "%{$search}%")
                              ->orWhere('categories.name', 'like', "%{$search}%")
                              ->orWhere('presidents.name', 'like', "%{$search}%");
                        });
                    })
                    ->addColumn('club_info', function($row) {
                        $img = $row->logo;
                        if ($img) {
                            if (!str_starts_with($img, 'http')) {
                                if (file_exists(public_path('uploads/' . $img))) {
                                    $img = asset('uploads/' . $img);
                                } else {
                                    $img = asset('storage/' . $img);
                                }
                            }
                        } else {
                            $img = null;
                        }
                        $imgHtml = $img ? '<img src="'.$img.'" class="w-10 h-10 rounded-lg object-cover shrink-0 shadow-sm" alt=""/>' : '<div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"><span class="material-symbols-outlined text-primary text-[18px]">groups</span></div>';
                        $n = e($row->name);
                        return '<div class="flex items-center gap-3 min-w-0 max-w-full">' . $imgHtml . '<span class="font-semibold text-slate-800 min-w-0 truncate" title="'.$n.'">' . $n . '</span></div>';
                    })
                    ->addColumn('category_name', function($row) {
                        if (!$row->category) {
                            return '<span class="text-slate-400 text-sm">—</span>';
                        }
                        $cn = e($row->category->name);
                        return '<span class="badge badge-primary max-w-full min-w-0 truncate" title="'.$cn.'">'.$cn.'</span>';
                    })
                    ->addColumn('president_name', function($row) {
                        if (!$row->president) {
                            return '<span class="text-slate-400 text-sm">Atanmadı</span>';
                        }
                        $pn = e($row->president->name);
                        return '<span class="block truncate text-slate-700 text-sm" title="'.$pn.'">'.$pn.'</span>';
                    })
                    ->addColumn('members_count', function($row) {
                        return '<div class="text-center whitespace-nowrap tabular-nums"><span class="font-semibold">' . (int)$row->approved_members_count . '</span></div>';
                    })
                    ->addColumn('status', function($row) {
                        $bgToggle = $row->is_active ? 'bg-green-600' : 'bg-slate-200';
                        $lbl = $row->is_active ? 'Aktif' : 'Pasif';
                        $lblColor = $row->is_active ? 'text-slate-700' : 'text-slate-500';

                        if (!auth()->user()->isAdmin()) {
                            $lblEsc = e($lbl);
                        return '<div class="flex items-center gap-2 justify-center min-w-0"><span class="w-2 h-2 rounded-full shrink-0 '.($row->is_active ? 'bg-green-500' : 'bg-slate-300').'"></span><span class="text-sm font-semibold '.$lblColor.' truncate min-w-0" title="'.$lblEsc.'">'.$lblEsc.'</span></div>';
                        }

                        $lblEsc = e($lbl);
                        return '<div class="flex items-center gap-2 justify-center min-w-0 max-w-full">
                            <label class="relative inline-flex items-center cursor-pointer m-0 shrink-0" onclick="event.preventDefault(); toggleStatus(\'club\', '.$row->id.')">
                                <div class="w-11 h-6 rounded-full relative transition-colors '.$bgToggle.'">
                                    <span class="absolute left-1 top-1 bg-white w-4 h-4 rounded-full transition-transform '.($row->is_active ? 'translate-x-[20px]' : '').'"></span>
                                </div>
                            </label>
                            <span class="text-xs sm:text-sm font-semibold '.$lblColor.' min-w-0 truncate" title="'.$lblEsc.'">'.$lblEsc.'</span>
                        </div>';
                    })
                    ->addColumn('action', function($row) {
                        $baseUrl = url('admin/kulupler');
                        $btn = '<div class="flex items-center justify-center gap-1 sm:gap-2 whitespace-nowrap">';
                        $btn .= '<a href="'.$baseUrl.'/'.$row->id.'/uyeler" class="w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors border border-amber-100" title="Üyeler"><span class="material-symbols-outlined text-[16px]">group</span></a>';
                        $btn .= '<a href="'.$baseUrl.'/'.$row->id.'/form-alanlari" class="w-8 h-8 flex items-center justify-center bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition-colors border border-purple-100" title="Kayıt Formu"><span class="material-symbols-outlined text-[16px]">dynamic_form</span></a>';
                        $btn .= '<button onclick="showKulupDuzenle('.$row->id.')" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-100 transition-colors border border-blue-100" title="Düzenle"><span class="material-symbols-outlined text-[16px]">edit_square</span></button>';
                        
                        if (auth()->user()->isAdmin()) {
                            $btn .= '<button onclick="showDeleteModal('.$row->id.', \''.e(addslashes($row->name)).'\')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors border border-red-100" title="Sil"><span class="material-symbols-outlined text-[16px]">delete</span></button>';
                        }
                        
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->filterColumn('club_info', function($query, $keyword) {
                        $query->where('clubs.name', 'like', "%{$keyword}%");
                    })
                    ->filterColumn('category_name', function($query, $keyword) {
                        $query->where('categories.name', 'like', "%{$keyword}%");
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
            'description'       => 'nullable|string|max:3000',
            'short_description' => 'nullable|string|max:150',
            'category_id'       => 'nullable|exists:categories,id',
            'president_id'      => 'nullable|exists:users,id',
            'is_active'         => 'boolean',
            'logo'              => 'nullable|image|max:10240',
            'cover_image'       => 'nullable|image|max:10240',
            'website_url'       => 'nullable|url|max:255',
            'instagram_url'     => 'nullable|url|max:255',
            'youtube_url'       => 'nullable|url|max:500',
            'twitter_url'       => 'nullable|url|max:500',
            'facebook_url'      => 'nullable|url|max:500',
            'whatsapp_url'      => 'nullable|url|max:500',
            'channel_url'       => 'nullable|url|max:500',
            'mission'           => 'nullable|string|max:300',
            'vision'            => 'nullable|string|max:300',
            'activities'        => 'nullable|string',
            'founder_name'      => 'nullable|string|max:100',
            'established_year'  => 'nullable|string|max:10',
        ]);

        if ($request->hasFile('logo')) {
            $clubSlug = Str::slug($request->name);
            $validated['logo'] = $request->file('logo')->store($clubSlug . '/logos', 'uploads');
        }
        if ($request->hasFile('cover_image')) {
            $clubSlug = Str::slug($request->name);
            $validated['cover_image'] = $request->file('cover_image')->store($clubSlug . '/covers', 'uploads');
        }

        try {
            return DB::transaction(function() use ($validated, $request) {
                \Log::info("Club Store Started", ['name' => $validated['name'], 'president_id' => $validated['president_id'] ?? 'none']);
                
                // Bir kullanıcı sadece bir kulübün başkanı olabilir mantığı
                if (!empty($validated['president_id'])) {
                    Club::where('president_id', $validated['president_id'])->update(['president_id' => null]);
                }

                $club = Club::create($validated);

                // Varsayılan form alanlarını oluştur
                ClubFormField::createDefaultFields($club->id);

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
        if (auth()->user()->isEditor() && (int)$club->id !== (int)auth()->user()->club_id) {
            abort(403, 'Bu kulübü düzenleme yetkiniz yok.');
        }

        // Validation hatası olursa editör için modalı tekrar açmak üzere session'a kaydet
        session()->flash('edit_club_id', $club->id);

        $validated = $request->validate([
            'name'              => 'required|string|max:100|unique:clubs,name,' . $club->id,
            'description'       => 'nullable|string|max:3000',
            'short_description' => 'nullable|string|max:150',
            'category_id'       => 'nullable|exists:categories,id',
            'president_id'      => 'nullable|exists:users,id',
            'is_active'         => 'boolean',
            'logo'              => 'nullable|image|max:10240',
            'cover_image'       => 'nullable|image|max:10240',
            'website_url'       => 'nullable|url|max:255',
            'instagram_url'     => 'nullable|url|max:255',
            'youtube_url'       => 'nullable|url|max:500',
            'twitter_url'       => 'nullable|url|max:500',
            'facebook_url'      => 'nullable|url|max:500',
            'whatsapp_url'      => 'nullable|url|max:500',
            'channel_url'       => 'nullable|url|max:500',
            'mission'           => 'nullable|string|max:300',
            'vision'            => 'nullable|string|max:300',
            'activities'        => 'nullable|string',
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
            $validated['logo'] = $request->file('logo')->store($club->slug . '/logos', 'uploads');
        }
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store($club->slug . '/covers', 'uploads');
        }

        try {
            return DB::transaction(function() use ($validated, $club, $request) {
                $oldPresidentId = $club->getOriginal('president_id');

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
                        $path = $image->store($club->slug . '/gallery', 'uploads');
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
            return back()->withInput()->with('error', 'Kulüp güncellenirken bir hata oluştu: ' . $e->getMessage())->with('edit_club_id', $club->id);
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
        if (auth()->user()->isEditor() && $club->id != auth()->user()->club_id) {
            abort(403, 'Bu kulübün üyelerini görme yetkiniz yok.');
        }

        if (request()->ajax()) {
            $query = ClubMember::with(['user', 'registrationData.formField'])
                ->where('club_members.club_id', $club->id)
                ->orderByRaw("CASE 
                    WHEN user_id = " . ($club->president_id ?? 0) . " THEN 0 
                    WHEN title = 'Başkan' THEN 1 
                    WHEN title = 'Başkan Yardımcısı' THEN 2 
                    WHEN title LIKE '%Başkan Yardımcısı%' THEN 3 
                    WHEN title LIKE '%Başkan%' THEN 4
                    ELSE 5 
                END")
                ->orderBy('title');

            $filter = request('status', 'all');
            if ($filter !== 'all') {
                $query->where('club_members.status', $filter);
            }

            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_info', function ($member) use ($club) {
                    if (!$member->user) return '<span class="text-slate-400 italic text-sm">Silinmiş Kullanıcı</span>';
                    $img = null;
                    if ($member->user->profile_photo) {
                        $img = str_starts_with($member->user->profile_photo, 'http') 
                            ? $member->user->profile_photo 
                            : (file_exists(public_path('uploads/' . $member->user->profile_photo)) 
                                ? asset('uploads/' . $member->user->profile_photo) 
                                : asset('storage/' . $member->user->profile_photo));
                    }
                    $photo = $img 
                        ? '<img src="'.$img.'" class="w-9 h-9 rounded-full object-cover">'
                        : '<span class="material-symbols-outlined text-primary text-[18px]">person</span>';
                    $presidentBadge = ($club->president_id && $member->user_id == $club->president_id)
                        ? '<span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-500 text-white ml-1 shrink-0"><span class="material-symbols-outlined text-[11px]">workspace_premium</span>BAŞKAN</span>'
                        : '';
                    $title = $member->title ? '<p class="text-[11px] font-bold text-primary uppercase tracking-wider mt-0.5 truncate" title="'.e($member->title).'">'.e($member->title).'</p>' : '';
                    return '<div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center shrink-0">'.$photo.'</div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1 min-w-0">
                                <p class="font-semibold text-slate-800 text-sm truncate" title="'.e($member->user->name).'">'.e($member->user->name).'</p>
                                '.$presidentBadge.'
                            </div>
                            '.$title.'
                        </div>
                    </div>';
                })
                ->addColumn('email', fn($m) => '<span class="text-sm text-slate-600 truncate block" title="'.e($m->user->email ?? '-').'">'.e($m->user->email ?? '-').'</span>')
                ->addColumn('form_data', function ($member) {
                    if ($member->registrationData && $member->registrationData->count() > 0) {
                        $json = htmlspecialchars(json_encode(
                            $member->registrationData->map(fn($d) => [
                                'label' => $d->formField->label ?? 'Alan',
                                'value' => $d->value ?? '-',
                            ])
                        ), ENT_QUOTES, 'UTF-8');
                        return '<button onclick=\'showFormData('.$json.', "'.e(addslashes($member->user->name ?? '')).'")\' class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-primary/10 text-primary hover:bg-primary/20 border border-primary/10 transition-colors">
                            <span class="material-symbols-outlined text-[14px]">description</span>
                            Görüntüle ('.$member->registrationData->count().')
                        </button>';
                    }
                    return '<span class="text-xs text-slate-400 italic">Yok</span>';
                })
                ->addColumn('date', fn($m) => '<span class="text-sm text-slate-500">'.$m->created_at->format('d.m.Y H:i').'</span>')
                ->addColumn('status_badge', function ($member) {
                    return match($member->status) {
                        'pending'  => '<span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200"><span class="material-symbols-outlined text-[13px]">schedule</span>Bekliyor</span>',
                        'approved' => '<span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200"><span class="material-symbols-outlined text-[13px]">check_circle</span>Onaylandı</span>',
                        'rejected' => '<span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200"><span class="material-symbols-outlined text-[13px]">cancel</span>Reddedildi</span>',
                        default    => '<span class="text-xs text-slate-400">-</span>',
                    };
                })
                ->addColumn('action', function ($member) use ($club) {
                    $html = '<div class="flex items-center gap-2">';
                    if ($member->status === 'pending') {
                        $html .= '<form action="'.route('admin.kulupler.uyeler.onayla', $member->id).'" method="POST" class="inline">'.csrf_field().'<button type="submit" class="w-8 h-8 flex items-center justify-center bg-green-50 text-green-600 rounded-lg hover:bg-green-100 border border-green-100" title="Onayla"><span class="material-symbols-outlined text-[16px]">check</span></button></form>';
                        $html .= '<form action="'.route('admin.kulupler.uyeler.reddet', $member->id).'" method="POST" class="inline">'.csrf_field().'<button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 border border-red-100" title="Reddet"><span class="material-symbols-outlined text-[16px]">close</span></button></form>';
                    } elseif ($member->status === 'approved') {
                        if (auth()->user()->isAdmin() && (!$club->president_id || $member->user_id != $club->president_id)) {
                            $html .= '<form action="'.route('admin.kulupler.set-president', [$club->id, $member->user_id]).'" method="POST" class="inline" onsubmit="return confirm(\'Başkan yapmak istediğinize emin misiniz?\')">'.csrf_field().'<button type="submit" class="w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 border border-amber-100" title="Başkan Yap"><span class="material-symbols-outlined text-[16px]">workspace_premium</span></button></form>';
                        }
                        if (auth()->user()->isAdmin() || ($club && $club->president_id == auth()->id())) {
                            $isEditor = ($member->user && $member->user->isEditor()) ? 1 : 0;
                            $html .= '<button onclick="showTitleModal('.$member->id.', \''.e(addslashes($member->user->name ?? '')).'\', \''.e(addslashes($member->title ?? '')).'\', '.$isEditor.' )" class="w-8 h-8 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 border border-indigo-100" title="Unvan ve Yetki"><span class="material-symbols-outlined text-[16px]">badge</span></button>';
                        }
                        $html .= '<form action="'.route('admin.kulupler.uyeler.reddet', $member->id).'" method="POST" class="inline">'.csrf_field().'<button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 border border-red-100" title="Üyeliği Kaldır"><span class="material-symbols-outlined text-[16px]">person_remove</span></button></form>';
                    } elseif ($member->status === 'rejected') {
                        $html .= '<form action="'.route('admin.kulupler.uyeler.onayla', $member->id).'" method="POST" class="inline">'.csrf_field().'<button type="submit" class="w-8 h-8 flex items-center justify-center bg-green-50 text-green-600 rounded-lg hover:bg-green-100 border border-green-100" title="Tekrar Onayla"><span class="material-symbols-outlined text-[16px]">undo</span></button></form>';
                    }
                    $html .= '<button onclick="showDeleteMemberModal('.$member->id.', \''.e(addslashes($member->user->name ?? 'Bilinmiyor')).'\' )" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 border border-red-100" title="Sil"><span class="material-symbols-outlined text-[16px]">delete</span></button>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['user_info', 'email', 'form_data', 'date', 'status_badge', 'action'])
                ->make(true);
        }

        $stats = [
            'total'    => ClubMember::where('club_id', $club->id)->count(),
            'pending'  => ClubMember::where('club_id', $club->id)->where('status', 'pending')->count(),
            'approved' => ClubMember::where('club_id', $club->id)->where('status', 'approved')->count(),
            'rejected' => ClubMember::where('club_id', $club->id)->where('status', 'rejected')->count(),
        ];

        return view('admin.kulup-uyeleri', compact('club', 'stats'));
    }

    public function approveMember(ClubMember $member)
    {
        if (auth()->user()->isEditor() && $member->club_id != auth()->user()->club_id) {
            abort(403, 'Bu üyeyi onaylama yetkiniz yok.');
        }

        if ($member->status !== 'approved') {
            $member->update(['status' => 'approved', 'approved_at' => now()]);
        }
        return back()->with('success', 'Üyelik onaylandı.');
    }

    public function rejectMember(ClubMember $member)
    {
        if (auth()->user()->isEditor() && $member->club_id != auth()->user()->club_id) {
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
        if (auth()->user()->isEditor() && $member->club_id != auth()->user()->club_id) {
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
        if (auth()->user()->isEditor()) {
            if ($member->club_id !== auth()->user()->club_id) {
                abort(403, 'Bu üyenin unvanını değiştirme yetkiniz yok.');
            }
            if (!$member->club || $member->club->president_id !== auth()->id()) {
                abort(403, 'Yalnızca kulübün asıl başkanı unvan ve yetki verebilir.');
            }
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'is_editor' => 'nullable'
        ]);
        
        $member->update(['title' => $request->title]);

        if ($member->user) {
            $user = $member->user;
            $wantsEditor = $request->has('is_editor');
            
            // Admin ve Başkan rollerini koru
            $isPresident = ($member->club && $member->club->president_id == $user->id);
            
            if (!$user->isAdmin() && !$isPresident) {
                if ($wantsEditor) {
                    $editorRole = Role::where('name', 'editor')->first();
                    if ($editorRole) {
                        $user->update([
                            'role_id' => $editorRole->id,
                            'club_id' => $member->club_id
                        ]);
                    }
                } else {
                    // Editörlük yetkisi kaldırılıyorsa ve kullanıcı şu an editörse, öğrenci yap
                    if ($user->isEditor()) {
                        $studentRole = Role::where('name', 'student')->first();
                        $user->update([
                            'role_id' => $studentRole->id ?? 3,
                            'club_id' => null
                        ]);
                    }
                }
            }
        }

        return back()->with('success', 'Üye unvanı ve yetkileri başarıyla güncellendi.');
    }

    public function deleteGalleryImage(ClubImage $image)
    {
        if (auth()->user()->isEditor() && $image->club->id !== auth()->user()->club_id) {
            abort(403, 'Bu resmi silme yetkiniz yok.');
        }

        // Dosyayı sil
        if (Storage::disk('uploads')->exists($image->image_path)) {
            Storage::disk('uploads')->delete($image->image_path);
        } elseif (Storage::disk('public')->exists($image->image_path)) {
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
        
        if (auth()->user()->isEditor() && $club->id != auth()->user()->club_id) {
            abort(403, 'Bu kulübe dosya yükleme yetkiniz yok.');
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $path = $file->store($club->slug . '/documents', 'uploads');

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
        if (Storage::disk('uploads')->exists($document->file_path)) {
            Storage::disk('uploads')->delete($document->file_path);
        } elseif (Storage::disk('public')->exists($document->file_path)) {
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

                // Başkanın kulüp üyeleri listesinde de görünmesini sağla
                ClubMember::updateOrCreate(
                    ['club_id' => $club->id, 'user_id' => $user->id],
                    ['status' => 'approved', 'approved_at' => now(), 'title' => 'Başkan']
                );

                \Log::info("President synchronized and added to members list", [
                    'user_id' => $user->id, 
                    'role_id' => $user->role_id, 
                    'club_id' => $user->club_id
                ]);
            }
        }
    }

    // ══════════════════════════════════════════════════════════
    // KULÜP KAYIT FORMU ALANLARI YÖNETİMİ
    // ══════════════════════════════════════════════════════════

    /**
     * Kulübün form alanlarını listele
     */
    public function formFields(Club $club)
    {
        if (auth()->user()->isEditor() && $club->id != auth()->user()->club_id) {
            abort(403, 'Bu kulübün form alanlarını görme yetkiniz yok.');
        }

        $fields = $club->allFormFields()->get();
        return view('admin.kulup-form-alanlari', compact('club', 'fields'));
    }

    /**
     * Yeni form alanı ekle
     */
    public function storeFormField(Request $request, Club $club)
    {
        if (auth()->user()->isEditor() && $club->id != auth()->user()->club_id) {
            abort(403, 'Bu kulübe form alanı ekleme yetkiniz yok.');
        }

        $validated = $request->validate([
            'label'       => 'required|string|max:255',
            'type'        => 'required|in:text,email,tel,textarea,checkbox,select',
            'placeholder' => 'nullable|string|max:255',
            'options'     => 'nullable|string', // virgülle ayrılmış select seçenekleri
            'is_required' => 'boolean',
        ]);

        // Sort order: en sondaki alanın +1'i
        $maxOrder = $club->allFormFields()->max('sort_order') ?? 0;
        $validated['sort_order'] = $maxOrder + 1;
        $validated['club_id'] = $club->id;
        $validated['is_required'] = $request->boolean('is_required');

        // Options'ı JSON array'e çevir
        if (!empty($validated['options'])) {
            $validated['options'] = array_map('trim', explode(',', $validated['options']));
        } else {
            $validated['options'] = null;
        }

        ClubFormField::create($validated);

        return back()->with('success', 'Form alanı başarıyla eklendi.');
    }

    /**
     * Form alanını güncelle
     */
    public function updateFormField(Request $request, ClubFormField $field)
    {
        if (auth()->user()->isEditor() && $field->club_id != auth()->user()->club_id) {
            abort(403, 'Bu form alanını düzenleme yetkiniz yok.');
        }

        $validated = $request->validate([
            'label'       => 'required|string|max:255',
            'type'        => 'required|in:text,email,tel,textarea,checkbox,select',
            'placeholder' => 'nullable|string|max:255',
            'options'     => 'nullable|string',
            'is_required' => 'boolean',
            'is_active'   => 'boolean',
        ]);

        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = $request->boolean('is_active');

        if (!empty($validated['options'])) {
            $validated['options'] = array_map('trim', explode(',', $validated['options']));
        } else {
            $validated['options'] = null;
        }

        $field->update($validated);

        return back()->with('success', 'Form alanı güncellendi.');
    }

    /**
     * Form alanını sil
     */
    public function destroyFormField(ClubFormField $field)
    {
        if (auth()->user()->isEditor() && $field->club_id != auth()->user()->club_id) {
            abort(403, 'Bu form alanını silme yetkiniz yok.');
        }

        $field->delete();
        return back()->with('success', 'Form alanı silindi.');
    }

    /**
     * Form alanlarını yeniden sırala
     */
    public function reorderFormFields(Request $request, Club $club)
    {
        if (auth()->user()->isEditor() && $club->id != auth()->user()->club_id) {
            abort(403, 'Bu form alanlarını sıralama yetkiniz yok.');
        }

        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'integer|exists:club_form_fields,id',
        ]);

        foreach ($request->order as $index => $fieldId) {
            ClubFormField::where('id', $fieldId)
                ->where('club_id', $club->id)
                ->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Varsayılan form alanlarını oluştur
     */
    public function createDefaultFormFields(Club $club)
    {
        if (auth()->user()->isEditor() && $club->id != auth()->user()->club_id) {
            abort(403, 'Bu kulübe varsayılan alanları ekleme yetkiniz yok.');
        }

        ClubFormField::createDefaultFields($club->id);
        return back()->with('success', 'Varsayılan form alanları oluşturuldu.');
    }
}
