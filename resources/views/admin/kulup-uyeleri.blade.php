@extends('layouts.admin')
@section('title', e($club->name) . ' - Üyelik Yönetimi')
@section('page_title', e($club->name) . ' - Üyelik Yönetimi')
@section('data-page', 'members')

@section('content')

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">error</span>{{ session('error') }}
</div>
@endif

{{-- Breadcrumb --}}
<div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
    <a href="{{ route('admin.kulupler') }}" class="hover:text-primary transition-colors font-medium">Kulüpler</a>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <span class="text-slate-800 font-bold">{{ $club->name }}</span>
    <span class="material-symbols-outlined text-[14px]">chevron_right</span>
    <span class="text-primary font-bold">Üyelik Yönetimi</span>
</div>

{{-- Kulüp Bilgi Kartı --}}
<div class="admin-card mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 shadow-sm">
    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
        @if($club->logo)
            <img src="{{ asset('storage/' . $club->logo) }}" class="w-14 h-14 rounded-xl object-cover" alt="">
        @else
            <span class="material-symbols-outlined text-primary text-[28px]">groups</span>
        @endif
    </div>
    <div class="flex-1">
        <h2 class="text-xl font-bold font-headline text-slate-800">{{ $club->name }}</h2>
        <p class="text-sm text-slate-500">{{ $club->short_description ?? 'Kulüp üyelik başvurularını buradan yönetebilirsiniz.' }}</p>
    </div>
    <a href="{{ route('admin.kulupler') }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-all flex items-center gap-2 active:scale-95">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span>Kulüplere Dön
    </a>
</div>

{{-- İstatistik Kartları --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
    <div class="admin-card flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-11 h-11 bg-slate-100 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-slate-600">people</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['total'] }}">0</p>
            <p class="text-xs text-slate-500 font-medium">Toplam</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-amber-600">schedule</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-amber-600" data-count="{{ $stats['pending'] }}">0</p>
            <p class="text-xs text-slate-500 font-medium">Bekleyen</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-11 h-11 bg-green-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-green-600" data-count="{{ $stats['approved'] }}">0</p>
            <p class="text-xs text-slate-500 font-medium">Onaylandı</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-3 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-red-600">cancel</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-red-600" data-count="{{ $stats['rejected'] }}">0</p>
            <p class="text-xs text-slate-500 font-medium">Reddedildi</p>
        </div>
    </div>
</div>

{{-- Filtre ve Aksiyonlar --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('admin.kulupler.uyeler', $club->id) }}?status=all"
           class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $filter === 'all' ? 'bg-primary text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Tümü <span class="ml-1 opacity-70">({{ $stats['total'] }})</span>
        </a>
        <a href="{{ route('admin.kulupler.uyeler', $club->id) }}?status=pending"
           class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $filter === 'pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            <span class="material-symbols-outlined text-[14px] align-middle mr-1">schedule</span>Bekleyen <span class="ml-1 opacity-70">({{ $stats['pending'] }})</span>
        </a>
        <a href="{{ route('admin.kulupler.uyeler', $club->id) }}?status=approved"
           class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $filter === 'approved' ? 'bg-green-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            <span class="material-symbols-outlined text-[14px] align-middle mr-1">check_circle</span>Onaylı <span class="ml-1 opacity-70">({{ $stats['approved'] }})</span>
        </a>
        <a href="{{ route('admin.kulupler.uyeler', $club->id) }}?status=rejected"
           class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ $filter === 'rejected' ? 'bg-red-600 text-white shadow-sm' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            <span class="material-symbols-outlined text-[14px] align-middle mr-1">cancel</span>Reddedildi <span class="ml-1 opacity-70">({{ $stats['rejected'] }})</span>
        </a>
    </div>
</div>

