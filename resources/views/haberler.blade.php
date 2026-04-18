@extends('layouts.app')
@section('title', 'Haberler - Fırat Üniversitesi')
@section('data-page', 'news')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6">
    <!-- Hero — etkinlikler / duyurular ile aynı banner boyutu -->
    <section class="@include('partials.site-hero-dimensions') bg-gradient-to-br from-primary via-primary-dark to-slate-900">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 md:px-10 lg:px-16 text-center">
            <span class="inline-block bg-white/15 backdrop-blur-sm text-white px-4 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-widest mb-4 border border-white/20">
                <span class="material-symbols-outlined text-[14px] align-middle mr-1">newspaper</span>
                Güncel Gelişmeler
            </span>
            <h1 class="text-lg sm:text-3xl md:text-5xl lg:text-7xl font-bold font-headline text-white mb-2 md:mb-4 tracking-tight uppercase">
                Haberler
            </h1>
            <p class="text-white/70 text-xs sm:text-base md:text-xl max-w-2xl mx-auto leading-relaxed">
                Üniversitemizin ve kulüplerimizin en güncel haberlerini takip edin.
            </p>
        </div>
    </section>

    <!-- News Grid -->
    <section class="py-12 md:py-20">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 md:mb-12 gap-4">
            <div>
                <h2 class="text-xl md:text-2xl font-bold font-headline text-on-surface">Son Haberler</h2>
                <p class="text-on-surface-variant text-sm mt-1">Toplam {{ $news->total() }} haber bulundu</p>
            </div>
        </div>

        @if($news->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach($news as $item)
            <a href="{{ route('haber.detay', $item->slug) }}"
                class="group bg-white rounded-2xl overflow-hidden border border-black/5 hover:border-primary/20 hover:shadow-xl transition-all duration-300 equal-height-card">
                <div class="relative h-48 md:h-52 overflow-hidden">
                    @if($item->image_path)
                        @php
                            $nPath = $item->image_path;
                            $nUrl = str_starts_with($nPath, 'http') ? $nPath : (file_exists(public_path('uploads/' . $nPath)) ? asset('uploads/' . $nPath) : asset('storage/' . $nPath));
                        @endphp
                        <img src="{{ $nUrl }}" alt="{{ $item->title }}"
                            class="aspect-stable-img group-hover:scale-110 transition-transform duration-500"/>
                    @else
                        <div class="aspect-stable-img bg-gradient-to-br from-primary/10 to-primary/30 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary/40 text-[56px]">newspaper</span>
                        </div>
                    @endif
                    @if($item->club)
                    <div class="absolute top-3 left-3 bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-lg">
                        {{ $item->club->name }}
                    </div>
                    @endif
                </div>
                <div class="p-5 md:p-6 card-content">
                    <div class="flex items-center gap-2 text-xs text-slate-400 font-bold uppercase tracking-wider mb-3">
                        <span class="material-symbols-outlined text-[14px] text-primary">schedule</span>
                        {{ $item->published_at ? $item->published_at->format('d M Y') : $item->created_at->format('d M Y') }}
                    </div>
                    <h3 class="font-bold text-slate-800 group-hover:text-primary transition-colors text-lg leading-snug mb-2 line-clamp-2">
                        {{ $item->title }}
                    </h3>
                    <p class="text-sm text-slate-500 leading-relaxed line-clamp-3">
                        {{ Str::limit(strip_tags($item->content), 120) }}
                    </p>
                    <div class="mt-auto pt-4 flex items-center text-primary font-bold text-sm gap-1 group-hover:gap-2 transition-all">
                        Devamını Oku
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            {{ $news->links('partials.custom-pagination') }}
        </div>
        @else
        <div class="text-center py-20">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-slate-300 text-[40px]">newspaper</span>
            </div>
            <h3 class="text-xl font-bold text-slate-400 mb-2">Henüz Haber Yok</h3>
            <p class="text-slate-400 text-sm">Yayınlanan haberler burada görüntülenecektir.</p>
        </div>
        @endif
    </section>
</div>

@endsection
