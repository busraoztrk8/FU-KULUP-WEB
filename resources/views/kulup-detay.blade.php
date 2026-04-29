@extends('layouts.app')
@section('title', e(app()->getLocale() == 'en' && $club->name_en ? $club->name_en : $club->name) . ' - ' . __('site.university_name'))
@section('data-page', 'club-detail')
@push('styles')
<style>
    .gallery-slider .swiper-slide {
        width: auto;
    }
    .gallery-image-container::after {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.4);
        opacity: 0;
        transition: opacity 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .gallery-image-container:hover::after {
        opacity: 1;
        content: "\e3f4"; /* photo_library icon in material symbols */
        font-family: 'Material Symbols Outlined';
        color: white;
        font-size: 24px;
    }
    #lightbox-modal {
        transition: opacity 0.3s ease-out;
    }
    #lightbox-modal.hidden {
        display: none;
        opacity: 0;
    }
</style>
@endpush

@section('content')
<div class="pb-12 md:pb-20 px-4 sm:px-6 md:px-8 max-w-7xl mx-auto">


    {{-- Hero Sector --}}
    <section class="relative mb-12 rounded-3xl overflow-hidden h-[300px] md:h-[450px] shadow-2xl group">
        @if($club->cover_image)
            @php
                $coverPath = $club->cover_image;
                $coverUrl = str_starts_with($coverPath, 'http') ? $coverPath : (file_exists(public_path('uploads/' . $coverPath)) ? asset('uploads/' . $coverPath) : asset('storage/' . $coverPath));
            @endphp
            <img src="{{ $coverUrl }}" alt="{{ $club->name }}"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"/>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

        <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full flex flex-col md:flex-row items-start md:items-end gap-6">
            {{-- Logo --}}
            <div class="w-24 h-24 md:w-32 md:h-32 rounded-2xl bg-white p-2 shadow-2xl shrink-0">
                @if($club->logo)
                    @php
                        $logoPath = $club->logo;
                        $logoUrl = str_starts_with($logoPath, 'http') ? $logoPath : (file_exists(public_path('uploads/' . $logoPath)) ? asset('uploads/' . $logoPath) : asset('storage/' . $logoPath));
                    @endphp
                    <img src="{{ $logoUrl }}" alt="{{ $club->name }}"
                        class="w-full h-full object-cover rounded-xl"/>
                @else
                    <div class="w-full h-full bg-primary rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-4xl">groups</span>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h1 class="font-headline text-3xl md:text-5xl font-extrabold text-white tracking-tight leading-tight mb-4 drop-shadow-lg break-words">
                    {{ app()->getLocale() == 'en' && $club->name_en ? $club->name_en : $club->name }}
                </h1>
                <div class="flex flex-wrap gap-3">
                    @if($club->category)
                    <span class="px-4 py-1.5 bg-primary/90 backdrop-blur-md text-white rounded-full text-[11px] font-bold uppercase tracking-widest border border-white/20">
                        {{ app()->getLocale() == 'en' && $club->category->name_en ? $club->category->name_en : $club->category->name }}
                    </span>
                    @endif
                    <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md text-white rounded-full text-[11px] font-bold border border-white/10">
                        {{ number_format($club->total_active_member_count) }} {{ __('site.members') }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

        {{-- Left Column: Hakkımızda & Activities --}}
        <div class="lg:col-span-8 space-y-12">
            
            {{-- Hakkımızda --}}
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                <div class="flex items-center gap-4 mb-8">
                    <span class="w-1.5 h-10 bg-primary rounded-full"></span>
                    <h2 class="font-headline text-3xl font-bold text-slate-800">{{ __('site.about_us') }}</h2>
                </div>
                
                <div class="prose prose-slate max-w-none mb-10 text-slate-600 leading-relaxed text-lg break-words">
                    {!! nl2br(e(app()->getLocale() == 'en' && $club->description_en ? $club->description_en : ($club->description ?? __('site.no_club_desc')))) !!}
                </div>

                {{-- Mission & Vision Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    {{-- Mission --}}
                    <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100 group hover:border-primary/30 transition-colors">
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                            <span class="material-symbols-outlined text-primary text-[28px]">rocket_launch</span>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-2">{{ __('site.our_mission') }}</h4>
                        <p class="text-sm text-slate-500 leading-relaxed italic break-words">
                            {{ $club->mission ?? __('site.default_mission') }}
                        </p>
                    </div>
                    {{-- Vision --}}
                    <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100 group hover:border-primary/30 transition-colors">
                        <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                            <span class="material-symbols-outlined text-amber-500 text-[28px]">visibility</span>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-2">{{ __('site.our_vision') }}</h4>
                        <p class="text-sm text-slate-500 leading-relaxed italic break-words">
                            {{ $club->vision ?? __('site.default_vision') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Kulüp Faaliyetleri --}}
            @if($club->activities)
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <span class="w-1.5 h-10 bg-primary rounded-full"></span>
                    <h2 class="font-headline text-2xl font-bold text-slate-800 uppercase tracking-tight">{{ __('site.club_activities') }}</h2>
                </div>
                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed break-words">
                    {!! nl2br(e($club->activities)) !!}
                </div>
            </div>
            @endif
            
            {{-- Kulüp Haberleri --}}
            @if(isset($news) && $news->count() > 0)
            @php 
                $displayNews = $news;
                if($displayNews->count() > 0 && $displayNews->count() < 8) {
                    $displayNews = $displayNews->concat($displayNews)->concat($displayNews)->take(8); 
                }
            @endphp
            <div class="news-wrapper mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-headline text-xl sm:text-2xl font-bold text-slate-800 flex items-center gap-2 sm:gap-3">
                        <span class="material-symbols-outlined text-primary">newspaper</span>
                        {{ __('site.recent_news') }}
                    </h2>
                    <div class="flex items-center gap-4 sm:gap-6">
                        <div class="hidden sm:flex gap-2">
                            <button class="club-news-prev w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                            </button>
                            <button class="club-news-next w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                            </button>
                        </div>
                        <div class="hidden sm:block w-px h-6 bg-slate-200"></div>
                        <a href="{{ route('tum-haberler') }}?club={{ $club->slug }}" class="text-primary text-sm font-bold hover:underline whitespace-nowrap">{{ __('site.see_all') }}</a>
                    </div>
                </div>

                <div class="swiper club-news-swiper overflow-hidden rounded-3xl max-w-4xl mx-auto">
                    <div class="swiper-wrapper">
                        @foreach($displayNews as $item)
                        <div class="swiper-slide !h-auto py-4">
                            <a href="{{ route('haber.detay', $item->slug) }}" class="w-full group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-slate-100 block h-full flex flex-col">
                                <div class="aspect-[4/3] relative overflow-hidden">
                                    @php
                                        $newsImg = $item->image_path;
                                        $newsUrl = $newsImg ? (str_starts_with($newsImg, 'http') ? $newsImg : (file_exists(public_path('uploads/' . $newsImg)) ? asset('uploads/' . $newsImg) : asset('storage/' . $newsImg))) : asset('images/logo_orj.png');
                                    @endphp
                                    <img src="{{ $newsUrl }}" alt="{{ $item->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"/>
                                    <div class="absolute top-4 left-4">
                                        <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-lg border border-white/50 text-center min-w-[50px]">
                                            <span class="block text-lg font-black text-primary leading-none">{{ $item->created_at->format('d') }}</span>
                                            <span class="block text-[8px] font-bold text-slate-500 uppercase tracking-widest">{{ $item->created_at->isoFormat('MMM') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-6 flex-1 flex flex-col">
                                    <h3 class="font-bold text-slate-800 mb-2 group-hover:text-primary transition-colors line-clamp-2">
                                        {{ app()->getLocale() == 'en' && $item->title_en ? $item->title_en : $item->title }}
                                    </h3>
                                    <p class="text-slate-500 text-xs line-clamp-2 mb-4">
                                        {{ Str::limit(strip_tags(app()->getLocale() == 'en' && $item->content_en ? $item->content_en : $item->content), 100) }}
                                    </p>
                                    <div class="flex items-center gap-4 text-slate-400 text-[11px] font-medium mt-auto pt-4 border-t border-slate-50">
                                        <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">calendar_today</span>{{ $item->created_at->format('d.m.Y') }}</span>
                                        <span class="text-primary font-bold ml-auto flex items-center gap-1">{{ __('site.read_more') }} <span class="material-symbols-outlined text-[14px]">arrow_forward</span></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Club Gallery --}}
            @if($club->images->count() > 0)
            <div class="gallery-wrapper mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-headline text-xl sm:text-2xl font-bold text-slate-800 flex items-center gap-2 sm:gap-3">
                        <span class="material-symbols-outlined text-primary">photo_library</span>
                        {{ __('site.club_gallery') }}
                    </h2>
                    <div class="flex items-center gap-4 sm:gap-6">
                        <div class="hidden sm:flex gap-2">
                            <button class="gallery-prev w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                            </button>
                            <button class="gallery-next w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                            </button>
                        </div>
                        <div class="hidden sm:block w-px h-6 bg-slate-200"></div>
                        <a href="{{ route('kulup.galeri', $club->slug) }}" class="text-primary text-sm font-bold hover:underline whitespace-nowrap">{{ __('site.see_all') }}</a>
                    </div>
                </div>
                <div class="swiper gallery-slider overflow-hidden rounded-3xl max-w-4xl mx-auto">
                    <div class="swiper-wrapper">
                        @php
                            $displayImages = $club->images;
                            if($displayImages->count() > 0 && $displayImages->count() < 8) {
                                $displayImages = $displayImages->concat($displayImages)->concat($displayImages)->take(8);
                            }
                        @endphp
                        @foreach($displayImages as $index => $image)
                        <div class="swiper-slide">
                            <div class="gallery-image-container relative w-full aspect-[4/3] rounded-2xl overflow-hidden border border-slate-100 group shadow-sm hover:shadow-xl transition-all cursor-pointer"
                                 onclick="openLightbox({{ $index }})">
                                @php
                                    $galPath = $image->image_path;
                                    $galUrl = str_starts_with($galPath, 'http') ? $galPath : (file_exists(public_path('uploads/' . $galPath)) ? asset('uploads/' . $galPath) : asset('storage/' . $galPath));
                                @endphp
                                <img src="{{ $galUrl }}" 
                                     data-full="{{ $galUrl }}"
                                     class="gallery-thumb w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Upcoming Events --}}
            @php
                $upcomingEvents = $club->events()->where('start_time', '>=', now()->subHours(5))->orderBy('start_time')->get();
            @endphp
            @if($upcomingEvents->count() > 0)
            @php
                $displayEvents = $upcomingEvents;
                if($displayEvents->count() > 0 && $displayEvents->count() < 8) {
                    $displayEvents = $displayEvents->concat($displayEvents)->concat($displayEvents)->take(8);
                }
            @endphp
            <div class="events-wrapper">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-headline text-xl sm:text-2xl font-bold text-slate-800 flex items-center gap-2 sm:gap-3">
                        <span class="material-symbols-outlined text-primary">event_upcoming</span>
                        {{ __('site.upcoming_events') }}
                    </h2>
                    <div class="flex items-center gap-4 sm:gap-6">
                        @if($upcomingEvents->count() > 1)
                        <div class="hidden sm:flex gap-2">
                            <button class="club-events-prev w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                            </button>
                            <button class="club-events-next w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                            </button>
                        </div>
                        @endif
                        <div class="hidden sm:block w-px h-6 bg-slate-200"></div>
                        <a href="{{ route('tum-etkinlikler') }}?club={{ $club->slug }}" class="text-primary text-sm font-bold hover:underline whitespace-nowrap">{{ __('site.see_all') }}</a>
                    </div>
                </div>

                @if($upcomingEvents->count() === 1)
                    @php $event = $upcomingEvents->first(); @endphp
                    <a href="{{ route('etkinlik.detay', $event->slug) }}" class="group relative block bg-white rounded-[32px] overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100">
                        <div class="flex flex-col md:flex-row h-full">
                            <div class="md:w-2/5 aspect-[4/3] md:aspect-auto relative overflow-hidden">
                                @if($event->image)
                                    @php $evtUrl = str_starts_with($event->image, 'http') ? $event->image : (file_exists(public_path('uploads/' . $event->image)) ? asset('uploads/' . $event->image) : asset('storage/' . $event->image)); @endphp
                                    <img src="{{ $evtUrl }}" alt="{{ $event->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"/>
                                @else
                                    <div class="absolute inset-0 bg-gradient-to-br from-primary/20 to-primary/40 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-white text-6xl opacity-30">event</span>
                                    </div>
                                @endif
                                <div class="absolute top-6 left-6">
                                    <div class="bg-white/90 backdrop-blur-md px-4 py-2 rounded-2xl shadow-xl border border-white/50 text-center">
                                        <span class="block text-xl font-black text-primary leading-none">{{ $event->start_time->format('d') }}</span>
                                        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">{{ $event->start_time->isoFormat('MMM') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="md:w-3/5 p-8 md:p-10 flex flex-col justify-center">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-wider rounded-lg border border-primary/5">{{ __('site.open_event') }}</span>
                                    <span class="text-slate-400 text-xs flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">schedule</span>{{ $event->start_time->format('H:i') }}</span>
                                </div>
                                <h3 class="text-2xl md:text-3xl font-headline font-bold text-slate-800 mb-4 group-hover:text-primary transition-colors leading-tight">{{ $event->title }}</h3>
                                <p class="text-slate-500 text-sm leading-relaxed mb-8 line-clamp-3">{{ $event->short_description ?? Str::limit(strip_tags($event->description), 200) }}</p>
                                <div class="flex items-center gap-6 mt-auto">
                                    <div class="flex items-center gap-2 text-slate-600 text-sm">
                                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-primary text-[18px]">location_on</span>
                                        </div>
                                        <span class="font-medium truncate max-w-[150px]">{{ $event->location ?? __('site.not_specified') }}</span>
                                    </div>
                                    <div class="text-primary font-bold text-sm flex items-center gap-1 group-hover:gap-2 transition-all ml-auto">
                                        {{ __('site.details') }} <span class="material-symbols-outlined text-[18px]">arrow_right_alt</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @else
                    <div class="swiper club-events-swiper overflow-hidden rounded-3xl max-w-4xl mx-auto">
                        <div class="swiper-wrapper">
                            @foreach($displayEvents as $event)
                             <div class="swiper-slide !h-auto py-4">
                                <a href="{{ route('etkinlik.detay', $event->slug) }}" class="w-full group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-slate-100 block h-full equal-height-card">
                                    <div class="aspect-[4/3] relative overflow-hidden">
                                        @if($event->image)
                                            @php $evtUrl = str_starts_with($event->image, 'http') ? $event->image : (file_exists(public_path('uploads/' . $event->image)) ? asset('uploads/' . $event->image) : asset('storage/' . $event->image)); @endphp
                                            <img src="{{ $evtUrl }}" alt="{{ $event->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"/>
                                        @else
                                            <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-primary/30 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-primary/30 text-5xl">event</span>
                                            </div>
                                        @endif
                                        <div class="absolute top-4 left-4">
                                            <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-lg border border-white/50 text-center min-w-[50px]">
                                                <span class="block text-lg font-black text-primary leading-none">{{ $event->start_time->format('d') }}</span>
                                                <span class="block text-[8px] font-bold text-slate-500 uppercase tracking-widest">{{ $event->start_time->isoFormat('MMM') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-6 card-content">
                                        <h3 class="font-bold text-slate-800 mb-2 group-hover:text-primary transition-colors line-clamp-2">{{ $event->title }}</h3>
                                        <div class="flex items-center gap-4 text-slate-400 text-[11px] font-medium mt-auto pt-4 border-t border-slate-50">
                                            <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">location_on</span>{{ Str::limit($event->location ?? __('site.not_specified'), 20) }}</span>
                                            <span class="flex items-center gap-1.5 ml-auto"><span class="material-symbols-outlined text-[14px]">schedule</span>{{ $event->start_time->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            @endif

        </div>

        {{-- Right Column: Sidebar --}}
        <aside class="lg:col-span-4 space-y-8">

            {{-- Info Card (Mini Club Card Style) --}}
            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm relative overflow-hidden group">
                <div class="h-24 bg-gradient-to-br from-primary via-primary-dark to-slate-900 absolute top-0 inset-x-0 opacity-10 group-hover:opacity-20 transition-opacity"></div>
                <div class="p-8 pt-10 relative">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 rounded-2xl bg-white p-1.5 shadow-xl border border-slate-50 shrink-0">
                            @if($club->logo)
                                @php
                                    $logoUrl = str_starts_with($club->logo, 'http') ? $club->logo : (file_exists(public_path('uploads/' . $club->logo)) ? asset('uploads/' . $club->logo) : asset('storage/' . $club->logo));
                                @endphp
                                <img src="{{ $logoUrl }}" class="w-full h-full object-cover rounded-xl" alt="">
                            @else
                                <div class="w-full h-full bg-primary/10 rounded-xl flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-2xl">groups</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-headline text-xl font-bold text-slate-800 line-clamp-1">{{ app()->getLocale() == 'en' && $club->name_en ? $club->name_en : $club->name }}</h3>
                            <p class="text-[10px] font-bold text-primary uppercase tracking-widest mt-0.5">{{ app()->getLocale() == 'en' && $club->category && $club->category->name_en ? $club->category->name_en : ($club->category->name ?? __('site.general')) }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 group/item hover:bg-slate-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary/60 text-[20px]">person</span>
                                <span class="text-slate-500 text-xs font-semibold">{{ __('site.founder') }}</span>
                            </div>
                            <span class="font-bold text-slate-800 text-xs text-right">{{ $club->founder_name ?? ($club->president->name ?? '-') }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 group/item hover:bg-slate-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary/60 text-[20px]">groups</span>
                                <span class="text-slate-500 text-xs font-semibold">{{ __('site.member_count_label') }}</span>
                            </div>
                            <span class="font-bold text-slate-800 text-xs">{{ number_format($club->total_active_member_count) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 group/item hover:bg-slate-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary/60 text-[20px]">calendar_today</span>
                                <span class="text-slate-500 text-xs font-semibold">{{ __('site.established') }}</span>
                            </div>
                            <span class="font-bold text-slate-800 text-xs">{{ $club->established_year ?? $club->created_at->format('Y') }}</span>
                        </div>
                    </div>

                    @auth
                        @php
                            if (!isset($membership)) {
                                $membership = auth()->check()
                                    ? \App\Models\ClubMember::where('club_id', $club->id)->where('user_id', auth()->id())->first()
                                    : null;
                            }
                        @endphp

                        @if(!$membership)
                            <button type="button" onclick="openRegistrationModal()" class="w-full py-4 bg-[#5d1021] text-white rounded-2xl font-bold text-base hover:opacity-90 active:scale-95 transition-all shadow-xl shadow-primary/20 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">person_add</span>
                                {{ __('site.join_club') }}
                            </button>
                        @elseif($membership->status === 'pending')
                            <div class="w-full py-3 bg-amber-50 text-amber-700 rounded-2xl font-bold text-xs text-center border border-amber-100 italic mb-2">{{ __('site.application_pending') }}</div>
                            <button onclick="document.getElementById('withdraw-modal').classList.remove('hidden')" class="w-full py-3 bg-white text-amber-600 rounded-2xl font-bold text-sm hover:bg-amber-50 transition-all border border-amber-200 flex items-center justify-center gap-2 shadow-sm">
                                <span class="material-symbols-outlined text-[18px]">cancel</span> Başvuruyu Geri Çek
                            </button>
                        @else
                            <div class="w-full py-4 bg-green-50 text-green-700 rounded-2xl font-bold text-sm text-center border border-green-100 mb-4 tracking-tight">{{ __('site.club_member') }}</div>
                            
                            {{-- Member Exclusive Links --}}
                            @if($club->whatsapp_url || $club->channel_url)
                                <div class="grid grid-cols-2 gap-2 mb-4">
                                    @if($club->whatsapp_url)
                                    <a href="{{ $club->whatsapp_url }}" target="_blank" class="flex flex-col items-center justify-center gap-1 p-2 bg-green-500 hover:bg-green-600 text-white rounded-xl transition-all shadow-sm">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        <span class="text-[9px] font-bold">WhatsApp</span>
                                    </a>
                                    @endif
                                    @if($club->channel_url)
                                    <a href="{{ $club->channel_url }}" target="_blank" class="flex flex-col items-center justify-center gap-1 p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl transition-all shadow-sm">
                                        <span class="material-symbols-outlined text-base">campaign</span>
                                        <span class="text-[9px] font-bold">{{ __('site.announcement_channel') }}</span>
                                    </a>
                                    @endif
                                </div>
                            @endif

                            {{-- Kulüpten Çık --}}
                            <button onclick="document.getElementById('leave-modal').classList.remove('hidden')"
                                class="w-full py-3 mt-2 bg-red-50 text-red-600 rounded-2xl font-bold text-sm hover:bg-red-100 transition-all border border-red-100 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">logout</span> {{ __('site.leave_club') }}
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="w-full py-4 bg-[#5d1021] text-white rounded-2xl font-bold text-base hover:opacity-90 flex items-center justify-center gap-2 shadow-xl shadow-black/10">
                            <span class="material-symbols-outlined text-[20px]">login</span> {{ __('site.login_and_join') }}
                        </a>
                    @endauth

                    <div class="mt-10 pt-8 border-t border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center mb-6">{{ __('site.social_share') }}</p>
                        <div class="flex flex-wrap justify-center gap-3">
                            @if($club->website_url)
                                <a href="{{ $club->website_url }}" target="_blank" class="w-11 h-11 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm" title="Web Sitesi">
                                    <span class="material-symbols-outlined text-[18px]">public</span>
                                </a>
                            @endif
                            @if($club->instagram_url)
                                <a href="{{ $club->instagram_url }}" target="_blank" class="w-11 h-11 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-gradient-to-tr hover:from-orange-500 hover:to-purple-600 hover:text-white hover:border-transparent transition-all shadow-sm" title="Instagram">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.332 3.608 1.308.975.975 1.246 2.242 1.308 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.332 2.633-1.308 3.608-.975.975-2.242 1.246-3.608 1.308-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.332-3.608-1.308-.975-.975-1.246-2.242-1.308-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.332-2.633 1.308-3.608.975-.975 2.242-1.246 3.608-1.308 1.266-.058 1.646-.07 4.85-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-1.277.059-2.148.262-2.911.558-.788.306-1.457.715-2.123 1.381s-1.075 1.335-1.381 2.123c-.296.763-.499 1.634-.558 2.911-.058 1.28-.072 1.688-.072 4.947s.014 3.667.072 4.947c.059 1.277.262 2.148.558 2.911.306.788.715 1.457 1.381 2.123s1.335 1.075 2.123 1.381c.763.296 1.634.499 2.911.558 1.28.058 1.688.072 4.947.072s3.667-.014 4.947-.072c1.277-.059 2.148-.262 2.911-.558.788-.306 1.457-.715 2.123-1.381s1.075-1.335 1.381-2.123c.296-.763.499-1.634.558-2.911.058-1.28.072-1.688.072-4.947s-.014-3.667-.072-4.947c-.059-1.277-.262-2.148-.558-2.911-.306-.788-.715-1.457-1.381-2.123s-1.335-1.075-2.123-1.381c-.763-.296-1.634-.499-2.911-.558-1.28-.058-1.688-.072-4.947-.072zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.791-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.209-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </a>
                            @endif
                            @if($club->twitter_url)
                                <a href="{{ $club->twitter_url }}" target="_blank" class="w-11 h-11 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-black hover:text-white hover:border-black transition-all shadow-sm" title="X (Twitter)">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                            @endif
                            @if($club->youtube_url)
                                <a href="{{ $club->youtube_url }}" target="_blank" class="w-11 h-11 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-red-600 hover:text-white hover:border-red-600 transition-all shadow-sm" title="YouTube">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                </a>
                            @endif
                            @if($club->facebook_url)
                                <a href="{{ $club->facebook_url }}" target="_blank" class="w-11 h-11 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm" title="Facebook">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.791-4.667 4.53-4.667 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            @endif
                            <button onclick="navigator.share ? navigator.share({title:'{{ addslashes($club->name) }}', url: window.location.href}) : navigator.clipboard.writeText(window.location.href)" class="w-11 h-11 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm" title="Paylaş">
                                <span class="material-symbols-outlined text-[18px]">share</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Active Members / Board --}}
            @php
                $boardMembers = collect();
                
                if ($club->president_id && $club->president) {
                    $presidentMember = new \App\Models\ClubMember();
                    $presidentMember->user_id = $club->president->id;
                    $presidentMember->title = 'Başkan';
                    $presidentMember->status = 'approved';
                    $presidentMember->setRelation('user', $club->president);
                    $boardMembers->push($presidentMember);
                }

                $otherBoardMembers = $club->members()
                    ->where('status', 'approved')
                    ->whereNotNull('title')
                    ->where('title', '!=', '');
                    
                if ($club->president_id) {
                    $otherBoardMembers->where('user_id', '!=', $club->president_id);
                }

                $otherBoardMembers = $otherBoardMembers->orderByRaw("CASE 
                        WHEN title = 'Başkan' THEN 1 
                        WHEN title = 'Başkan Yardımcısı' THEN 2 
                        WHEN title LIKE '%Başkan Yardımcısı%' THEN 3 
                        WHEN title LIKE '%Başkan%' THEN 4
                        WHEN title IS NOT NULL AND title != '' THEN 5
                        ELSE 6 
                    END")
                    ->orderBy('title')
                    ->get();
                    
                $boardMembers = $boardMembers->concat($otherBoardMembers);
            @endphp

            @if($boardMembers->count() > 0)
            <div class="bg-white rounded-[32px] p-8 border border-slate-100 shadow-sm mt-8">
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-1 h-6 bg-[#5d1021] rounded-full"></span>
                    <h3 class="font-headline text-xl font-bold text-slate-800">{{ __('site.active_members') }}</h3>
                </div>
                
                <div class="space-y-5">
                    @foreach($boardMembers as $member)
                        @php
                            $isPres = ($member->title === 'Başkan');
                            $memberTitle = $member->title;
                            if (empty(trim($memberTitle))) {
                                $memberTitle = (app()->getLocale() == 'en') ? 'Member' : 'Üye';
                            }
                        @endphp
                        <div class="flex items-center gap-4 group">
                            <div class="relative shrink-0">
                                <div class="w-12 h-12 rounded-full overflow-hidden shadow-sm border {{ $isPres ? 'border-[#5d1021]' : 'border-slate-100' }}">
                                    @if($member->user && $member->user->profile_photo)
                                        @php
                                            $mPhoto = $member->user->profile_photo;
                                            $mPhotoUrl = str_starts_with($mPhoto, 'http') ? $mPhoto : (file_exists(public_path('uploads/' . $mPhoto)) ? asset('uploads/' . $mPhoto) : asset('storage/' . $mPhoto));
                                        @endphp
                                        <img src="{{ $mPhotoUrl }}" class="w-full h-full object-cover" alt="{{ $member->user->name }}">
                                    @else
                                        <div class="w-full h-full bg-[#5d1021]/5 flex items-center justify-center text-[#5d1021]/60">
                                            <span class="material-symbols-outlined text-[20px]">person</span>
                                        </div>
                                    @endif
                                </div>
                                @if($isPres)
                                    <div class="absolute bottom-0 right-0 bg-[#5d1021] text-white rounded-full p-0.5 shadow-md translate-x-1/4 translate-y-1/4 z-10 flex items-center justify-center border border-white">
                                        <span class="material-symbols-outlined text-[11px] block">workspace_premium</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-slate-800 text-sm truncate group-hover:text-[#5d1021] transition-colors">{{ $member->user->name ?? 'İsimsiz' }}</h4>
                                <p class="text-[10px] font-bold uppercase tracking-wide mt-0.5 {{ $isPres ? 'text-[#5d1021]' : 'text-slate-400' }}">
                                    @if(app()->getLocale() == 'en')
                                        @php
                                            $translatedTitle = \Illuminate\Support\Facades\Cache::remember('title_en_' . \Illuminate\Support\Str::slug($memberTitle), 86400, function() use ($memberTitle) {
                                                try {
                                                    return \Stichoza\GoogleTranslate\GoogleTranslate::trans($memberTitle, 'en', 'tr');
                                                } catch (\Exception $e) {
                                                    return $memberTitle;
                                                }
                                            });
                                        @endphp
                                        {{ $translatedTitle }}
                                    @else
                                        {{ $memberTitle }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

    </div>



</div>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- KULÜPTEN ÇIKIŞ ONAY MODALI --}}
{{-- ═══════════════════════════════════════════════════ --}}
@auth
@if(isset($membership) && $membership && $membership->status === 'approved')
<div id="leave-modal" class="fixed inset-0 z-[95] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('leave-modal').classList.add('hidden')"></div>
    <div class="relative bg-white w-full max-w-sm mx-4 rounded-3xl shadow-2xl p-8 text-center animate-in fade-in zoom-in duration-200">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-red-500 text-[32px]">logout</span>
        </div>
        <h3 class="font-headline text-xl font-bold text-slate-800 mb-2">{{ __('site.leave_club_confirm') }}</h3>
        <p class="text-slate-500 text-sm mb-6">{{ __('site.leave_club_message', ['name' => '<strong>' . (app()->getLocale() == 'en' && $club->name_en ? $club->name_en : $club->name) . '</strong>']) }}</p>
        <div class="flex gap-3">
            <button onclick="document.getElementById('leave-modal').classList.add('hidden')"
                class="flex-1 py-3 rounded-2xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-all">
                {{ __('site.cancel') }}
            </button>
            <form method="POST" action="{{ route('kulup.ayril', $club) }}" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 rounded-2xl bg-red-500 hover:bg-red-600 text-white font-bold text-sm transition-all">
                    {{ __('site.yes_leave') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endif
@endauth

{{-- ═══════════════════════════════════════════════════ --}}
{{-- KULÜP BAŞVURU İPTAL MODALI --}}
{{-- ═══════════════════════════════════════════════════ --}}
@auth
@if(isset($membership) && $membership && $membership->status === 'pending')
<div id="withdraw-modal" class="fixed inset-0 z-[95] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('withdraw-modal').classList.add('hidden')"></div>
    <div class="relative bg-white w-full max-w-sm mx-4 rounded-3xl shadow-2xl p-8 text-center animate-in fade-in zoom-in duration-200">
        <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-amber-500 text-[32px]">cancel</span>
        </div>
        <h3 class="font-headline text-xl font-bold text-slate-800 mb-2">Başvuruyu Geri Çek</h3>
        <p class="text-slate-500 text-sm mb-6">Kulüp üyeliği için yaptığınız başvuruyu iptal etmek istediğinize emin misiniz?</p>
        <div class="flex gap-3">
            <button onclick="document.getElementById('withdraw-modal').classList.add('hidden')"
                class="flex-1 py-3 rounded-2xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 transition-all">
                Vazgeç
            </button>
            <form method="POST" action="{{ route('kulup.ayril', $club) }}" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-3 rounded-2xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm transition-all">
                    Evet, Geri Çek
                </button>
            </form>
        </div>
    </div>
</div>
@endif
@endauth

{{-- ═══════════════════════════════════════════════════ --}}
{{-- KULÜP KAYIT FORMU MODALI --}}
{{-- ═══════════════════════════════════════════════════ --}}
@auth
@if(!$membership)
<div id="registration-modal" class="fixed inset-0 z-[90] flex items-center justify-center hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeRegistrationModal()"></div>

    {{-- Modal Content --}}
    <div class="relative bg-white w-full max-w-2xl mx-4 rounded-3xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col animate-in fade-in zoom-in duration-200">
        
        {{-- Header --}}
        <div class="bg-[#5d1021] px-8 py-6 text-white shrink-0">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    @if($club->logo)
                        @php
                            $modalLogo = $club->logo;
                            $modalLogoUrl = str_starts_with($modalLogo, 'http') ? $modalLogo : (file_exists(public_path('uploads/' . $modalLogo)) ? asset('uploads/' . $modalLogo) : asset('storage/' . $modalLogo));
                        @endphp
                        <img src="{{ $modalLogoUrl }}" 
                             class="w-12 h-12 rounded-xl object-cover border-2 border-white/20" alt="">
                    @else
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-white text-[24px]">groups</span>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-xl font-bold font-headline leading-tight">{{ app()->getLocale() == 'en' && $club->name_en ? $club->name_en : $club->name }}</h3>
                        <p class="text-white/70 text-xs font-medium">{{ __('site.registration_form') }}</p>
                    </div>
                </div>
                <button onclick="closeRegistrationModal()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-all">
                    <span class="material-symbols-outlined text-[22px]">close</span>
                </button>
            </div>
            <p class="text-white/80 text-sm leading-relaxed">{{ __('site.registration_desc') }}</p>
        </div>

        {{-- Zorunlu Alan Bilgisi --}}
        <div class="px-8 py-3 bg-red-50/80 border-b border-red-100 shrink-0">
            <p class="text-red-500 text-xs font-medium flex items-center gap-1.5">
                <span class="text-red-400">*</span> {{ __('site.required_field') }}
            </p>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
        <div class="px-8 pt-4 shrink-0">
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-red-700 text-sm font-bold mb-2 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">error</span> {{ __('site.fix_errors') }}
                </p>
                <ul class="text-red-600 text-xs space-y-1 pl-5 list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('kulup.kayit', $club) }}" method="POST" id="registration-form" class="flex-1 overflow-y-auto">
            @csrf
            <div class="px-8 py-6 space-y-5">
                @foreach($club->formFields as $field)
                @php
                    $labelMap = [
                        'Adınız - Soyadınız' => __('site.form_full_name'),
                        'Öğrenci Numaranız' => __('site.form_student_number'),
                        'E-posta Adresiniz' => __('site.form_email'),
                        'Fakülteniz - Bölümünüz' => __('site.form_department'),
                        'Telefon Numaranız' => __('site.form_phone'),
                        'KVKK metnini tam olarak okuduğumu, anladığımı ve onayladığımı kabul, beyan ve taahhüt ederim.' => __('site.form_kvkk'),
                    ];
                    $placeholderMap = [
                        'Yanıtınız' => __('site.your_answer'),
                        'ornek@email.com' => 'example@email.com',
                    ];
                    
                    $translatedLabel = app()->getLocale() == 'en' ? ($labelMap[$field->label] ?? $field->label) : $field->label;
                    $translatedPlaceholder = app()->getLocale() == 'en' ? ($placeholderMap[$field->placeholder] ?? $field->placeholder) : $field->placeholder;
                @endphp
                <div class="bg-slate-50/50 rounded-2xl p-5 border border-slate-100 hover:border-[#5d1021]/20 transition-colors group">

                    @if($field->type === 'checkbox')
                        {{-- Checkbox: label sadece yanında gösterilir, başlık tekrar etmez --}}
                        <label class="flex items-start gap-3 cursor-pointer select-none">
                            <input type="checkbox" name="field_{{ $field->id }}" value="1"
                                   class="mt-0.5 w-5 h-5 rounded border-slate-300 text-[#5d1021] focus:ring-[#5d1021]/20 transition-all shrink-0"
                                   {{ old('field_' . $field->id) ? 'checked' : '' }}
                                   {{ $field->is_required ? 'required' : '' }}>
                            <span class="text-sm font-semibold text-slate-700 leading-relaxed">
                                {{ $translatedLabel }}
                                @if($field->is_required)<span class="text-red-500 ml-0.5">*</span>@endif
                            </span>
                        </label>
                    @else
                        <label class="block text-sm font-bold text-slate-800 mb-3">
                            {{ $translatedLabel }}
                            @if($field->is_required)
                                <span class="text-red-500 ml-0.5">*</span>
                            @endif
                        </label>

                        @if($field->type === 'text')
                            @php 
                                $isStudentNo = str_contains(strtolower($field->label), 'numara') || str_contains(strtolower($field->label), 'no'); 
                                $defaultValue = old('field_' . $field->id);
                                
                                if (!$defaultValue && auth()->check()) {
                                    $lbl = mb_strtolower($field->label, 'UTF-8');
                                    if ((str_contains($lbl, 'öğrenci') || str_contains($lbl, 'ogrenci')) && auth()->user()->userEMailAddress) {
                                        $defaultValue = explode('@', auth()->user()->userEMailAddress)[0];
                                    } elseif (str_contains($lbl, 'ad') || str_contains($lbl, 'soyad')) {
                                        $defaultValue = auth()->user()->full_name ?? auth()->user()->name;
                                    } elseif (str_contains($lbl, 'fakülte') || str_contains($lbl, 'bölüm')) {
                                        $defaultValue = auth()->user()->userOfficeLocation;
                                    }
                                }
                            @endphp
                            <input type="{{ $isStudentNo ? 'number' : 'text' }}"
                                   name="field_{{ $field->id }}"
                                   value="{{ $defaultValue }}"
                                   placeholder="{{ $translatedPlaceholder ?? __('site.your_answer') }}"
                                   @if($isStudentNo) min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'')" @endif
                                   class="w-full bg-transparent border-0 border-b-2 border-slate-200 focus:border-[#5d1021] focus:ring-0 text-sm text-slate-700 px-0 py-2 placeholder-slate-400 transition-colors"
                                   {{ $field->is_required ? 'required' : '' }}>
                            @if($isStudentNo)
                                <p class="text-xs text-slate-400 mt-1">{{ __('site.only_numbers') }}</p>
                            @endif

                        @elseif($field->type === 'email')
                            @php
                                $defaultEmail = old('field_' . $field->id);
                                if (!$defaultEmail && auth()->check()) {
                                    $defaultEmail = auth()->user()->email ?? auth()->user()->userEMailAddress;
                                }
                            @endphp
                            <input type="email" name="field_{{ $field->id }}" value="{{ $defaultEmail }}"
                                   placeholder="{{ $translatedPlaceholder ?? 'ornek@email.com' }}"
                                   class="w-full bg-transparent border-0 border-b-2 border-slate-200 focus:border-[#5d1021] focus:ring-0 text-sm text-slate-700 px-0 py-2 placeholder-slate-400 transition-colors"
                                   {{ $field->is_required ? 'required' : '' }}>

                        @elseif($field->type === 'tel')
                            @php
                                $defaultPhone = old('field_' . $field->id);
                                if (!$defaultPhone && auth()->check() && auth()->user()->userTelephoneNumber) {
                                    $defaultPhone = auth()->user()->userTelephoneNumber;
                                }
                            @endphp
                            <input type="tel" name="field_{{ $field->id }}" value="{{ $defaultPhone }}"
                                   placeholder="{{ $translatedPlaceholder ?? '05XXXXXXXXX' }}"
                                   maxlength="11"
                                   pattern="[0-9]{10,11}"
                                   oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                   class="w-full bg-transparent border-0 border-b-2 border-slate-200 focus:border-[#5d1021] focus:ring-0 text-sm text-slate-700 px-0 py-2 placeholder-slate-400 transition-colors"
                                   {{ $field->is_required ? 'required' : '' }}>
                            <p class="text-xs text-slate-400 mt-1">{{ __('site.phone_format') }}</p>

                        @elseif($field->type === 'textarea')
                            <textarea name="field_{{ $field->id }}" rows="3"
                                      placeholder="{{ $translatedPlaceholder ?? __('site.your_answer') }}"
                                      class="w-full bg-transparent border-0 border-b-2 border-slate-200 focus:border-[#5d1021] focus:ring-0 text-sm text-slate-700 px-0 py-2 placeholder-slate-400 transition-colors resize-none"
                                      {{ $field->is_required ? 'required' : '' }}>{{ old('field_' . $field->id) }}</textarea>

                        @elseif($field->type === 'select')
                            <select name="field_{{ $field->id }}"
                                    class="w-full bg-white border border-slate-200 focus:border-[#5d1021] focus:ring-2 focus:ring-[#5d1021]/10 rounded-xl text-sm text-slate-700 px-4 py-2.5 transition-all"
                                    {{ $field->is_required ? 'required' : '' }}>
                                <option value="">{{ __('site.select_option') }}</option>
                                @if($field->options)
                                    @foreach($field->options as $option)
                                        <option value="{{ $option }}" {{ old('field_' . $field->id) === $option ? 'selected' : '' }}>{{ $option }}</option>
                                    @endforeach
                                @endif
                            </select>
                        @endif
                    @endif
                </div>
                @endforeach
            </div>

            {{-- Footer --}}
            <div class="px-8 py-5 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between gap-4 shrink-0">
                <button type="button" onclick="resetRegistrationForm()" class="text-[#5d1021] text-sm font-bold hover:underline transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">refresh</span>
                    {{ __('site.clear_form') }}
                </button>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="closeRegistrationModal()" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-100 transition-all active:scale-95">
                        {{ __('site.cancel') }}
                    </button>
                    <button type="submit" class="px-8 py-2.5 rounded-xl bg-[#5d1021] hover:bg-[#4a0c1a] text-white font-bold text-sm transition-all shadow-lg shadow-[#5d1021]/20 active:scale-95 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        {{ __('site.submit') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endauth

{{-- Lightbox Modal --}}
<div id="lightbox-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    {{-- Close Btn --}}
    <button onclick="closeLightbox()" class="absolute top-6 right-6 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-[110]">
        <span class="material-symbols-outlined text-[32px]">close</span>
    </button>
    
    {{-- Navigation --}}
    <button onclick="prevImage()" class="absolute left-6 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-[110]">
        <span class="material-symbols-outlined text-[40px]">chevron_left</span>
    </button>
    <button onclick="nextImage()" class="absolute right-6 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-[110]">
        <span class="material-symbols-outlined text-[40px]">chevron_right</span>
    </button>

    {{-- Image Container --}}
    <div class="relative w-full h-full flex items-center justify-center p-4 md:p-20">
        <img id="lightbox-img" src="" alt="Galeri Resim" class="max-w-full max-h-full object-contain shadow-2xl rounded-lg transform transition-transform duration-300 scale-95">
    </div>
    
    {{-- Counter --}}
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 bg-black/40 backdrop-blur-md px-6 py-2 rounded-full border border-white/10 text-white/80 font-bold text-sm tracking-wider">
        <span id="lightbox-counter-current">1</span> / <span id="lightbox-counter-total">1</span>
    </div>
</div>

@push('scripts')
<script>
    let currentImageIndex = 0;
    const galleryImages = [];
    
    document.addEventListener('DOMContentLoaded', function() {
        // Collect all images for lightbox
        document.querySelectorAll('.gallery-thumb').forEach((img, index) => {
            galleryImages.push(img.getAttribute('data-full'));
        });

        // Initialize all swipers with a small delay for stability
        setTimeout(function() {
            const commonOptions = {
                slidesPerView: 1,
                centeredSlides: false,
                loop: true,
                loopedSlides: 8,
                loopAdditionalSlides: 8,
                spaceBetween: 20,
                grabCursor: true,
                observer: true,
                observeParents: true,
                initialSlide: 0,
                roundLengths: true,
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20
                    },
                    768: { 
                        slidesPerView: 2,
                        spaceBetween: 30
                    }
                }
            };

            // Gallery
            if (document.querySelector('.gallery-slider')) {
                new Swiper('.gallery-slider', {
                    ...commonOptions,
                    navigation: {
                        nextEl: '.gallery-next',
                        prevEl: '.gallery-prev',
                    }
                });
            }

            // News
            if (document.querySelector('.club-news-swiper')) {
                new Swiper('.club-news-swiper', {
                    ...commonOptions,
                    navigation: {
                        nextEl: '.club-news-next',
                        prevEl: '.club-news-prev',
                    }
                });
            }

            // Events
            if (document.querySelector('.club-events-swiper')) {
                new Swiper('.club-events-swiper', {
                    ...commonOptions,
                    navigation: {
                        nextEl: '.club-events-next',
                        prevEl: '.club-events-prev',
                    }
                });
            }
        }, 300);
    });

    function openLightbox(index) {
        currentImageIndex = index;
        updateLightbox();
        const modal = document.getElementById('lightbox-modal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            document.getElementById('lightbox-img').classList.remove('scale-95');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const modal = document.getElementById('lightbox-modal');
        modal.classList.add('opacity-0');
        document.getElementById('lightbox-img').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }

    function updateLightbox() {
        const img = document.getElementById('lightbox-img');
        img.src = galleryImages[currentImageIndex];
        document.getElementById('lightbox-counter-current').innerText = currentImageIndex + 1;
        document.getElementById('lightbox-counter-total').innerText = galleryImages.length;
    }

    function nextImage() {
        currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
        updateLightbox();
    }

    function prevImage() {
        currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
        updateLightbox();
    }

    // Keyboard support
    document.addEventListener('keydown', (e) => {
        if (document.getElementById('lightbox-modal').classList.contains('hidden')) return;
        
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') prevImage();
        if (e.key === 'Escape') closeLightbox();
    });
    // ── Registration Modal ──
    function openRegistrationModal() {
        const modal = document.getElementById('registration-modal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeRegistrationModal() {
        const modal = document.getElementById('registration-modal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function resetRegistrationForm() {
        const form = document.getElementById('registration-form');
        if (form) form.reset();
    }

    // Validation hatası varsa modal'ı otomatik aç
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            openRegistrationModal();
        });
    @endif

</script>
@endpush

@endsection
