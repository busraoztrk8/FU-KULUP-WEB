@extends('layouts.app')
@section('title', 'Dashboard - Fırat Üniversitesi')
@section('data-page', 'dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 py-12">

    {{-- Hoşgeldin --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 md:p-8 mb-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center shrink-0">
                @if(auth()->user()->profile_photo)
                    @php
                        $dashPhoto = auth()->user()->profile_photo;
                        $dashPUrl = str_starts_with($dashPhoto, 'http') ? $dashPhoto : (file_exists(public_path('uploads/' . $dashPhoto)) ? asset('uploads/' . $dashPhoto) : asset('storage/' . $dashPhoto));
                    @endphp
                    <img src="{{ $dashPUrl }}"
                        class="w-full h-full object-cover rounded-2xl" alt=""/>
                @else
                    <span class="material-symbols-outlined text-primary text-[28px]">account_circle</span>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-xl md:text-2xl font-bold font-headline text-slate-900">
                    Hoş geldin, {{ auth()->user()->name }} 👋
                </h1>
                <p class="text-slate-500 text-sm mt-1">
                    {{ auth()->user()->role?->label ?? 'Öğrenci' }} ·
                    {{ auth()->user()->email }}
                </p>
            </div>
            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
            <a href="{{ route('admin.index') }}"
                class="shrink-0 flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-primary-dark transition-all active:scale-95 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span>
                Yönetim Paneli
            </a>
            @endif
        </div>
    </div>

    {{-- Hızlı Erişim --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
        <a href="{{ route('etkinlikler') }}"
            class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:border-primary/30 hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-primary text-[24px]">event</span>
            </div>
            <h3 class="font-bold text-slate-800 group-hover:text-primary transition-colors">Etkinlikler</h3>
            <p class="text-sm text-slate-500 mt-1">Yaklaşan etkinlikleri keşfet</p>
        </a>
        <a href="{{ route('kulupler') }}"
            class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:border-primary/30 hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-primary text-[24px]">groups</span>
            </div>
            <h3 class="font-bold text-slate-800 group-hover:text-primary transition-colors">Kulüpler</h3>
            <p class="text-sm text-slate-500 mt-1">Kulüplere göz at ve katıl</p>
        </a>
        <a href="{{ route('profile.edit') }}"
            class="group bg-white rounded-2xl border border-slate-100 shadow-sm p-6 hover:border-primary/30 hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-primary text-[24px]">manage_accounts</span>
            </div>
            <h3 class="font-bold text-slate-800 group-hover:text-primary transition-colors">Profilim</h3>
            <p class="text-sm text-slate-500 mt-1">Hesap bilgilerini düzenle</p>
        </a>
    </div>

    {{-- Kayıtlı Etkinlikler --}}
    @php
        $myRegistrations = \App\Models\EventRegistration::with('event.category')
            ->where('user_id', auth()->id())
            ->where('status', 'registered')
            ->latest()
            ->take(5)
            ->get();
    @endphp

    @if($myRegistrations->count() > 0)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 md:p-8 mb-8">
        <h2 class="font-bold font-headline text-slate-800 text-lg mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">how_to_reg</span>
            Kayıtlı Olduğum Etkinlikler
        </h2>
        <div class="space-y-3">
            @foreach($myRegistrations as $reg)
            <a href="{{ route('etkinlik.detay', $reg->event->slug) }}"
                class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 transition-colors group">
                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-primary text-[18px]">event</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-800 group-hover:text-primary transition-colors truncate">
                        {{ $reg->event->title }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ $reg->event->start_time->format('d M Y, H:i') }}
                        @if($reg->event->location) · {{ $reg->event->location }} @endif
                    </p>
                </div>
                <span class="badge badge-success text-[10px] shrink-0">Kayıtlı</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Üye Olduğum Kulüpler --}}
    @php
        $myClubs = \App\Models\ClubMember::with('club.category')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();
    @endphp

    @if($myClubs->count() > 0)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 md:p-8 mb-8">
        <h2 class="font-bold font-headline text-slate-800 text-lg mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">groups</span>
            Kulüp Başvurularım
        </h2>
        <div class="space-y-3">
            @foreach($myClubs as $member)
            <a href="{{ route('kulup.detay', $member->club->slug) }}"
                class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 transition-colors group">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shrink-0">
                    @if($member->club->logo)
                        @php
                            $dashLogo = $member->club->logo;
                            $dashLUrl = str_starts_with($dashLogo, 'http') ? $dashLogo : (file_exists(public_path('uploads/' . $dashLogo)) ? asset('uploads/' . $dashLogo) : asset('storage/' . $dashLogo));
                        @endphp
                        <img src="{{ $dashLUrl }}" class="w-full h-full object-cover rounded-xl" alt=""/>
                    @else
                        <span class="material-symbols-outlined text-white text-[18px]">groups</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-800 group-hover:text-primary transition-colors truncate">
                        {{ $member->club->name }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ $member->club->category?->name ?? 'Kategori yok' }}
                    </p>
                </div>
                @php
                    $statusMap = ['pending' => ['Onay Bekleniyor', 'badge-warning'], 'approved' => ['Onaylandı', 'badge-success'], 'rejected' => ['Reddedildi', 'badge-danger']];
                    [$label, $class] = $statusMap[$member->status] ?? ['Bilinmiyor', ''];
                @endphp
                <span class="badge {{ $class }} text-[10px] shrink-0">{{ $label }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Çıkış --}}
    <div class="flex justify-end">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-2 text-sm font-semibold text-red-400 hover:text-red-600 transition-colors px-4 py-2 rounded-xl hover:bg-red-50">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                Çıkış Yap
            </button>
        </form>
    </div>

</div>
@endsection
