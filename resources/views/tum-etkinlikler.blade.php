@extends('layouts.app')

@section('title', 'Tüm Etkinlikler - Fırat Üniversitesi')
@section('data-page', 'tum-etkinlikler')
@section('page-title', 'Tüm Etkinlikler')

@section('content')
    <!-- Hero Section -->
    <section class="bg-surface-container py-12 md:py-20 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold font-headline text-on-background mb-4">Tüm Etkinlikler</h1>
            <p class="text-on-surface-variant text-base md:text-xl max-w-2xl mx-auto font-body">Üniversitemizdeki tüm yaklaşan ve geçmiş etkinlikleri takip edin.</p>
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 md:py-16">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @forelse($events as $event)
            <a href="{{ route('etkinlik.detay', ['slug' => $event->slug]) }}"
                class="group bg-white border border-black/5 rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full cursor-pointer">
                <div class="relative h-48 md:h-60 w-full overflow-hidden shrink-0 bg-slate-100">
                    @if($event->image)
                        <img alt="{{ $event->title }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                             src="{{ asset('storage/' . $event->image) }}" />
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <span class="material-symbols-outlined text-5xl">event</span>
                        </div>
                    @endif
                    <div class="absolute top-3 md:top-4 left-3 md:left-4 bg-white/90 backdrop-blur-sm border border-white/20 rounded-xl p-2 text-center min-w-[55px] shadow-md text-primary">
                        <div class="text-[10px] font-bold uppercase leading-none mb-1 text-slate-500">{{\Carbon\Carbon::parse($event->start_time)->translatedFormat('M')}}</div>
                        <div class="text-xl md:text-2xl font-extrabold leading-none">{{\Carbon\Carbon::parse($event->start_time)->format('d')}}</div>
                    </div>
                </div>
                <div class="p-5 md:p-6 flex flex-col flex-1">
                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        @if($event->category)
                        <span class="bg-primary/10 text-primary text-[10px] font-extrabold uppercase px-2 py-0.5 rounded tracking-wider">{{ $event->category->name }}</span>
                        @endif
                        <span class="text-on-surface-variant text-xs flex items-center gap-1 font-medium">
                            <span class="material-symbols-outlined text-[14px]">schedule</span> 
                            {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                            @if($event->end_time) - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}@endif
                        </span>
                    </div>
                    <h4 class="text-lg md:text-xl font-bold font-headline mb-2 text-on-surface group-hover:text-primary transition-colors leading-snug">
                        {{ $event->title }}
                    </h4>
                    <p class="text-on-surface-variant text-sm mb-4 line-clamp-2 leading-relaxed flex-1">
                        {{ $event->short_description ?? strip_tags($event->description) }}
                    </p>
                    <div class="mt-auto flex items-center justify-between pt-4 border-t border-black/5">
                        <div class="flex items-center gap-1.5 md:gap-2 text-on-surface-variant">
                            <span class="material-symbols-outlined text-[18px] text-primary">location_on</span>
                            <span class="text-xs font-medium truncate max-w-[150px]">{{ $event->location ?? 'Yer Belirtilmedi' }}</span>
                        </div>
                        <div class="text-primary font-bold text-sm flex items-center gap-1">
                            İncele <span class="material-symbols-outlined text-[16px] transition-transform group-hover:translate-x-1">arrow_forward</span>
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
        
        <div class="mt-12">
            @if($events->hasPages())
                {{ $events->links() }}
            @endif
        </div>
    </section>
@endsection
