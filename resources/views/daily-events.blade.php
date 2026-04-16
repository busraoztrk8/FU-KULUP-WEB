@extends('layouts.app')

@section('title', $date->translatedFormat('j F Y') . ' Etkinlikleri - Fırat Üniversitesi')
@section('data-page', 'daily-events')

@section('content')
    <!-- Header Section -->
    <section class="bg-slate-50 border-b border-slate-100 py-12 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <a href="{{ route('etkinlikler') }}" class="inline-flex items-center gap-2 text-primary font-bold text-sm mb-4 hover:gap-3 transition-all">
                        <span class="material-symbols-outlined text-base">arrow_back</span>
                        Etkinlik Takvimine Dön
                    </a>
                    <h1 class="text-3xl md:text-5xl font-bold font-headline text-on-background mb-4 capitalize">
                        {{ $date->translatedFormat('j F Y, l') }}
                    </h1>
                    <p class="text-on-surface-variant text-base md:text-lg max-w-2xl">
                        Bu tarihte kampüsümüzde gerçekleşecek olan toplam {{ $events->count() }} etkinlik bulunmaktadır.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-3">
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">calendar_month</span>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Seçili Tarih</div>
                            <div class="text-sm font-bold text-on-background">{{ $date->translatedFormat('d.m.Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Events Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-12 md:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @forelse($events as $event)
                <div class="group bg-white border border-slate-100 rounded-3xl overflow-hidden hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500 hover:-translate-y-1 block">
                    <div class="relative h-48 sm:h-56 overflow-hidden">
                        @if($event->image)
                            @php
                                $dImg = $event->image;
                                $dUrl = str_starts_with($dImg, 'http') ? $dImg : (file_exists(public_path('uploads/' . $dImg)) ? asset('uploads/' . $dImg) : asset('storage/' . $dImg));
                            @endphp
                            <img alt="{{ $event->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                src="{{ $dUrl }}" />
                        @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                                <span class="material-symbols-outlined text-5xl">event</span>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4">
                            @if($event->category)
                                <span class="bg-white/90 backdrop-blur-md text-primary text-[10px] font-extrabold uppercase px-3 py-1.5 rounded-full shadow-sm">{{ $event->category->name }}</span>
                            @endif
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                    
                    <div class="p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-primary text-xs font-bold flex items-center gap-1.5 bg-primary/5 px-2.5 py-1 rounded-lg">
                                <span class="material-symbols-outlined text-sm">schedule</span>
                                {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                            </span>
                            @if($event->location)
                            <span class="text-on-surface-variant text-xs flex items-center gap-1.5 truncate max-w-[150px]">
                                <span class="material-symbols-outlined text-sm">location_on</span>
                                {{ $event->location }}
                            </span>
                            @endif
                        </div>
                        
                        <h3 class="text-xl font-bold font-headline mb-3 text-on-background group-hover:text-primary transition-colors line-clamp-2 min-h-[3.5rem]">
                            {{ $event->title }}
                        </h3>
                        
                        <p class="text-on-surface-variant text-sm mb-6 line-clamp-3 leading-relaxed">
                            {{ $event->short_description ?? strip_tags($event->description) }}
                        </p>
                        
                        <div class="flex items-center justify-between pt-6 border-t border-slate-50 mt-auto">
                            <div class="flex items-center gap-2">
                                @if($event->club && $event->club->logo)
                                    @php
                                        $clLogo = $event->club->logo;
                                        $clUrl = str_starts_with($clLogo, 'http') ? $clLogo : (file_exists(public_path('uploads/' . $clLogo)) ? asset('uploads/' . $clLogo) : asset('storage/' . $clLogo));
                                    @endphp
                                    <img src="{{ $clUrl }}" 
                                         class="w-6 h-6 rounded-full object-cover border border-slate-100" />
                                @endif
                                <span class="text-[11px] font-bold text-slate-500 truncate max-w-[100px]">{{ $event->club ? $event->club->name : 'Fırat Üniversitesi' }}</span>
                            </div>
                            <a href="{{ route('etkinlik.detay', $event->slug) }}" class="inline-flex items-center gap-1 text-primary font-bold text-sm group/btn">
                                Detaylar
                                <span class="material-symbols-outlined text-sm group-hover/btn:translate-x-1 transition-transform">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full border-2 border-dashed border-slate-100 rounded-[3rem] p-12 md:p-20 text-center flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-4xl text-slate-300">event_busy</span>
                    </div>
                    <h3 class="text-2xl font-bold text-on-background mb-2">Etkinlik Bulunamadı</h3>
                    <p class="text-on-surface-variant max-w-md mx-auto">Bu tarih için planlanmış bir etkinlik bulunmuyor.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
