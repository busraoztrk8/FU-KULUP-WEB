@extends('layouts.app')
@section('title', __('site.university_name') . ' - ' . __('site.hero_title') . ' ' . __('site.hero_highlight'))
@section('data-page', 'home')
@section('content')

    <!-- Hero Section with Image Slider -->
    <section
        class="relative h-[480px] sm:h-[580px] md:h-[720px] flex items-center overflow-hidden shadow-2xl group">
        @if(isset($sliders) && $sliders->count() > 0)
                <div class="swiper hero-swiper h-full w-full absolute inset-0 z-0">
                    <div class="swiper-wrapper">
                        @foreach($sliders as $slider)
                            <div class="swiper-slide relative">
                                <img alt="{{ $slider->title ?? 'Fırat Üniversitesi' }}" class="w-full h-full object-cover brightness-[0.5] sm:brightness-[0.6] md:brightness-75" 
                                     src="{{ str_starts_with($slider->image_path, 'http') ? $slider->image_path : (file_exists(public_path('uploads/' . $slider->image_path)) ? asset('uploads/' . $slider->image_path) : asset('storage/' . $slider->image_path)) }}" />
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="container mx-auto px-4 sm:px-6 relative z-10 text-center">
                                        <div class="glass-card block w-[92%] sm:inline-block px-4 py-8 sm:px-6 sm:py-10 md:px-10 md:py-16 rounded-2xl md:rounded-[2rem] max-w-4xl mx-auto shadow-2xl">
                                            @if($slider->title)
                                                <h1 class="font-headline text-xl sm:text-3xl md:text-5xl lg:text-7xl font-extrabold tracking-tight mb-3 md:mb-6 leading-tight text-on-surface px-2 break-words">
                                                    {{ \Illuminate\Support\Str::words($slider->title, 10, '...') }}
                                                </h1>
                                            @endif
                                            @if($slider->subtitle)
                                                <p class="font-body text-xs sm:text-base md:text-xl text-on-surface-variant max-w-2xl mx-auto mb-4 md:mb-10 leading-relaxed px-4 break-words">
                                                    {{ \Illuminate\Support\Str::words($slider->subtitle, 15, '...') }}
                                                </p>
                                            @endif
                                            @if($slider->button_text && $slider->button_url)
                                                <div class="flex flex-col sm:flex-row items-center justify-center gap-2 md:gap-4">
                                                    <a href="{{ $slider->button_url }}" class="w-full sm:w-auto px-5 md:px-8 py-2.5 md:py-4 bg-gradient-primary text-white rounded-full font-bold text-xs md:text-lg hover:opacity-90 transition-all shadow-xl shadow-primary/30 text-center">
                                                        {{ $slider->button_text }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($sliders->count() > 1)
                        <!-- Navigation -->
                        <div class="swiper-button-next !text-white !right-1 sm:!right-4 !w-8 !h-8 sm:!w-12 sm:!h-12 !bg-black/30 hover:!bg-black/50 rounded-full backdrop-blur transition-all z-20 after:hidden flex items-center justify-center">
                            <span class="material-symbols-outlined !text-xl sm:!text-3xl">chevron_right</span>
                        </div>
                        <div class="swiper-button-prev !text-white !left-1 sm:!left-4 !w-8 !h-8 sm:!w-12 sm:!h-12 !bg-black/30 hover:!bg-black/50 rounded-full backdrop-blur transition-all z-20 after:hidden flex items-center justify-center">
                            <span class="material-symbols-outlined !text-xl sm:!text-3xl">chevron_left</span>
                        </div>
                        <!-- Pagination -->
                        <div class="swiper-pagination !bottom-4 sm:!bottom-6 swiper-pagination-white z-20 scale-75 sm:scale-100"></div>
                    @endif
                </div>
            @else
                <!-- Static Fallback Slider Content -->
                <div class="absolute inset-0 z-0">
                    <div class="hero-slider-item active h-full w-full">
                        <img alt="University Library" class="w-full h-full object-cover brightness-[0.5] sm:brightness-[0.6] md:brightness-75"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBGXyLOwSo27k1_6Ddg5vXCAGtTwpzk90fHHcbFmXnMq5aIBKeKGr0UhTzQH7c8B7Z-Zjlfpq2DTtT8Eh_VfP5YFf2SSNjtOZ_zO8ftmO4Mxzk8F_wwoHnZyFVDtTBk1jpkdPAVw_p1brUdDUJq8F8lyzl6Oy-KEqdeIOoESaDzEi0d0BRD8dGJERB0nFhYcoKDf6jcn9RAtpiE5DqymljGU-NTHD_fB9_BmG2PdLhPcVHkCNvum-DdK6erFUECPnPUAzpaw41m2hE" />
                    </div>
                </div>
                <div class="container mx-auto px-4 sm:px-6 relative z-10 text-center">
                    <div class="glass-card inline-block px-5 py-8 sm:px-6 sm:py-10 md:px-10 md:py-16 rounded-2xl md:rounded-[2rem] max-w-4xl mx-auto shadow-2xl">
                        <h1 class="font-headline text-2xl sm:text-3xl md:text-5xl lg:text-7xl font-extrabold tracking-tight mb-4 md:mb-6 leading-tight text-on-surface">
                            {{ __('site.hero_title') }} <span class="text-gradient">{{ __('site.hero_highlight') }}</span>
                        </h1>
                        <p class="font-body text-sm sm:text-base md:text-xl text-on-surface-variant max-w-2xl mx-auto mb-6 md:mb-10 leading-relaxed">
                            {{ __('site.hero_description') }}
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 md:gap-4">
                            <a href="{{ route('etkinlikler') }}"
                                class="w-full sm:w-auto px-6 md:px-8 py-3 md:py-4 bg-gradient-primary text-white rounded-full font-bold text-sm md:text-lg hover:opacity-90 transition-all shadow-xl shadow-primary/30 text-center">
                                {{ __('site.discover_events') }}
                            </a>
                            <a href="{{ route('kulupler') }}"
                                class="w-full sm:w-auto px-6 md:px-8 py-3 md:py-4 bg-white border border-outline-variant text-on-surface rounded-full font-bold text-sm md:text-lg hover:bg-surface-container transition-all text-center">
                                {{ __('site.discover_clubs') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
    </section>

    <!-- Stats Section -->
    <section class="py-10 md:py-12 border-b border-black/5 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 md:gap-12 text-center">
                <div class="p-4 md:p-6 rounded-2xl hover:bg-slate-50 transition-colors flex flex-col items-center justify-center h-full" data-animate>
                    <div class="text-4xl md:text-5xl font-headline font-extrabold text-gradient mb-1 flex items-center justify-center tabular-nums">
                        <span class="stat-counter" data-target="100">0</span><span>+</span>
                    </div>
                    <div class="text-lg md:text-xl font-bold text-on-surface mb-1">{{ __('site.club') }}</div>
                    <div class="text-on-surface-variant font-medium tracking-wide uppercase text-[10px] md:text-xs">{{ __('site.active_club') }}</div>
                </div>
                <div class="p-4 md:p-6 rounded-2xl hover:bg-slate-50 transition-colors flex flex-col items-center justify-center h-full" data-animate>
                    <div class="text-4xl md:text-5xl font-headline font-extrabold text-gradient mb-1 flex items-center justify-center tabular-nums">
                        <span class="stat-counter" data-target="10">0</span><span>K+</span>
                    </div>
                    <div class="text-lg md:text-xl font-bold text-on-surface mb-1">{{ __('site.participants') }}</div>
                    <div class="text-on-surface-variant font-medium tracking-wide uppercase text-[10px] md:text-xs">{{ __('site.registered') }}</div>
                </div>
                <div class="p-4 md:p-6 rounded-2xl hover:bg-slate-50 transition-colors flex flex-col items-center justify-center h-full" data-animate>
                    <div class="text-4xl md:text-5xl font-headline font-extrabold text-gradient mb-1 flex items-center justify-center tabular-nums">
                        <span class="stat-counter" data-target="50">0</span><span>+</span>
                    </div>
                    <div class="text-lg md:text-xl font-bold text-on-surface mb-1">{{ __('site.events') }}</div>
                    <div class="text-on-surface-variant font-medium tracking-wide uppercase text-[10px] md:text-xs">{{ __('site.total_events') }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trending Events Section - Single Row Carousel -->
    <section class="py-12 md:py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 md:mb-12 gap-6 sm:gap-4">
                <div class="w-full sm:w-auto">
                    <h2 class="text-2xl md:text-3xl font-headline font-bold mb-2 text-on-surface">{{ __('site.trending_events') }}</h2>
                    <div class="h-1.5 w-20 bg-primary rounded-full"></div>
                </div>
                <div class="flex items-center gap-3 self-end sm:self-auto">
                    <button class="events-swiper-prev w-10 h-10 md:w-12 md:h-12 rounded-full bg-white border border-black/10 shadow-md hover:shadow-lg flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary/30 transition-all">
                        <span class="material-symbols-outlined text-xl">chevron_left</span>
                    </button>
                    <button class="events-swiper-next w-10 h-10 md:w-12 md:h-12 rounded-full bg-white border border-black/10 shadow-md hover:shadow-lg flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary/30 transition-all">
                        <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </button>
                </div>
            </div>

            @if($trendingEvents->count() > 0)
            <div class="swiper events-swiper overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach($trendingEvents as $event)
                    <div class="swiper-slide !h-auto">
                        <a href="{{ route('etkinlik.detay', ['slug' => $event->slug]) }}"
                            class="bg-white rounded-2xl overflow-hidden group hover:shadow-2xl transition-all duration-300 cursor-pointer border border-black/5 block h-full flex flex-col">
                            <div class="p-4 md:p-6">
                                <div class="flex gap-3 md:gap-4 mb-3 md:mb-4">
                                    <div
                                        class="bg-surface-container rounded-lg p-2 md:p-3 text-center min-w-[45px] sm:min-w-[50px] md:min-w-[60px] flex flex-col justify-center items-center h-fit">
                                        <span class="text-[10px] sm:text-xs font-bold text-primary uppercase leading-none mb-1">{{ $event->start_time->translatedFormat('M') }}</span>
                                        <span class="text-lg sm:text-xl md:text-2xl font-bold text-on-surface leading-none">{{ $event->start_time->format('d') }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3
                                            class="text-sm sm:text-base md:text-lg font-headline font-bold mb-1 group-hover:text-primary transition-colors text-on-surface line-clamp-1 break-all">
                                            {{ $event->title }}</h3>
                                        <div class="flex items-center text-xs sm:text-sm text-on-surface-variant">
                                            <span class="material-symbols-outlined text-xs sm:text-sm mr-1">location_on</span>
                                            <span class="truncate">{{ $event->location ?? __('site.location') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex gap-2 mb-4 md:mb-6 flex-wrap">
                                    @if($event->category)
                                    <span
                                        class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider">{{ $event->category->name ?? __('site.event') }}</span>
                                    @endif
                                    @if($event->club)
                                    <span
                                        class="px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold uppercase tracking-wider">{{ $event->club->name ?? '' }}</span>
                                    @endif
                                </div>
                                @if($event->image)
                                    <img alt="{{ $event->title }}" class="aspect-stable-img rounded-xl"
                                        src="{{ str_starts_with($event->image, 'http') ? $event->image : (file_exists(public_path('uploads/' . $event->image)) ? asset('uploads/' . $event->image) : asset('storage/' . $event->image)) }}" />
                                @else
                                    <div class="aspect-stable-img bg-slate-100 flex items-center justify-center rounded-xl text-slate-300">
                                        <span class="material-symbols-outlined text-4xl">event</span>
                                    </div>
                                @endif
                                <div class="card-content mt-4">
                                    <h3 class="text-base md:text-lg font-headline font-bold mb-2 line-clamp-2 break-all">{{ $event->title }}</h3>
                                    <p class="text-on-surface-variant text-sm line-clamp-3 break-all">{{ $event->short_description ?? Str::limit(strip_tags($event->description), 120) }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="py-12 text-center text-slate-400">
                {{ __('site.no_events') }}
            </div>
            @endif
        </div>
    </section>

    <!-- Active Clubs Section -->
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="flex justify-between items-end mb-10 md:mb-16 gap-4">
                <div class="flex-1 text-center">
                    <h2 class="text-2xl md:text-4xl font-headline font-bold mb-4 text-on-surface">{{ __('site.active_clubs_title') }}</h2>
                    <p class="text-on-surface-variant max-w-xl mx-auto text-sm md:text-base">{{ __('site.active_clubs_desc') }}</p>
                </div>
                @if($activeClubs->count() > 1)
                <div class="flex items-center gap-2 shrink-0">
                    <button class="clubs-swiper-prev w-10 h-10 md:w-12 md:h-12 rounded-full bg-white border border-black/10 shadow-md hover:shadow-lg flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary/30 transition-all">
                        <span class="material-symbols-outlined text-xl">chevron_left</span>
                    </button>
                    <button class="clubs-swiper-next w-10 h-10 md:w-12 md:h-12 rounded-full bg-white border border-black/10 shadow-md hover:shadow-lg flex items-center justify-center text-on-surface-variant hover:text-primary hover:border-primary/30 transition-all">
                        <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </button>
                </div>
                @endif
            </div>
            @if($activeClubs->count() > 1)
            <div class="swiper active-clubs-swiper !pb-4">
                <div class="swiper-wrapper">
                    @forelse($activeClubs as $club)
                    <div class="swiper-slide !h-auto">
                        <a href="{{ route('kulup.detay', ['slug' => $club->slug]) }}"
                            class="group bg-surface-container-high rounded-2xl md:rounded-3xl overflow-hidden shadow-sm flex flex-col lg:flex-row hover:shadow-xl transition-all duration-500 block text-left h-full">
                            <div class="lg:w-1/2 relative overflow-hidden h-48 sm:h-64 lg:h-auto">
                                @if($club->logo)
                                    @php
                                        $logoPath = $club->logo;
                                        $logoUrl = str_starts_with($logoPath, 'http') ? $logoPath : (file_exists(public_path('uploads/' . $logoPath)) ? asset('uploads/' . $logoPath) : asset('storage/' . $logoPath));
                                    @endphp
                                    <img src="{{ $logoUrl }}" alt="{{ $club->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                                        <span class="material-symbols-outlined text-5xl">groups</span>
                                    </div>
                                @endif
                            </div>
                            <div class="lg:w-1/2 p-6 md:p-8 lg:p-10 flex flex-col justify-between card-content">
                                <div>
                                    <div class="flex items-center justify-between mb-3 md:mb-4">
                                        @if($club->category)
                                        <span class="px-3 py-1 bg-primary text-white rounded-full text-[10px] font-bold uppercase tracking-tighter">{{ $club->category->name ?? '' }}</span>
                                        @endif
                                        <span class="text-on-surface-variant text-sm flex items-center font-bold">
                                            <span class="material-symbols-outlined text-xs mr-1 text-primary">group</span> 
                                            {{ $club->total_active_member_count }} {{ __('site.members') }}
                                        </span>
                                    </div>
                                    @if($club->president)
                                    <div class="flex items-center gap-2 mb-3 text-sm text-on-surface-variant">
                                        <span class="material-symbols-outlined text-xs">person</span>
                                        <span>{{ __('site.name') }}: <span class="font-semibold text-on-surface">{{ $club->president->name }}</span></span>
                                    </div>
                                    @endif
                                    <h3 class="text-lg md:text-xl font-headline font-bold mb-2 md:mb-3 text-on-surface group-hover:text-primary transition-colors line-clamp-1">{{ $club->name }}</h3>
                                    
                                    <div class="mb-2">
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-primary/70 bg-primary/5 px-2 py-0.5 rounded border border-primary/10">
                                            {{ __('site.about_us') }}
                                        </span>
                                    </div>

                                    <p class="text-on-surface-variant text-sm leading-relaxed mb-4 md:mb-6 line-clamp-3 break-all">
                                        {{ Str::limit(strip_tags($club->description), 160) }}
                                    </p>
                                </div>
                                <div class="text-primary font-bold text-sm flex items-center gap-2 mt-auto">
                                    {{ __('site.view_details') }} <span class="material-symbols-outlined">arrow_right_alt</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="swiper-slide">
                        <div class="py-12 text-center text-slate-400">{{ __('site.no_events') }}</div>
                    </div>
                    @endforelse
                </div>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                @forelse($activeClubs as $club)
                <a href="{{ route('kulup.detay', ['slug' => $club->slug]) }}"
                    class="group bg-surface-container-high rounded-2xl md:rounded-3xl overflow-hidden shadow-sm flex flex-col lg:flex-row hover:shadow-xl transition-all duration-500 block text-left h-full">
                    <div class="lg:w-1/2 relative overflow-hidden h-48 sm:h-64 lg:h-auto">
                        @if($club->logo)
                            @php
                                $logoPath = $club->logo;
                                $logoUrl = str_starts_with($logoPath, 'http') ? $logoPath : (file_exists(public_path('uploads/' . $logoPath)) ? asset('uploads/' . $logoPath) : asset('storage/' . $logoPath));
                            @endphp
                            <img src="{{ $logoUrl }}" alt="{{ $club->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                                <span class="material-symbols-outlined text-5xl">groups</span>
                            </div>
                        @endif
                    </div>
                    <div class="lg:w-1/2 p-6 md:p-8 lg:p-10 flex flex-col justify-between card-content">
                        <div>
                            <div class="flex items-center justify-between mb-3 md:mb-4">
                                @if($club->category)
                                <span class="px-3 py-1 bg-primary text-white rounded-full text-[10px] font-bold uppercase tracking-tighter">{{ $club->category->name ?? '' }}</span>
                                @endif
                                <span class="text-on-surface-variant text-sm flex items-center"><span class="material-symbols-outlined text-xs mr-1">group</span> {{ $club->total_active_member_count }} {{ __('site.members') }}</span>
                            </div>
                            @if($club->president)
                            <div class="flex items-center gap-2 mb-3 text-sm text-on-surface-variant">
                                <span class="material-symbols-outlined text-xs">person</span>
                                <span>{{ __('site.president') }}: <span class="font-semibold text-on-surface">{{ $club->president->name }}</span></span>
                            </div>
                            @endif
                            <h3 class="text-lg md:text-xl font-headline font-bold mb-2 md:mb-3 text-on-surface group-hover:text-primary transition-colors line-clamp-1">{{ $club->name }}</h3>
                            
                            <div class="mb-2">
                                <span class="text-[10px] font-bold uppercase tracking-widest text-primary/70 bg-primary/5 px-2 py-0.5 rounded border border-primary/10">
                                    {{ __('site.about_us') }}
                                </span>
                            </div>

                            <p class="text-on-surface-variant text-sm leading-relaxed mb-4 md:mb-6 line-clamp-3 break-all">
                                {{ Str::limit(strip_tags($club->description), 160) }}
                            </p>
                        </div>
                        <div class="text-primary font-bold text-sm flex items-center gap-2 mt-auto">
                            {{ __('site.view_club') }} <span class="material-symbols-outlined">arrow_right_alt</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full py-12 text-center text-slate-400">{{ __('site.no_clubs') }}</div>
                @endforelse
            </div>
            @endif
        </div>
    </section>

    <!-- Campus Life Gallery Section -->
    <section class="py-12 md:py-16 px-4 sm:px-6 bg-slate-50 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="mb-10 md:mb-16">
                <h2 class="text-2xl md:text-4xl font-headline font-bold text-center mb-4 text-on-surface">{{ __('site.campus_gallery') }}</h2>
                <div class="h-1 w-20 bg-primary mx-auto rounded-full"></div>
            </div>
            <div class="swiper gallery-swiper overflow-hidden rounded-2xl md:rounded-3xl pb-12">
                <div class="swiper-wrapper flex items-start">
                    @php
                        $columns = $galleryImages->chunk(2);
                    @endphp
                    @forelse($columns as $index => $chunk)
                        <div class="swiper-slide !w-[240px] sm:!w-[280px] md:!w-[320px] transition-all duration-300">
                            <div class="flex flex-col gap-4 md:gap-6 {{ $index % 2 != 0 ? 'pt-8 md:pt-16' : '' }}">
                                @foreach($chunk as $imgIndex => $image)
                                    @php
                                        // "Stone-like" staggered heights
                                        $heightClass = ($index % 2 == 0) 
                                            ? ($imgIndex % 2 == 0 ? 'h-60 md:h-80' : 'h-48 md:h-64') 
                                            : ($imgIndex % 2 == 0 ? 'h-48 md:h-64' : 'h-60 md:h-80');
                                    @endphp
                                    <div class="relative group cursor-pointer {{ $heightClass }} overflow-hidden rounded-2xl md:rounded-3xl shadow-md hover:shadow-2xl transition-all duration-500 bg-slate-100">
                                            @php
                                                $gp = $image->image_path;
                                                $gUrl = str_starts_with($gp, 'http') ? $gp : (file_exists(public_path('uploads/' . $gp)) ? asset('uploads/' . $gp) : asset('storage/' . $gp));
                                            @endphp
                                            <img alt="{{ $image->title ?? 'Kampüs Yaşamı' }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                                src="{{ $gUrl }}" />
                                        <div class="absolute inset-0 pointer-events-none bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end p-4 md:p-6">
                                            <p class="text-white text-sm font-bold transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">{{ $image->title }}</p>
                                        </div>
                                        <div class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/30 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                            <span class="material-symbols-outlined text-white text-base">zoom_in</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="w-full py-12 text-center text-slate-400">
                            <p>{{ __('site.no_gallery') }}</p>
                        </div>
                    @endforelse
                </div>
                <!-- Only Pagination dots remain -->
                <div class="swiper-pagination !-bottom-1 !bottom-0"></div>
            </div>
        </div>
    </section>

    <!-- Success Stories Section -->
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <div class="mb-10 md:mb-16">
                <h2 class="text-2xl md:text-4xl font-headline font-bold text-center mb-4 text-on-surface">{{ __('site.platform_achievements') }}</h2>
                <p class="text-on-surface-variant text-center max-w-xl mx-auto text-sm md:text-base">{{ __('site.achievements_desc') }}</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <!-- Success Card 1 -->
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl border border-black/5 shadow-sm hover:shadow-lg transition-all text-center">
                    <div
                        class="w-14 h-14 md:w-16 md:h-16 bg-primary-container text-primary rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <span class="material-symbols-outlined text-2xl md:text-3xl">emoji_events</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2 md:mb-3">{{ __('site.achievement_robotics') }}</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">{{ __('site.achievement_robotics_desc') }}</p>
                </div>
                <!-- Success Card 2 -->
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl border border-black/5 shadow-sm hover:shadow-lg transition-all text-center">
                    <div
                        class="w-14 h-14 md:w-16 md:h-16 bg-primary-container text-primary rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <span class="material-symbols-outlined text-2xl md:text-3xl">campaign</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2 md:mb-3">{{ __('site.achievement_100events') }}</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">{{ __('site.achievement_100events_desc') }}</p>
                </div>
                <!-- Success Card 3 -->
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl border border-black/5 shadow-sm hover:shadow-lg transition-all text-center">
                    <div
                        class="w-14 h-14 md:w-16 md:h-16 bg-primary-container text-primary rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <span class="material-symbols-outlined text-2xl md:text-3xl">groups</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2 md:mb-3">{{ __('site.achievement_global') }}</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">{{ __('site.achievement_global_desc') }}</p>
                </div>
                <!-- Success Card 4 -->
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl border border-black/5 shadow-sm hover:shadow-lg transition-all text-center">
                    <div
                        class="w-14 h-14 md:w-16 md:h-16 bg-primary-container text-primary rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <span class="material-symbols-outlined text-2xl md:text-3xl">star</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2 md:mb-3">{{ __('site.achievement_community') }}</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">{{ __('site.achievement_community_desc') }}</p>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Hero Swiper
            if (document.querySelector('.hero-swiper')) {
                new Swiper('.hero-swiper', {
                    loop: true,
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    },
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                });
            }

            // Events Carousel Swiper
            if (document.querySelector('.events-swiper')) {
                const eventsSwiper = new Swiper('.events-swiper', {
                    slidesPerView: 1.1,
                    spaceBetween: 16,
                    grabCursor: true,
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 24,
                        },
                    },
                });

                const eventsPrev = document.querySelector('.events-swiper-prev');
                const eventsNext = document.querySelector('.events-swiper-next');
                if (eventsPrev) eventsPrev.addEventListener('click', () => eventsSwiper.slidePrev());
                if (eventsNext) eventsNext.addEventListener('click', () => eventsSwiper.slideNext());
            }

            // Active Clubs Swiper
            if (document.querySelector('.active-clubs-swiper')) {
                const clubsSwiper = new Swiper('.active-clubs-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    grabCursor: true,
                    loop: false,
                    autoplay: {
                        delay: 6000,
                        disableOnInteraction: false,
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 1.5,
                            spaceBetween: 30,
                        },
                        1024: {
                            slidesPerView: 2,
                            spaceBetween: 40,
                        },
                    },
                    on: {
                        init: function() {
                            // Swiper initialized
                        }
                    }
                });

                const prevBtn = document.querySelector('.clubs-swiper-prev');
                const nextBtn = document.querySelector('.clubs-swiper-next');
                if (prevBtn) prevBtn.addEventListener('click', () => clubsSwiper.slidePrev());
                if (nextBtn) nextBtn.addEventListener('click', () => clubsSwiper.slideNext());
            }
        });
    </script>
    @endpush
@endsection