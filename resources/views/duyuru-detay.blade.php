@extends('layouts.app')
@section('title', e($duyuru->title) . ' - Duyurular - Fırat Üniversitesi')
@section('data-page', 'announcement-detail')

@section('content')

{{-- Hero --}}
<section class="relative min-h-[380px] sm:min-h-[450px] md:h-[550px] w-full overflow-hidden flex flex-col justify-end">
    @if($duyuru->image_path)
        @php
            $dPath = $duyuru->image_path;
            $dUrl = str_starts_with($dPath, 'http') ? $dPath : (file_exists(public_path('uploads/' . $dPath)) ? asset('uploads/' . $dPath) : asset('storage/' . $dPath));
        @endphp
        <img src="{{ $dUrl }}" alt="{{ $duyuru->title }}" class="absolute inset-0 w-full h-full object-cover"/>
    @else
        <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-primary to-primary-dark"></div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
    <div class="relative z-10 w-full p-5 sm:p-8 md:p-14 max-w-7xl mx-auto">
        <div class="flex flex-wrap items-center gap-3 mb-2 md:mb-4">
            <a href="{{ route('duyurular') }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 backdrop-blur-md text-white font-bold text-[10px] uppercase tracking-widest border border-white/20 hover:bg-white/25 transition-all">
                <span class="material-symbols-outlined text-[14px]">arrow_back</span>
                Tüm Duyurular
            </a>
            @if($duyuru->club)
                <span class="inline-block px-2.5 py-1 rounded-full bg-primary text-white font-bold text-[10px] uppercase tracking-widest shadow-lg">
                    {{ $duyuru->club->name }}
                </span>
            @endif
        </div>
        <h1 class="text-xl sm:text-3xl md:text-5xl font-headline font-extrabold text-white tracking-tight mb-2 md:mb-6 leading-tight">
            {{ $duyuru->title }}
        </h1>
        <div class="flex flex-wrap items-center gap-4 md:gap-8 text-white/90 text-sm md:text-base pb-4 border-b border-white/10">
            <div class="flex items-center gap-1.5 md:gap-2 text-[11px] md:text-base">
                <span class="material-symbols-outlined text-primary text-sm md:text-lg">calendar_today</span>
                {{ $duyuru->published_at ? $duyuru->published_at->format('d M Y') : $duyuru->created_at->format('d M Y') }}
            </div>
            <div class="flex items-center gap-1.5 md:gap-2 text-[11px] md:text-base">
                <span class="material-symbols-outlined text-primary text-sm md:text-lg">schedule</span>
                {{ $duyuru->published_at ? $duyuru->published_at->format('H:i') : $duyuru->created_at->format('H:i') }}
            </div>
        </div>
    </div>
</section>

