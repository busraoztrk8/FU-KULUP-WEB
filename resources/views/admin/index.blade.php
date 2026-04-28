@extends('layouts.admin')

@section('title', 'Yönetim Paneli')
@section('page_title', 'Genel Bakış')
@section('data-page', 'dashboard')

@section('content')

<!-- Stats Cards -->
@if(auth()->user()->isEditor())
<div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="md:col-span-4 bg-white border border-slate-100 rounded-2xl p-6 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-[32px]">waving_hand</span>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Hoş Geldiniz, {{ auth()->user()->full_name }}</h2>
                <p class="text-slate-500 text-sm">Kulübünüzün tüm yönetim araçlarına buradan hızlıca erişebilirsiniz.</p>
            </div>
        </div>
        <a href="{{ route('admin.kulupler') }}" class="px-5 py-2.5 bg-slate-50 border border-slate-200 text-slate-600 rounded-xl font-bold flex items-center gap-2 transition-all hover:bg-white active:scale-95">
            <span class="material-symbols-outlined text-[20px]">settings</span>
            Kulüp Ayarları
        </a>
    </div>

    {{-- Quick Actions --}}
    <button onclick="window.location.href='{{ route('admin.haberler') }}?action=add'" class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all group text-left">
        <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl mb-3 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-all">
            <span class="material-symbols-outlined">add_circle</span>
        </div>
        <h4 class="font-bold text-slate-800 text-sm">Haber Ekle</h4>
        <p class="text-xs text-slate-400 mt-1">Yeni bir gelişmeyi duyur</p>
    </button>

    <button onclick="window.location.href='{{ route('admin.etkinlikler') }}?action=add'" class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all group text-left">
        <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-xl mb-3 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all">
            <span class="material-symbols-outlined">event_available</span>
        </div>
        <h4 class="font-bold text-slate-800 text-sm">Etkinlik Oluştur</h4>
        <p class="text-xs text-slate-400 mt-1">Takvime yeni etkinlik ekle</p>
    </button>

    <button onclick="window.location.href='{{ route('admin.duyurular') }}?action=add'" class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all group text-left">
        <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-xl mb-3 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-all">
            <span class="material-symbols-outlined">campaign</span>
        </div>
        <h4 class="font-bold text-slate-800 text-sm">Duyuru Yayınla</h4>
        <p class="text-xs text-slate-400 mt-1">Önemli bilgilendirme yap</p>
    </button>

    <button onclick="window.location.href='{{ route('admin.members.index') }}'" class="bg-white border border-slate-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all group text-left">
        <div class="w-10 h-10 bg-purple-50 text-purple-500 rounded-xl mb-3 flex items-center justify-center group-hover:bg-purple-500 group-hover:text-white transition-all">
            <span class="material-symbols-outlined">group_add</span>
        </div>
        <h4 class="font-bold text-slate-800 text-sm">Üyeleri Yönet</h4>
        <p class="text-xs text-slate-400 mt-1">Üyelik başvurularını incele</p>
    </button>
</div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="admin-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-[28px]">article</span>
            </div>
            <span class="badge badge-success">+12%</span>
        </div>
        <p class="text-[32px] font-bold font-headline text-slate-800 leading-none" data-count="{{ $stats['total_news'] }}">0</p>
        <p class="text-sm text-slate-500 mt-2 font-medium">Toplam Haber</p>
    </div>
    <div class="admin-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-tertiary/10 rounded-2xl flex items-center justify-center">
                <span class="material-symbols-outlined text-tertiary text-[28px]">event</span>
            </div>
            <span class="badge badge-info">Bu Ay</span>
        </div>
        <p class="text-[32px] font-bold font-headline text-slate-800 leading-none" data-count="{{ $stats['total_events'] }}">0</p>
        <p class="text-sm text-slate-500 mt-2 font-medium">Toplam Etkinlik</p>
    </div>
    <div class="admin-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-500 text-[28px]">campaign</span>
            </div>
            <span class="badge badge-warning">Aktif</span>
        </div>
        <p class="text-[32px] font-bold font-headline text-slate-800 leading-none" data-count="{{ $stats['total_announcements'] }}">0</p>
        <p class="text-sm text-slate-500 mt-2 font-medium">Toplam Duyuru</p>
    </div>
    <div class="admin-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center">
                <span class="material-symbols-outlined text-secondary text-[28px]">people</span>
            </div>
            <span class="badge badge-success">{{ auth()->user()->isAdmin() ? '+28%' : 'Onaylı' }}</span>
        </div>
        <p class="text-[32px] font-bold font-headline text-slate-800 leading-none" data-count="{{ $stats['total_users'] }}">0</p>
        <p class="text-sm text-slate-500 mt-2 font-medium">{{ auth()->user()->isAdmin() ? 'Toplam Kullanıcı' : 'Kulüp Üyeleri' }}</p>
    </div>
</div>

