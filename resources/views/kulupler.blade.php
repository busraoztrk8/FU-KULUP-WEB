@extends('layouts.app')
@section('title', 'Kulüpler - Fırat Üniversitesi')
@section('data-page', 'clubs')

@section('content')
<div class="px-4 sm:px-6 md:px-12 max-w-7xl mx-auto pb-12">

    {{-- Hero: İlk aktif kulüp --}}
    @php $featured = $clubs->first(); @endphp
    @if($featured)
    <section class="mb-4 md:mb-16">
        <div class="relative overflow-hidden rounded-2xl md:rounded-3xl min-h-[280px] md:h-[400px] flex items-center group shadow-2xl">
            <div class="absolute inset-0 z-0">
                @if($featured->cover_image)
                    @php
                        $featCover = $featured->cover_image;
                        $featUrl = str_starts_with($featCover, 'http') ? $featCover : (file_exists(public_path('uploads/' . $featCover)) ? asset('uploads/' . $featCover) : asset('storage/' . $featCover));
                    @endphp
                    <img src="{{ $featUrl }}" alt="{{ $featured->name }}"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"/>
                @else
                    <div class="w-full h-full bg-gradient-to-br from-primary to-primary-dark"></div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-r from-primary/90 via-primary/60 to-transparent"></div>
            </div>
            <div class="relative z-10 px-6 sm:px-10 md:px-16 max-w-2xl text-white py-8 md:py-0">
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 mb-4 backdrop-blur-md">
                    <span class="material-symbols-outlined text-sm mr-1.5" style="font-variation-settings:'FILL' 1;">star</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider">Öne Çıkan Kulüp</span>
                </div>
                <h1 class="text-lg sm:text-3xl md:text-5xl font-bold font-headline leading-tight mb-2 tracking-tight">
                    {{ $featured->name }}
                </h1>
                @if($featured->short_description)
                <p class="text-white/80 text-sm md:text-lg mb-6 line-clamp-2">{{ $featured->short_description }}</p>
                @endif
                <a href="{{ route('kulup.detay', $featured->slug) }}"
                    class="inline-flex items-center gap-2 bg-white text-primary px-4 py-2 md:px-6 md:py-3 rounded-full font-bold hover:bg-slate-100 transition-all shadow-lg active:scale-95 text-[11px] md:text-base">
                    Kulübü Görüntüle
                    <span class="material-symbols-outlined text-xs md:text-base">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- Arama & Filtre --}}
    <section class="mb-6 md:mb-12">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 md:gap-6">
            <div class="relative flex-1 max-w-xl">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400 text-[20px] md:text-[24px]">search</span>
                <input id="club-search" type="text" placeholder="Kulüp ara..."
                    class="w-full bg-white border border-black/5 rounded-xl md:rounded-2xl py-2 md:py-3.5 pl-10 md:pl-12 pr-4 focus:ring-2 focus:ring-primary/20 focus:border-primary text-xs md:text-base text-slate-900 placeholder:text-slate-400 transition-all shadow-sm"/>
            </div>
            <div class="flex flex-wrap gap-2">
                <button data-filter="all"
                    class="px-3 py-1 md:px-4 md:py-2 rounded-full bg-primary text-white font-bold text-[10px] md:text-sm transition-all shadow-lg shadow-primary/20 active">
                    Hepsi
                </button>
                @foreach($categories as $cat)
                <button data-filter="{{ $cat->slug }}"
                    class="px-3 py-1 md:px-4 md:py-2 rounded-full bg-white hover:bg-primary hover:text-white text-slate-600 font-bold text-[10px] md:text-sm transition-all border border-black/5 shadow-sm">
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Kulüp Grid --}}
    @if($clubs->count() > 0)
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8" id="clubs-grid">
        @foreach($clubs as $club)
        <div data-category="{{ $club->category?->slug ?? 'diger' }}" data-club-name="{{ $club->name }}"
            class="burgundy-card group rounded-2xl md:rounded-3xl overflow-hidden flex flex-col h-full shadow-lg hover:shadow-2xl transition-all duration-500 border border-white/10">
            <div class="h-44 md:h-48 overflow-hidden relative">
                @if($club->cover_image)
                    @php
                        $cCov = $club->cover_image;
                        $cCovUrl = str_starts_with($cCov, 'http') ? $cCov : (file_exists(public_path('uploads/' . $cCov)) ? asset('uploads/' . $cCov) : asset('storage/' . $cCov));
                    @endphp
                    <img src="{{ $cCovUrl }}" alt="{{ $club->name }}"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"/>
                @elseif($club->logo)
                    @php
                        $clogo = $club->logo;
                        $clogoUrl = str_starts_with($clogo, 'http') ? $clogo : (file_exists(public_path('uploads/' . $clogo)) ? asset('uploads/' . $clogo) : asset('storage/' . $clogo));
                    @endphp
                    <img src="{{ $clogoUrl }}" alt="{{ $club->name }}"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"/>
                @else
                    <div class="w-full h-full bg-primary-dark flex items-center justify-center">
                        <span class="material-symbols-outlined text-white/30 text-[64px]">groups</span>
                    </div>
                @endif
                @if($club->category)
                <div class="absolute top-3 left-3 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">
                    {{ $club->category->name }}
                </div>
                @endif
            </div>
            <div class="p-4 md:p-8 flex flex-col flex-1">
                <div class="flex justify-between items-start mb-2 md:mb-3">
                    <h3 class="text-sm md:text-2xl font-bold font-headline text-white group-hover:text-white/90 transition-colors leading-snug">
                        {{ $club->name }}
                    </h3>
                    <div class="flex items-center text-white/70 text-xs font-bold shrink-0 ml-2">
                        <span class="material-symbols-outlined text-sm mr-1">group</span>
                        {{ number_format($club->member_count) }} Üye
                    </div>
                </div>
                @if($club->short_description)
                <p class="text-white/80 text-xs md:text-sm mb-4 md:mb-6 leading-relaxed line-clamp-3">{{ $club->short_description }}</p>
                @else
                <p class="text-white/50 text-xs md:text-sm mb-4 md:mb-6 italic">Açıklama eklenmemiş.</p>
                @endif
                <a href="{{ route('kulup.detay', $club->slug) }}"
                    class="mt-auto w-full py-2.5 md:py-3.5 rounded-xl md:rounded-2xl bg-white text-primary font-bold hover:bg-slate-100 transition-all flex justify-center items-center active:scale-95 shadow-lg text-xs md:text-sm">
                    Kulübü Görüntüle
                </a>
            </div>
        </div>
        @endforeach
    </section>
    @else
    <div class="text-center py-20">
        <span class="material-symbols-outlined text-slate-300 text-[64px] block mb-4">groups</span>
        <p class="text-slate-400 font-medium">Henüz aktif kulüp bulunmuyor.</p>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
// Filtre butonları
document.querySelectorAll('[data-filter]').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var filter = this.dataset.filter;
        document.querySelectorAll('[data-filter]').forEach(function(b) {
            b.classList.remove('bg-primary', 'text-white', 'shadow-lg');
            b.classList.add('bg-white', 'text-slate-600');
        });
        this.classList.add('bg-primary', 'text-white', 'shadow-lg');
        this.classList.remove('bg-white', 'text-slate-600');

        document.querySelectorAll('[data-category]').forEach(function(card) {
            card.style.display = (filter === 'all' || card.dataset.category === filter) ? '' : 'none';
        });
    });
});

// Arama
document.getElementById('club-search').addEventListener('input', function() {
    var q = this.value.toLowerCase().trim();
    document.querySelectorAll('[data-club-name]').forEach(function(card) {
        card.style.display = card.dataset.clubName.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endpush
