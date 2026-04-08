<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $term = $request->input('q');
        
        $query = User::with('role')
            ->whereHas('role', function($q) {
                $q->whereIn('name', ['admin', 'editor', 'student']);
            });

        if (!empty($term)) {
            $query->where(function($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
                  ->orWhere('email', 'LIKE', "%{$term}%")
                  ->orWhereHas('role', function($rq) use ($term) {
                      $rq->where('name', 'LIKE', "%{$term}%")
                        ->orWhere('label', 'LIKE', "%{$term}%");
                  });
            });
        }

        $users = $query->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.label as role_label')
            ->orderByRaw("CASE 
                WHEN roles.name = 'admin' THEN 1 
                WHEN roles.name = 'editor' THEN 2 
                ELSE 3 END ASC")
            ->orderBy('users.name', 'ASC')
            ->paginate(20);

        return response()->json([
            'results' => collect($users->items())->map(function($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->name . " (" . ($user->role_label ?? $user->role->label) . " - " . $user->email . ")"
                ];
            }),
            'pagination' => [
                'more' => $users->hasMorePages()
            ]
        ]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with(['role', 'club'])->select('users.*');

            if ($request->filled('role_id')) {
                $query->where('role_id', $request->role_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user_info', function ($user) {
                    $img = $user->profile_photo ? asset('storage/' . $user->profile_photo) : null;
                    $initials = mb_strtoupper(mb_substr($user->name, 0, 2, 'UTF-8'), 'UTF-8');
                    $imgHtml = $img ? '<img src="'.$img.'" class="w-9 h-9 rounded-full object-cover shrink-0 shadow-sm" alt=""/>' 
                                    : '<div class="w-9 h-9 bg-primary/10 rounded-full flex items-center justify-center shrink-0 text-primary text-xs font-bold shadow-sm">'.$initials.'</div>';
                    
                    return '<div class="flex items-center gap-3">
                                '.$imgHtml.'
                                <div class="flex flex-col">
                                    <span class="font-semibold text-slate-800">'.$user->name.'</span>
                                    <span class="text-[11px] text-slate-500">'.$user->email.'</span>
                                </div>
                            </div>';
                })
                ->addColumn('role_name', function ($user) {
                    return $user->role ? '<span class="px-2 py-0.5 bg-primary/10 text-primary font-bold text-[10px] rounded uppercase">'.$user->role->label.'</span>' : '<span class="text-slate-400 text-sm">—</span>';
                })
                ->addColumn('club_name', function ($user) {
                    return '<span class="text-slate-600 font-medium text-xs">'.($user->club?->name ?? '—').'</span>';
                })
                ->addColumn('date', function ($user) {
                    return '<span class="text-slate-500 text-xs">'.$user->created_at->format('d.m.Y').'</span>';
                })
                ->addColumn('status', function ($user) {
                    if ($user->email_verified_at) {
                        return '<span class="px-2 py-0.5 bg-green-100 text-green-700 font-bold text-[10px] rounded uppercase">Aktif</span>';
                    } else {
                        return '<span class="px-2 py-0.5 bg-amber-100 text-amber-700 font-bold text-[10px] rounded uppercase">Onay Bekliyor</span>';
                    }
                })
                ->addColumn('action', function ($user) {
                    $editBtn = '<button onclick="showKullaniciDuzenle('.$user->id.', \''.addslashes($user->name).'\', \''.$user->email.'\', '.($user->role_id ?? 'null').', '.($user->club_id ?? 'null').')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100 transition-colors" title="Düzenle"><span class="material-symbols-outlined text-[18px]">edit</span></button>';
                    $deleteBtn = '<button type="button" onclick="showDeleteModal('.$user->id.', \''.addslashes($user->name).'\')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition-colors" title="Sil"><span class="material-symbols-outlined text-[18px]">delete</span></button>';
                    
                    return '<div class="flex items-center justify-end gap-1.5">'.$editBtn.$deleteBtn.'</div>';
                })
                ->rawColumns(['user_info', 'role_name', 'club_name', 'date', 'status', 'action'])
                ->make(true);
        }

        $roles = Role::all();
        $clubs = Club::where('is_active', true)->get();

        $stats = [
            'total'   => User::count(),
            'active'  => User::whereNotNull('email_verified_at')->count(),
            'admins'  => User::whereHas('role', fn($q) => $q->where('name', 'admin'))->count(),
            'passive' => User::whereNull('email_verified_at')->count(),
        ];

        return view('admin.kullanicilar', compact('roles', 'clubs', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role_id'  => 'required|exists:roles,id',
            'club_id'  => 'nullable|exists:clubs,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.kullanicilar')->with('success', 'Kullanıcı oluşturuldu.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
            'club_id' => 'nullable|exists:clubs,id',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('admin.kullanicilar')->with('success', 'Kullanıcı güncellendi.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.kullanicilar')->with('success', 'Kullanıcı silindi.');
    }
}