{{-- Üye Tablosu --}}
<div class="admin-card p-0 overflow-hidden shadow-sm">
    @if($members->count() > 0)
    <div class="overflow-x-auto w-full">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="px-6 py-4 text-left text-slate-500 font-bold uppercase text-xs tracking-wider">Kullanıcı</th>
                    <th class="px-6 py-4 text-left text-slate-500 font-bold uppercase text-xs tracking-wider">E-posta</th>
                    <th class="px-6 py-4 text-left text-slate-500 font-bold uppercase text-xs tracking-wider">Başvuru Mesajı</th>
                    <th class="px-6 py-4 text-left text-slate-500 font-bold uppercase text-xs tracking-wider">Tarih</th>
                    <th class="px-6 py-4 text-left text-slate-500 font-bold uppercase text-xs tracking-wider">Durum</th>
                    <th class="px-6 py-4 text-left text-slate-500 font-bold uppercase text-xs tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($members as $member)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    {{-- Kullanıcı --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                                @if($member->user && $member->user->profile_photo)
                                    <img src="{{ asset('storage/' . $member->user->profile_photo) }}" class="w-9 h-9 rounded-full object-cover" alt="">
                                @else
                                    <span class="material-symbols-outlined text-primary text-[18px]">person</span>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800 text-sm flex items-center gap-2">
                                    {{ $member->user->name ?? 'Bilinmiyor' }}
                                    @if($club->president_id && $member->user_id == $club->president_id)
                                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-500 text-white shadow-sm" title="Kulüp Başkanı">
                                            <span class="material-symbols-outlined text-[12px]">workspace_premium</span> BAŞKAN
                                        </span>
                                    @endif
                                </p>
                                @if($member->title)
                                    <p class="text-[11px] font-bold text-primary uppercase tracking-wider mt-0.5">{{ $member->title }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    {{-- E-posta --}}
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $member->user->email ?? '-' }}</td>
                    {{-- Başvuru Mesajı --}}
                    <td class="px-6 py-4">
                        @if($member->message)
                            <p class="text-sm text-slate-600 max-w-[200px] truncate" title="{{ $member->message }}">{{ $member->message }}</p>
                        @else
                            <span class="text-sm text-slate-400 italic">Mesaj yok</span>
                        @endif
                    </td>
                    {{-- Tarih --}}
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-600">
                            <p>{{ $member->created_at->format('d.m.Y') }}</p>
                            <p class="text-xs text-slate-400">{{ $member->created_at->format('H:i') }}</p>
                        </div>
                    </td>
                    {{-- Durum --}}
                    <td class="px-6 py-4">
                        @if($member->status === 'pending')
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="material-symbols-outlined text-[13px]">schedule</span>Bekliyor
                            </span>
                        @elseif($member->status === 'approved')
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200">
                                <span class="material-symbols-outlined text-[13px]">check_circle</span>Onaylandı
                            </span>
                        @elseif($member->status === 'rejected')
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                <span class="material-symbols-outlined text-[13px]">cancel</span>Reddedildi
                            </span>
                        @endif
                    </td>
                    {{-- İşlemler --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            @if($member->status === 'pending')
                                {{-- Onayla --}}
                                <form action="{{ route('admin.kulupler.uyeler.onayla', $member->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors border border-green-100" title="Onayla">
                                        <span class="material-symbols-outlined text-[16px]">check</span>
                                    </button>
                                </form>
                                {{-- Reddet --}}
                                <form action="{{ route('admin.kulupler.uyeler.reddet', $member->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors border border-red-100" title="Reddet">
                                        <span class="material-symbols-outlined text-[16px]">close</span>
                                    </button>
                                </form>
                            @elseif($member->status === 'approved')
                                {{-- Başkan Yap --}}
                                @if(auth()->user()->isAdmin() && (!$club->president_id || $member->user_id != $club->president_id))
                                    <form action="{{ route('admin.kulupler.set-president', [$club->id, $member->user_id]) }}" method="POST" class="inline" onsubmit="return confirm('{{ $member->user->name }} adlı kişiyi bu kulübün BAŞKANI yapmak istediğinize emin misiniz? Mevcut başkan değişecektir.')">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors border border-amber-100" title="Kulüp Başkanı Yap">
                                            <span class="material-symbols-outlined text-[16px]">workspace_premium</span>
                                        </button>
                                    </form>
                                @endif

                                {{-- Unvan Belirle --}}
                                <button type="button" onclick="showTitleModal({{ $member->id }}, '{{ e(addslashes($member->user->name)) }}', '{{ e(addslashes($member->title ?? '')) }}')" class="w-8 h-8 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors border border-indigo-100" title="Unvan Belirle">
                                    <span class="material-symbols-outlined text-[16px]">badge</span>
                                </button>
                                
                                {{-- Reddet (onaylı üyeyi geri al) --}}
                                <form action="{{ route('admin.kulupler.uyeler.reddet', $member->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors border border-red-100" title="Üyeliği Kaldır">
                                        <span class="material-symbols-outlined text-[16px]">person_remove</span>
                                    </button>
                                </form>
                            @elseif($member->status === 'rejected')
                                {{-- Tekrar Onayla --}}
                                <form action="{{ route('admin.kulupler.uyeler.onayla', $member->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors border border-green-100" title="Tekrar Onayla">
                                        <span class="material-symbols-outlined text-[16px]">undo</span>
                                    </button>
                                </form>
                            @endif
                            {{-- Sil --}}
                            <button onclick="showDeleteMemberModal({{ $member->id }}, '{{ e(addslashes($member->user->name ?? 'Bilinmiyor')) }}')" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors border border-red-100" title="Kaydı Sil">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($members->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $members->links() }}
    </div>
    @endif

    @else
    {{-- Boş durum --}}
    <div class="flex flex-col items-center justify-center py-16">
        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
            <span class="material-symbols-outlined text-slate-400 text-[36px]">group_off</span>
        </div>
        <h3 class="text-lg font-bold font-headline text-slate-700 mb-2">Üyelik başvurusu bulunamadı</h3>
        <p class="text-sm text-slate-500 mb-4">
            @if($filter === 'pending')
                Bekleyen başvuru yok.
            @elseif($filter === 'approved')
                Onaylanmış üye yok.
            @elseif($filter === 'rejected')
                Reddedilmiş başvuru yok.
            @else
                Bu kulübe henüz üyelik başvurusu yapılmamış.
            @endif
        </p>
        <a href="{{ route('admin.kulupler') }}" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all flex items-center gap-2 active:scale-95">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>Kulüplere Dön
        </a>
    </div>
    @endif
</div>

{{-- Silme Onay Modalı --}}
<div id="delete-member-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideDeleteMemberModal()"></div>
    <div class="relative bg-white rounded-2xl p-8 max-w-sm w-full mx-4 shadow-2xl">
        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-red-500 text-[28px]">warning</span>
        </div>
        <h3 class="text-lg font-bold font-headline text-center text-slate-800 mb-2">Üyelik Kaydını Sil</h3>
        <p class="text-sm text-slate-500 text-center mb-6">"<span id="delete-member-name"></span>" adlı kullanıcının üyelik kaydı kalıcı olarak silinecektir.</p>
        <form id="delete-member-form" method="POST" class="flex gap-3">
            @csrf @method('DELETE')
            <button type="button" onclick="hideDeleteMemberModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
            <button type="submit" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-all active:scale-95">Sil</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showDeleteMemberModal(id, name) {
    document.getElementById('delete-member-name').textContent = name;
    document.getElementById('delete-member-form').action = "/admin/kulup-uyelik/" + id;
    document.getElementById('delete-member-modal').classList.remove('hidden');
}
function hideDeleteMemberModal() {
    document.getElementById('delete-member-modal').classList.add('hidden');
}
</script>
@endpush
