@extends('layouts.app')

@section('title', 'Tüm Etkinlikler - Fırat Üniversitesi')
@section('data-page', 'tum-etkinlikler')
@section('page-title', 'Tüm Etkinlikler')

@section('content')
    <!-- Hero Section -->
    <section class="bg-surface-container py-12 md:py-20 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute left-0 bottom-0 w-48 h-48 bg-primary/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold font-headline text-on-background mb-4">Tüm Etkinlikler</h1>
            <p class="text-on-surface-variant text-base md:text-xl max-w-2xl mx-auto font-body">Üniversitemizdeki tüm yaklaşan ve geçmiş etkinlikleri takip edin.</p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <span class="bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-bold">
                    <span class="material-symbols-outlined text-sm align-middle mr-1">event</span>
                    Toplam {{ $totalEvents }} Etkinlik
                </span>
                @if($events->lastPage() > 1)
                <span class="bg-slate-100 text-slate-600 px-4 py-2 rounded-full text-sm font-bold">
                    Sayfa {{ $events->currentPage() }} / {{ $events->lastPage() }}
                </span>
                @endif
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 md:py-16">
        <div id="all-events-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @forelse($events as $event)
            <a href="{{ route('etkinlik.detay', ['slug' => $event->slug]) }}"
                class="group bg-primary rounded-[1.5rem] md:rounded-[2.5rem] overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full border border-white/5 cursor-pointer">
                <div class="relative h-36 md:h-64 w-full overflow-hidden shrink-0">
                    @if($event->image)
                        @php
                            $etkImg = $event->image;
                            $etkUrl = str_starts_with($etkImg, 'http') ? $etkImg : (file_exists(public_path('uploads/' . $etkImg)) ? asset('uploads/' . $etkImg) : asset('storage/' . $etkImg));
                        @endphp
                        <img alt="{{ $event->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            src="{{ $etkUrl }}" />
                    @else
                        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                            <span class="material-symbols-outlined text-5xl">event</span>
                        </div>
                    @endif
                    
                    <div class="absolute top-4 left-4 bg-white rounded-xl md:rounded-[1.2rem] p-2 md:p-3 text-center min-w-[55px] md:min-w-[65px] shadow-xl">
                        <div class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">
                            {{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('M') }}
                        </div>
                        <div class="text-xl md:text-2xl font-black text-primary leading-tight">
                            {{ \Carbon\Carbon::parse($event->start_time)->format('d') }}
                        </div>
                    </div>
                </div>
                <div class="p-4 md:p-8 flex flex-col flex-1 text-white">
                    <div class="flex items-center gap-2 md:gap-3 mb-3 md:mb-4">
                        @if($event->category)
                        <span class="bg-white/10 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded tracking-wider border border-white/10">
                            {{ $event->category->name }}
                        </span>
                        @endif
                        <span class="text-white/80 text-xs flex items-center gap-1.5 font-medium">
                            <span class="material-symbols-outlined text-sm">schedule</span> 
                            {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                        </span>
                    </div>
                    <h4 class="text-sm md:text-2xl font-extrabold font-headline mb-1 md:mb-4 leading-tight uppercase group-hover:text-amber-200 transition-colors">
                        {{ $event->title }}
                    </h4>
                    <p class="text-white/60 text-xs md:text-sm mb-4 md:mb-8 line-clamp-2 leading-relaxed font-body">
                        {{ $event->short_description ?? strip_tags($event->description) }}
                    </p>
                    <div class="mt-auto flex items-center justify-between pt-4 md:pt-5 border-t border-white/5">
                        <div class="flex items-center gap-1 md:gap-2">
                            <span class="material-symbols-outlined text-sm text-white/40">location_on</span>
                            <span class="text-xs text-white/70 font-medium truncate max-w-[140px]">{{ $event->location ?? 'Yer Belirtilmedi' }}</span>
                        </div>
                        <div class="text-white font-bold text-xs md:text-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                            Detaylar <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-16 bg-slate-50 border border-dashed border-slate-200 rounded-3xl">
                <span class="material-symbols-outlined text-6xl text-slate-300 mb-4 inline-block">event_busy</span>
                <h3 class="text-xl font-bold text-slate-600 mb-2">Henüz Etkinlik Bulunmuyor</h3>
                <p class="text-slate-400">Şu anda planlanmış bir etkinlik verisi bulunamadı.</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($events->hasPages())
        <div class="mt-12 md:mt-16">
            {{ $events->links('partials.custom-pagination') }}
        </div>
        @endif
    </section>
@endsection