{{-- Content --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-14 items-start">

        {{-- Sol: İçerik --}}
        <div class="lg:col-span-2 space-y-10">
            <div>
                <h2 class="text-lg md:text-2xl font-headline font-bold text-on-surface flex items-center mb-4 md:mb-8">
                    <span class="w-1.5 h-6 md:h-8 bg-primary rounded-full mr-3 md:mr-4"></span>
                    Duyuru İçeriği
                </h2>
                <div class="text-on-surface-variant leading-relaxed text-base space-y-4">
                    {!! nl2br(e($duyuru->content)) !!}
                </div>
            </div>

            {{-- Kulüp Bilgisi --}}
            @if($duyuru->club)
            <div class="bg-slate-50 rounded-xl md:rounded-2xl p-4 md:p-6 border border-slate-100 flex flex-col sm:flex-row items-start sm:items-center gap-4 md:gap-5">
                <div class="w-12 h-12 md:w-16 md:h-16 rounded-xl bg-primary flex items-center justify-center shrink-0 shadow-sm border border-black/5">
                    @if($duyuru->club->logo)
                        @php
                            $cLogo = $duyuru->club->logo;
                            $cLogoUrl = str_starts_with($cLogo, 'http') ? $cLogo : (file_exists(public_path('uploads/' . $cLogo)) ? asset('uploads/' . $cLogo) : asset('storage/' . $cLogo));
                        @endphp
                        <img src="{{ $cLogoUrl }}" class="w-full h-full object-cover rounded-xl" alt="{{ $duyuru->club->name }}"/>
                    @else
                        <span class="material-symbols-outlined text-white text-[24px] md:text-[28px]">groups</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] md:text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Yayınlayan Kulüp</p>
                    <h3 class="text-sm md:text-lg font-bold text-slate-800 truncate">{{ $duyuru->club->name }}</h3>
                    @if($duyuru->club->short_description)
                        <p class="text-xs md:text-sm text-slate-500 mt-1 line-clamp-1">{{ $duyuru->club->short_description }}</p>
                    @endif
                </div>
                <a href="{{ route('kulup.detay', $duyuru->club->slug) }}"
                    class="shrink-0 w-full sm:w-auto text-center px-4 md:px-5 py-2 md:py-2.5 bg-primary text-white rounded-xl font-bold text-[11px] md:text-sm hover:bg-primary-dark transition-all active:scale-95">
                    Kulübü Gör
                </a>
            </div>
            @endif
        </div>

        {{-- Sağ: Sidebar --}}
        <aside class="sticky top-24 space-y-6">
            {{-- Detay Kartı --}}
            <div class="bg-white p-4 md:p-8 rounded-2xl border border-black/5 shadow-xl shadow-primary/5 relative overflow-hidden">
                <h3 class="text-lg md:text-xl font-headline font-bold text-on-surface mb-4 md:mb-6">Duyuru Detayları</h3>
                <div class="space-y-4 md:space-y-5">
                    <div class="flex items-start gap-3 md:gap-4">
                        <div class="p-2 md:p-2.5 bg-primary/5 rounded-xl text-primary shrink-0">
                            <span class="material-symbols-outlined text-sm md:text-base">calendar_today</span>
                        </div>
                        <div>
                            <p class="text-[8px] md:text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Yayın Tarihi</p>
                            <p class="font-bold text-slate-800 text-xs md:text-base">{{ $duyuru->published_at ? $duyuru->published_at->format('d M Y') : $duyuru->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 md:gap-4">
                        <div class="p-2 md:p-2.5 bg-primary/5 rounded-xl text-primary shrink-0">
                            <span class="material-symbols-outlined text-sm md:text-base">schedule</span>
                        </div>
                        <div>
                            <p class="text-[8px] md:text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Yayın Saati</p>
                            <p class="font-bold text-slate-800 text-xs md:text-base">
                                {{ $duyuru->published_at ? $duyuru->published_at->format('H:i') : $duyuru->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                    @if($duyuru->club)
                    <div class="flex items-start gap-3 md:gap-4">
                        <div class="p-2 md:p-2.5 bg-primary/5 rounded-xl text-primary shrink-0">
                            <span class="material-symbols-outlined text-sm md:text-base">groups</span>
                        </div>
                        <div>
                            <p class="text-[8px] md:text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">İlgili Kulüp</p>
                            <p class="font-bold text-slate-800 text-xs md:text-base leading-snug">{{ $duyuru->club->name }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-primary/5 rounded-full blur-2xl"></div>
            </div>

            {{-- Paylaş --}}
            <div class="bg-slate-50 rounded-xl md:rounded-2xl p-4 md:p-6 border border-slate-100">
                <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 md:mb-4 text-center">Paylaş</p>
                <div class="flex justify-center gap-3">
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($duyuru->title) }}&url={{ urlencode(request()->url()) }}"
                        target="_blank"
                        class="w-10 h-10 rounded-xl bg-white flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">share</span>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($duyuru->title . ' ' . request()->url()) }}"
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

            {{-- Diğer Duyurular --}}
            @if($relatedAnnouncements->count() > 0)
            <div class="bg-white p-4 md:p-6 rounded-2xl border border-black/5 shadow-lg">
                <h3 class="text-sm md:text-base font-headline font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">campaign</span>
                    Son Duyurular
                </h3>
                <div class="space-y-4">
                    @foreach($relatedAnnouncements as $related)
                    <a href="{{ route('duyuru.detay', $related->slug) }}" class="group flex gap-3 items-start">
                        <div class="w-16 h-12 rounded-lg bg-slate-100 overflow-hidden shrink-0 border border-black/5">
                            @if($related->image_path)
                                @php
                                    $relPath = $related->image_path;
                                    $relUrl = str_starts_with($relPath, 'http') ? $relPath : (file_exists(public_path('uploads/' . $relPath)) ? asset('uploads/' . $relPath) : asset('storage/' . $relPath));
                                @endphp
                                <img src="{{ $relUrl }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt=""/>
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary/30 text-[16px]">campaign</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[12px] md:text-sm font-bold text-slate-700 group-hover:text-primary transition-colors line-clamp-2 leading-tight">
                                {{ $related->title }}
                            </h4>
                            <p class="text-[10px] text-slate-400 mt-1">
                                {{ $related->published_at ? $related->published_at->format('d M Y') : $related->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </aside>
    </div>
</section>

{{-- Benzer Duyurular --}}
@if($relatedAnnouncements->count() > 0)
<section class="bg-slate-50 py-10 md:py-20 border-t border-black/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="flex justify-between items-end mb-4 md:mb-8">
            <h2 class="text-lg md:text-3xl font-headline font-bold text-slate-900">
                Diğer Duyurular
            </h2>
            <a href="{{ route('duyurular') }}" class="text-primary font-bold text-[11px] md:text-sm hover:underline">Tümünü Gör</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($relatedAnnouncements as $s)
            <a href="{{ route('duyuru.detay', $s->slug) }}"
                class="group bg-white rounded-2xl overflow-hidden border border-black/5 hover:border-primary/20 hover:shadow-xl transition-all duration-300">
                <div class="relative h-44 overflow-hidden">
                    @if($s->image_path)
                        @php
                            $sImgPath = $s->image_path;
                            $sImgUrl = str_starts_with($sImgPath, 'http') ? $sImgPath : (file_exists(public_path('uploads/' . $sImgPath)) ? asset('uploads/' . $sImgPath) : asset('storage/' . $sImgPath));
                        @endphp
                        <img src="{{ $sImgUrl }}" alt="{{ $s->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"/>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary/10 to-primary/30 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-[48px]">campaign</span>
                        </div>
                    @endif
                </div>
                <div class="p-5">
                    <p class="text-xs text-primary font-bold uppercase tracking-wider mb-2">
                        {{ $s->published_at ? $s->published_at->format('d M Y') : $s->created_at->format('d M Y') }}
                    </p>
                    <h4 class="font-bold text-slate-800 group-hover:text-primary transition-colors line-clamp-2 leading-tight">
                        {{ $s->title }}
                    </h4>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