<!-- 3 Column Lists -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 items-stretch">

    <!-- Haberler -->
    <div class="admin-card p-0 flex flex-col h-full overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">article</span>
                <h3 class="font-bold font-headline text-slate-800 text-[16px]">Son Haberler</h3>
            </div>
            <a href="{{ route('admin.haberler') }}" class="bg-primary hover:bg-primary-dim text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors shadow-sm">+ Yönet</a>
        </div>
        <div class="p-6 space-y-4 overflow-y-auto flex-1">
            @forelse($recentNews as $news)
            <div class="flex items-start justify-between border-b border-slate-50 pb-4 last:border-0 last:pb-0 hover:bg-slate-50 cursor-pointer p-2 -mx-2 rounded transition-colors" onclick="window.location.href='{{ route('admin.haberler') }}'">
                <div>
                    <h4 class="text-[14px] font-bold text-slate-800 mb-1">{{ Str::limit($news->title, 40) }}</h4>
                    <span class="text-[12px] font-medium text-slate-500">{{ $news->created_at->format('d.m.Y') }}</span>
                </div>
                @if($news->is_published)
                <span class="badge badge-success shrink-0 ml-3">Yayında</span>
                @else
                <span class="badge badge-warning shrink-0 ml-3">Taslak</span>
                @endif
            </div>
            @empty
            <p class="text-sm text-slate-400 text-center py-4">Henüz haber yok.</p>
            @endforelse
        </div>
    </div>

    <!-- Etkinlikler -->
    <div class="admin-card p-0 flex flex-col h-full overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">event</span>
                <h3 class="font-bold font-headline text-slate-800 text-[16px]">Son Etkinlikler</h3>
            </div>
            <a href="{{ route('admin.etkinlikler') }}" class="bg-primary hover:bg-primary-dim text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors shadow-sm">+ Yönet</a>
        </div>
        <div class="p-6 space-y-4 overflow-y-auto flex-1">
            @forelse($recentEvents as $event)
            <div class="flex items-start justify-between border-b border-slate-50 pb-4 last:border-0 last:pb-0 hover:bg-slate-50 cursor-pointer p-2 -mx-2 rounded transition-colors">
                <div>
                    <h4 class="text-[14px] font-bold text-slate-800 mb-1">{{ Str::limit($event->title, 40) }}</h4>
                    <p class="text-[12px] text-slate-500 leading-relaxed flex items-start">
                        <span class="material-symbols-outlined text-[14px] text-primary/80 mr-1 shrink-0 mt-[2px]">location_on</span>
                        <span>{{ $event->location ?? 'Konum belirtilmedi' }} — {{ $event->start_time->format('d.m.Y') }}</span>
                    </p>
                </div>
            </div>
            @empty
            <p class="text-sm text-slate-400 text-center py-4">Henüz etkinlik yok.</p>
            @endforelse
        </div>
    </div>

    @if(auth()->user()->isEditor())
    <!-- Son Üyelik Başvuruları (Editor Only) -->
    <div class="admin-card p-0 flex flex-col h-full overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">person_add</span>
                <h3 class="font-bold font-headline text-slate-800 text-[16px]">Son Üyelik Başvuruları</h3>
            </div>
            <a href="{{ route('admin.members.index') }}" class="bg-primary hover:bg-primary-dim text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors shadow-sm">Tümünü Gör</a>
        </div>
        <div class="p-6 space-y-4 overflow-y-auto flex-1">
            @forelse($recentMembers as $member)
            <div class="flex items-center justify-between border-b border-slate-50 pb-4 last:border-0 last:pb-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-500 text-sm">
                        {{ strtoupper(substr($member->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="text-[14px] font-bold text-slate-800">{{ $member->user->name }}</h4>
                        <span class="text-[11px] text-slate-400">{{ $member->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @php
                    $mStatus = match($member->status) {
                        'pending' => ['Onay Bekliyor', 'bg-amber-100 text-amber-700'],
                        'approved' => ['Onaylandı', 'bg-green-100 text-green-700'],
                        'rejected' => ['Reddedildi', 'bg-red-100 text-red-700'],
                        default => [$member->status, 'bg-slate-100 text-slate-700']
                    };
                @endphp
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $mStatus[1] }}">{{ $mStatus[0] }}</span>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-8 text-slate-400">
                <span class="material-symbols-outlined text-[32px] mb-2">person_off</span>
                <p class="text-sm">Henüz başvuru yok.</p>
            </div>
            @endforelse
        </div>
    </div>
    @else
    <!-- Duyurular (Admin default) -->
    <div class="admin-card p-0 flex flex-col h-full overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">campaign</span>
                <h3 class="font-bold font-headline text-slate-800 text-[16px]">Son Duyurular</h3>
            </div>
            <a href="{{ route('admin.duyurular') }}" class="bg-primary hover:bg-primary-dim text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors shadow-sm">+ Yönet</a>
        </div>
        <div class="p-6 space-y-4 overflow-y-auto flex-1">
            @forelse($recentAnnouncements as $announcement)
            <div class="flex items-start justify-between border-b border-slate-50 pb-4 last:border-0 last:pb-0 hover:bg-slate-50 cursor-pointer p-2 -mx-2 rounded transition-colors" onclick="window.location.href='{{ route('admin.duyurular') }}'">
                <div>
                    <h4 class="text-[14px] font-bold text-slate-800 mb-1">{{ Str::limit($announcement->title, 40) }}</h4>
                    <span class="text-[12px] font-medium text-slate-500">{{ $announcement->created_at->format('d.m.Y') }}</span>
                </div>
                @if($announcement->is_published)
                <span class="badge badge-success shrink-0 ml-3">Yayında</span>
                @else
                <span class="badge badge-warning shrink-0 ml-3">Taslak</span>
                @endif
            </div>
            @empty
            <p class="text-sm text-slate-400 text-center py-4">Henüz duyuru yok.</p>
            @endforelse
        </div>
    </div>
    @endif

</div>

@endsection
