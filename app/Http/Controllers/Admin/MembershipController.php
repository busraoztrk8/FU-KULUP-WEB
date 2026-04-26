<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClubMember;
use App\Models\User;
use App\Models\Club;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ClubMember::with(['club', 'user', 'registrationData.formField'])->latest();
            
            // Eğer kullanıcı admin değilse (editörse), sadece kendi kulübünün üyelerini görsün
            if (!auth()->user()->isAdmin()) {
                $query->where('club_id', auth()->user()->club_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('student_info', function ($member) {
                    if (!$member->user) return '<span class="text-slate-400 italic">Silinmiş Kullanıcı</span>';
                    
                    $initials = mb_strtoupper(mb_substr($member->user->name, 0, 2, 'UTF-8'), 'UTF-8');
                    return '<div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 bg-primary/10 rounded-full flex items-center justify-center shrink-0 text-primary text-xs font-bold shadow-sm">'.$initials.'</div>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-semibold text-slate-800 truncate" title="'.$member->user->name.'">'.$member->user->name.'</span>
                                    <span class="text-[11px] text-slate-500 truncate" title="'.$member->user->email.'">'.$member->user->email.'</span>
                                </div>
                            </div>';
                })
                ->addColumn('club_info', function ($member) {
                    return '<span class="text-sm font-semibold text-slate-600">' . ($member->club->name ?? 'Belirsiz') . '</span>';
                })
                ->addColumn('date', function ($member) {
                    return '<span class="text-xs text-slate-500">' . ($member->created_at ? $member->created_at->format('d.m.Y H:i') : '-') . '</span>';
                })
                ->addColumn('status_label', function ($member) {
                    if ($member->status == 'pending') {
                        return '<span class="px-2.5 py-1 bg-amber-100 text-amber-700 font-bold text-[10px] rounded-lg uppercase">Beklemede</span>';
                    } elseif ($member->status == 'approved') {
                        return '<span class="px-2.5 py-1 bg-green-100 text-green-700 font-bold text-[10px] rounded-lg uppercase">Onaylandı</span>';
                    } else {
                        return '<span class="px-2.5 py-1 bg-red-100 text-red-700 font-bold text-[10px] rounded-lg uppercase">Reddedildi</span>';
                    }
                })
                ->addColumn('action', function ($member) {
                    $html = '<div class="flex items-center justify-end gap-2">';
                    
                    // Form Verisi Butonu
                    if ($member->registrationData && $member->registrationData->count() > 0) {
                        $registrationJson = htmlspecialchars(json_encode(
                            $member->registrationData->map(function($data) {
                                return [
                                    'label' => $data->formField ? $data->formField->label : 'Bilinmeyen Alan',
                                    'value' => $data->value
                                ];
                            })
                        ), ENT_QUOTES, 'UTF-8');
                        
                        $html .= '<button onclick="showFormDataModal('.$member->id.', \''.e(addslashes($member->user->name ?? 'Bilinmiyor')).'\', \''.$registrationJson.'\')" class="w-8 h-8 flex items-center justify-center bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition-colors border border-purple-100" title="Form Verisini Gör">
                                    <span class="material-symbols-outlined text-[16px]">description</span>
                                  </button>';
                    }

                    if ($member->status == 'pending') {
                        $html .= '<form action="' . route('admin.members.approve', $member) . '" method="POST">
                                    ' . csrf_field() . '
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors border border-green-100" title="Onayla">
                                        <span class="material-symbols-outlined text-[16px]">check</span>
                                    </button>
                                  </form>';
                        $html .= '<form action="' . route('admin.members.reject', $member) . '" method="POST">
                                    ' . csrf_field() . '
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors border border-red-100" title="Reddet">
                                        <span class="material-symbols-outlined text-[16px]">close</span>
                                    </button>
                                  </form>';
                    }
                    $html .= '<form action="' . route('admin.members.destroy', $member) . '" method="POST" onsubmit="return confirm(\'Bu kaydı silmek istediğinize emin misiniz?\')">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors border border-red-100" title="Sil">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                              </form>';
                    $html .= '</div>';
                    return $html;
                })
                ->rawColumns(['student_info', 'club_info', 'date', 'status_label', 'action'])
                ->make(true);
        }

        $users = User::orderBy('name')->get();
        $clubs = auth()->user()->isAdmin() ? Club::orderBy('name')->get() : Club::where('id', auth()->user()->club_id)->get();

        return view('admin.uyelikler', compact('users', 'clubs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'club_id' => 'required|exists:clubs,id',
            'status'  => 'required|in:approved,pending,rejected'
        ]);

        // Mükerrer kontrolü
        $exists = ClubMember::where('user_id', $validated['user_id'])
            ->where('club_id', $validated['club_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bu üyelik kaydı zaten mevcut.');
        }

        ClubMember::create($validated + ['approved_at' => ($validated['status'] === 'approved' ? now() : null)]);

        return back()->with('success', 'Üyelik kaydı başarıyla oluşturuldu.');
    }

    public function approve(ClubMember $member)
    {
        if (auth()->user()->isEditor() && $member->club_id != auth()->user()->club_id) {
            abort(403, 'Yetkiniz yok.');
        }

        if ($member->status !== 'approved') {
            $member->update([
                'status' => 'approved',
                'approved_at' => now()
            ]);
        }
        return back()->with('success', 'Üyelik onaylandı.');
    }

    public function reject(ClubMember $member)
    {
        if (auth()->user()->isEditor() && $member->club_id != auth()->user()->club_id) {
            abort(403, 'Yetkiniz yok.');
        }

        if ($member->status === 'approved') {
            $member->club->decrement('member_count');
        }
        $member->update([
            'status' => 'rejected'
        ]);
        return back()->with('success', 'Üyelik reddedildi.');
    }

    public function destroy(ClubMember $member)
    {
        if (auth()->user()->isEditor() && $member->club_id != auth()->user()->club_id) {
            abort(403, 'Yetkiniz yok.');
        }

        if ($member->status === 'approved') {
            $member->club->decrement('member_count');
        }
        $member->delete();
        return back()->with('success', 'Kayıt silindi.');
    }
}

