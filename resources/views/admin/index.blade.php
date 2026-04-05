@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')
@section('data-page', 'dashboard')

@section('content')

<!-- Stats Cards -->
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
            <span class="badge badge-success">+28%</span>
        </div>
        <p class="text-[32px] font-bold font-headline text-slate-800 leading-none" data-count="{{ $stats['total_users'] }}">0</p>
        <p class="text-sm text-slate-500 mt-2 font-medium">Toplam Kullanıcı</p>
    </div>
</div>

<!-- 3 Column Lists -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    <!-- Haberler -->
    <div class="admin-card p-0 flex flex-col h-full overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">article</span>
                <h3 class="font-bold font-headline text-slate-800 text-[16px]">Son Haberler</h3>
            </div>
            <button onclick="showToast('Yeni Haber ekleme formu açılacak', 'success')" class="bg-primary hover:bg-primary-dim text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors shadow-sm">+ Ekle</button>
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
            <button onclick="window.location.href='{{ route('admin.etkinlikler') }}'" class="bg-tertiary hover:bg-[#0494c7] text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors shadow-sm">+ Ekle</button>
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

    <!-- Duyurular -->
    <div class="admin-card p-0 flex flex-col h-full overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-slate-400">campaign</span>
                <h3 class="font-bold font-headline text-slate-800 text-[16px]">Son Duyurular</h3>
            </div>
            <button onclick="showToast('Yeni Duyuru ekleme formu açılacak', 'success')" class="bg-secondary hover:bg-[#b06cf0] text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors shadow-sm">+ Ekle</button>
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

</div>

@endsection
