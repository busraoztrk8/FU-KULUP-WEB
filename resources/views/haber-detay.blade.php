@extends('layouts.app')
@section('title', $haber->title . ' - Haberler - Fırat Üniversitesi')
@section('data-page', 'news-detail')

@section('content')

{{-- Hero --}}
<section class="relative h-[350px] sm:h-[450px] md:h-[550px] w-full overflow-hidden">
    @if($haber->image_path)
        <img src="{{ str_starts_with($haber->image_path, 'http') ? $haber->image_path : asset('storage/' . $haber->image_path) }}" alt="{{ $haber->title }}" class="w-full h-full object-cover"/>
    @else
        <div class="w-full h-full bg-gradient-to-br from-primary to-primary-dark"></div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
    <div class="absolute bottom-0 w-full p-5 sm:p-8 md:p-14 max-w-7xl mx-auto left-1/2 -translate-x-1/2">
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <a href="{{ route('haberler') }}" class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-white/15 backdrop-blur-sm text-white font-bold text-[10px] uppercase tracking-widest border border-white/20 hover:bg-white/25 transition-all">
                <span class="material-symbols-outlined text-[14px]">arrow_back</span>
                Haberler
            </a>
            @if($haber->club)
            <span class="inline-block px-3 py-1 rounded-full bg-primary text-white font-bold text-[10px] uppercase tracking-widest shadow-lg">
                {{ $haber->club->name }}
            </span>
            @endif
        </div>
        <h1 class="text-2xl sm:text-3xl md:text-5xl font-headline font-extrabold text-white tracking-tight mb-4 leading-tight">
            {{ $haber->title }}
        </h1>
        <div class="flex flex-wrap items-center gap-4 md:gap-8 text-white/90 text-sm md:text-base pb-4 border-b border-white/10">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-lg">calendar_today</span>
                {{ $haber->published_at ? $haber->published_at->format('d M Y') : $haber->created_at->format('d M Y') }}
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-lg">schedule</span>
                {{ $haber->published_at ? $haber->published_at->format('H:i') : $haber->created_at->format('H:i') }}
            </div>
        </div>
    </div>
</section>

{{-- Content --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-14 items-start">

        {{-- Sol: İçerik --}}
        <div class="lg:col-span-2 space-y-8">
            <div>
                <h2 class="text-2xl font-headline font-bold text-on-surface flex items-center mb-5">
                    <span class="w-1.5 h-8 bg-primary rounded-full mr-4"></span>
                    Haber İçeriği
                </h2>
                <div class="prose prose-lg max-w-none text-on-surface-variant leading-relaxed">
                    {!! nl2br(e($haber->content)) !!}
                </div>
            </div>

            {{-- Kulüp Bilgisi --}}
            @if($haber->club)
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 flex flex-col sm:flex-row items-start sm:items-center gap-5">
                <div class="w-16 h-16 rounded-xl bg-primary flex items-center justify-center shrink-0">
                    @if($haber->club->logo)
                        <img src="{{ str_starts_with($haber->club->logo, 'http') ? $haber->club->logo : asset('storage/' . $haber->club->logo) }}" class="w-full h-full object-cover rounded-xl" alt="{{ $haber->club->name }}"/>
                    @else
                        <span class="material-symbols-outlined text-white text-[28px]">groups</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Yayınlayan Kulüp</p>
                    <h3 class="text-lg font-bold text-slate-800 truncate">{{ $haber->club->name }}</h3>
                </div>
                <a href="{{ route('kulup.detay', $haber->club->slug) }}"
                    class="shrink-0 px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-dark transition-all active:scale-95">
                    Kulübü Gör
                </a>
            </div>
            @endif
        </div>

        {{-- Sağ: Sidebar --}}
        <aside class="sticky top-24 space-y-6">
            {{-- Paylaş --}}
            <div class="bg-white p-6 rounded-2xl border border-black/5 shadow-lg">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 text-center">Paylaş</p>
                <div class="flex justify-center gap-3">
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($haber->title) }}&url={{ urlencode(request()->url()) }}"
                        target="_blank"
                        class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">share</span>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($haber->title . ' ' . request()->url()) }}"
                        target="_blank"
                        class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">chat</span>
                    </a>
                    <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); alert('Link kopyalandı!')"
                        class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">link</span>
                    </button>
                </div>
            </div>

            {{-- Son Haberler --}}
            @if($relatedNews->count() > 0)
            <div class="bg-white p-6 rounded-2xl border border-black/5 shadow-lg">
                <h3 class="font-headline font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[18px]">newspaper</span>
                    Diğer Haberler
                </h3>
                <div class="space-y-4">
                    @foreach($relatedNews as $related)
                    <a href="{{ route('haber.detay', $related->slug) }}" class="group flex gap-3 items-start">
                        <div class="w-16 h-12 rounded-lg bg-slate-100 overflow-hidden shrink-0">
                            @if($related->image_path)
                                <img src="{{ str_starts_with($related->image_path, 'http') ? $related->image_path : asset('storage/' . $related->image_path) }}" class="w-full h-full object-cover" alt=""/>
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-slate-300 text-[16px]">newspaper</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold text-slate-700 group-hover:text-primary transition-colors line-clamp-2 leading-snug">
                                {{ $related->title }}
                            </h4>
                            <p class="text-[11px] text-slate-400 mt-1">
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

{{-- Benzer Haberler --}}
@if($relatedNews->count() > 0)
<section class="bg-slate-50 py-14 md:py-20 border-t border-black/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="flex justify-between items-end mb-8">
            <h2 class="text-2xl md:text-3xl font-headline font-bold text-on-surface">
                Diğer <span class="text-gradient">Haberler</span>
            </h2>
            <a href="{{ route('haberler') }}" class="text-primary font-bold text-sm hover:underline">Tümünü Gör</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($relatedNews as $s)
            <a href="{{ route('haber.detay', $s->slug) }}"
                class="group bg-white rounded-2xl overflow-hidden border border-black/5 hover:border-primary/20 hover:shadow-xl transition-all duration-300">
                <div class="relative h-44 overflow-hidden">
                    @if($s->image_path)
                        <img src="{{ str_starts_with($s->image_path, 'http') ? $s->image_path : asset('storage/' . $s->image_path) }}" alt="{{ $s->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"/>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary/20 to-primary/40 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-[48px]">newspaper</span>
                        </div>
                    @endif
                </div>
                <div class="p-5">
                    <p class="text-xs text-primary font-bold uppercase tracking-wider mb-2">
                        {{ $s->published_at ? $s->published_at->format('d M Y') : $s->created_at->format('d M Y') }}
                    </p>
                    <h4 class="font-bold text-slate-800 group-hover:text-primary transition-colors line-clamp-2 leading-snug">
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
