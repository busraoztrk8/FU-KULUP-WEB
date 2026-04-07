@extends('layouts.app')
@section('title', $club->name . ' - Fırat Üniversitesi')
@section('data-page', 'club-detail')

@section('content')
<div class="pb-12 md:pb-20 px-4 sm:px-6 md:px-8 max-w-7xl mx-auto">

    {{-- Hero --}}
    <section class="relative mb-8 md:mb-12 rounded-2xl md:rounded-3xl overflow-hidden h-[280px] sm:h-[350px] md:h-[420px] shadow-2xl">
        @if($club->cover_image)
            <img src="{{ asset('storage/' . $club->cover_image) }}" alt="{{ $club->name }}"
                class="absolute inset-0 w-full h-full object-cover"/>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-dark"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>

        <div class="absolute bottom-0 left-0 p-5 sm:p-8 md:p-12 w-full flex flex-col sm:flex-row items-start sm:items-end gap-4 md:gap-6">
            {{-- Logo --}}
            <div class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-2xl bg-white p-2 shadow-2xl shrink-0">
                @if($club->logo)
                    <img src="{{ asset('storage/' . $club->logo) }}" alt="{{ $club->name }}"
                        class="w-full h-full object-cover rounded-xl"/>
                @else
                    <div class="w-full h-full bg-primary rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-3xl md:text-4xl">groups</span>
                    </div>
                @endif
            </div>
            <div>
                <h1 class="font-headline text-2xl sm:text-3xl md:text-5xl font-extrabold text-white tracking-tight leading-tight mb-3">
                    {{ $club->name }}
                </h1>
                <div class="flex flex-wrap gap-2">
                    @if($club->category)
                    <span class="px-3 py-1 bg-primary text-white rounded-full text-[10px] font-bold uppercase tracking-widest">
                        {{ $club->category->name }}
                    </span>
                    @endif
                    <span class="px-3 py-1 bg-white/20 backdrop-blur text-white rounded-full text-[10px] font-bold">
                        {{ $club->member_count }} Üye
                    </span>
                    @if($club->is_active)
                    <span class="px-3 py-1 bg-green-500/80 text-white rounded-full text-[10px] font-bold">Aktif</span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- İçerik --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 md:gap-12">

        {{-- Sol: Ana İçerik --}}
        <div class="lg:col-span-8 space-y-10 md:space-y-14">

            {{-- Hakkında --}}
            <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-slate-100">
                <h2 class="font-headline text-2xl font-bold mb-5 flex items-center gap-3 text-on-surface">
                    <span class="w-1.5 h-8 bg-primary rounded-full"></span>
                    Hakkımızda
                </h2>
                @if($club->description)
                    <div class="text-on-surface-variant leading-relaxed text-base space-y-3">
                        {!! nl2br(e($club->description)) !!}
                    </div>
                @else
                    <p class="text-slate-400 italic">Henüz açıklama eklenmemiş.</p>
                @endif
            </div>

            {{-- Yaklaşan Etkinlikler --}}
            @if($club->events->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-headline text-2xl font-bold text-on-surface">Kulüp Etkinlikleri</h2>
                    <a href="{{ route('etkinlikler') }}" class="text-primary text-sm font-bold hover:underline">Tümünü Gör</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach($club->events as $event)
                    <a href="{{ route('etkinlik.detay', $event->slug) }}"
                        class="group relative rounded-2xl overflow-hidden aspect-video shadow-md hover:shadow-xl transition-all">
                        @if($event->image)
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}"
                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/60 to-primary-dark"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-5">
                            <span class="bg-primary text-white text-[10px] font-bold px-2 py-0.5 rounded mb-2 inline-block uppercase">
                                {{ $event->start_time->format('d M') }}
                            </span>
                            <h3 class="text-base font-bold text-white leading-snug line-clamp-2">{{ $event->title }}</h3>
                            @if($event->location)
                            <p class="text-white/70 text-xs mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[12px]">location_on</span>
                                {{ $event->location }}
                            </p>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Sağ: Sidebar --}}
        <aside class="lg:col-span-4 space-y-6">

            {{-- Kulüp Bilgileri --}}
            <div class="bg-white rounded-2xl p-6 md:p-8 border border-slate-100 shadow-sm">
                <h3 class="font-headline text-xl font-bold mb-6 text-on-surface">Kulüp Bilgileri</h3>
                <div class="space-y-4 mb-6">
                    @if($club->president)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                        <span class="text-slate-500 flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-primary text-lg">person_search</span>
                            Başkan
                        </span>
                        <span class="font-bold text-slate-800 text-sm">{{ $club->president->name }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                        <span class="text-slate-500 flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-primary text-lg">group</span>
                            Üye Sayısı
                        </span>
                        <span class="font-bold text-slate-800 text-sm">{{ number_format($club->member_count) }}</span>
                    </div>
                    @if($club->category)
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                        <span class="text-slate-500 flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-primary text-lg">category</span>
                            Kategori
                        </span>
                        <span class="font-bold text-slate-800 text-sm">{{ $club->category->name }}</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                        <span class="text-slate-500 flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-primary text-lg">event</span>
                            Etkinlik Sayısı
                        </span>
                        <span class="font-bold text-slate-800 text-sm">{{ $club->event_count }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5">
                        <span class="text-slate-500 flex items-center gap-2 text-sm">
                            <span class="material-symbols-outlined text-primary text-lg">calendar_today</span>
                            Kuruluş
                        </span>
                        <span class="font-bold text-slate-800 text-sm">{{ $club->created_at->format('Y') }}</span>
                    </div>
                </div>

                @auth
                @php
                    $membership = \App\Models\ClubMember::where('club_id', $club->id)
                        ->where('user_id', auth()->id())
                        ->first();
                @endphp

                @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>{{ session('success') }}
                </div>
                @endif
                @if(session('info'))
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-700 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">info</span>{{ session('info') }}
                </div>
                @endif
                @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">error</span>{{ session('error') }}
                </div>
                @endif

                @if(!$membership)
                    <form action="{{ route('kulup.kayit', $club) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full py-4 bg-primary text-white rounded-xl font-bold text-base hover:bg-primary-dark active:scale-95 transition-all shadow-xl shadow-primary/20 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">person_add</span>
                            Kulübe Katıl
                        </button>
                    </form>
                @elseif($membership->status === 'pending')
                    <div class="w-full py-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl font-bold text-sm text-center flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">hourglass_empty</span>
                        Başvurunuz İnceleniyor
                    </div>
                    <form action="{{ route('kulup.ayril', $club) }}" method="POST" class="mt-2">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full py-2.5 border border-slate-200 text-slate-500 rounded-xl text-sm font-medium hover:bg-slate-50 transition-all">
                            Başvuruyu İptal Et
                        </button>
                    </form>
                @elseif($membership->status === 'approved')
                    <div class="w-full py-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-bold text-sm text-center flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        Kulüp Üyesisiniz
                    </div>
                    <form action="{{ route('kulup.ayril', $club) }}" method="POST" class="mt-2">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Kulüpten ayrılmak istediğinize emin misiniz?')"
                            class="w-full py-2.5 border border-red-200 text-red-500 rounded-xl text-sm font-medium hover:bg-red-50 transition-all">
                            Kulüpten Ayrıl
                        </button>
                    </form>
                @elseif($membership->status === 'rejected')
                    <div class="w-full py-4 bg-red-50 border border-red-200 text-red-600 rounded-xl font-bold text-sm text-center flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">cancel</span>
                        Başvurunuz Reddedildi
                    </div>
                @endif

                @else
                <a href="{{ route('login') }}"
                    class="w-full py-4 bg-primary text-white rounded-xl font-bold text-base hover:bg-primary-dark active:scale-95 transition-all shadow-xl shadow-primary/20 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">login</span>
                    Giriş Yap ve Katıl
                </a>
                @endauth
            </div>

            {{-- Kısa Açıklama --}}
            @if($club->short_description)
            <div class="bg-primary/5 rounded-2xl p-6 border border-primary/10">
                <p class="text-sm text-slate-600 italic leading-relaxed text-center">
                    "{{ $club->short_description }}"
                </p>
            </div>
            @endif

            {{-- Paylaş --}}
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 text-center">Paylaş</p>
                <div class="flex justify-center gap-3">
                    <a href="https://wa.me/?text={{ urlencode($club->name . ' - ' . request()->url()) }}"
                        target="_blank"
                        class="w-10 h-10 rounded-xl bg-white flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">chat</span>
                    </a>
                    <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); alert('Link kopyalandı!')"
                        class="w-10 h-10 rounded-xl bg-white flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">link</span>
                    </button>
                </div>
            </div>

        </aside>
    </div>
</div>
@endsection
