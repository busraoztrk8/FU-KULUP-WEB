@extends('layouts.app')

@section('title', __('site.page_title_events') . ' - ' . __('site.university_name'))
@section('data-page', 'events')

@section('content')
@php
    $customTitle = \App\Models\SiteSetting::getVal('events_hero_title');
    $customSubtitle = \App\Models\SiteSetting::getVal('events_hero_subtitle');
    $customImage = \App\Models\SiteSetting::getVal('events_hero_image');
    
    $featured = $events->where('is_featured', true)->first() ?? $events->first();
    
    $heroTitle = $customTitle ?: ($featured ? $featured->title : __('site.events_page_hero_title'));
    $heroSubtitle = $customSubtitle ?: ($featured ? $featured->short_description : __('site.events_page_hero_desc'));
    
    if ($customImage) {
        $heroUrl = file_exists(public_path('uploads/' . $customImage)) ? asset('uploads/' . $customImage) : asset('storage/' . $customImage);
    } elseif ($featured && $featured->image) {
        $fPath = $featured->image;
        $heroUrl = str_starts_with($fPath, 'http') ? $fPath : (file_exists(public_path('uploads/' . $fPath)) ? asset('uploads/' . $fPath) : asset('storage/' . $fPath));
    } else {
        $heroUrl = null;
    }
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6">
    <!-- Hero Section -->
    <section class="@include('partials.site-hero-dimensions') overflow-hidden relative group">
        <div class="swiper hero-swiper absolute inset-0 w-full h-full">
            <div class="swiper-wrapper">
                @forelse($heroEvents as $heroEvent)
                    @php
                        $eImg = $heroEvent->image;
                        $eUrl = ($eImg && str_starts_with($eImg, 'http')) ? $eImg : ($eImg && file_exists(public_path('uploads/' . $eImg)) ? asset('uploads/' . $eImg) : ($eImg ? asset('storage/' . $eImg) : null));
                    @endphp
                    <div class="swiper-slide relative w-full h-full">
                        @if($eUrl)
                            <img alt="{{ $heroEvent->title }}" class="absolute inset-0 w-full h-full object-cover" src="{{ $eUrl }}" />
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-dark"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-r from-black/70 md:from-black/60 via-black/25 to-transparent"></div>
                        
                        <div class="absolute inset-0 flex items-center">
                            <div class="relative z-10 w-full max-w-4xl px-4 sm:px-6 md:px-20 text-center md:text-left">
                                <!-- Sabit Boyutlu İçerik Kutusu -->
                                <div class="min-h-[250px] md:min-h-[350px] flex flex-col justify-center">
                                    <div class="inline-flex items-center self-center md:self-start mb-4">
                                        <span class="bg-primary text-white px-3 py-1 rounded-lg text-[10px] md:text-xs font-bold uppercase tracking-widest shadow-lg shadow-primary/20">
                                            {{ __('site.upcoming_event') }}
                                        </span>
                                    </div>
                                    
                                    <h1 class="text-2xl sm:text-4xl md:text-6xl font-bold font-headline text-white mb-4 md:mb-6 leading-[1.1] tracking-tight uppercase drop-shadow-lg line-clamp-2">
                                        {{ $heroEvent->title }}
                                    </h1>
                                    
                                    <p class="text-sm sm:text-lg md:text-xl text-white/90 mb-8 md:mb-10 max-w-2xl font-body leading-relaxed mx-auto md:mx-0 line-clamp-3 md:line-clamp-4 drop-shadow-md">
                                        {{ $heroEvent->short_description ?? Str::limit(strip_tags($heroEvent->description), 200) }}
                                    </p>
                                    
                                    <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-3 md:gap-5">
                                        <a href="{{ route('etkinlik.detay', $heroEvent->slug) }}"
                                            class="bg-primary hover:bg-primary-dark text-white px-6 py-3 md:px-10 md:py-4 rounded-full font-bold transition-all flex justify-center items-center gap-2 group/btn text-xs md:text-base shadow-xl shadow-primary/30">
                                            {{ __('site.view_details') }}
                                            <span class="material-symbols-outlined text-sm md:text-base group-hover/btn:translate-x-1 transition-transform">arrow_forward</span>
                                        </a>
                                        <a href="{{ route('kulup.detay', $heroEvent->club->slug) }}"
                                            class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/20 px-6 py-3 md:px-10 md:py-4 rounded-full font-bold transition-all text-xs md:text-base text-center">
                                            {{ __('site.event_host') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="swiper-slide relative w-full h-full">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-dark"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <h1 class="text-white text-3xl font-bold">{{ __('site.no_events_found') }}</h1>
                        </div>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            <div class="swiper-pagination hero-pagination !bottom-8 !left-20 !justify-start !w-auto"></div>
            
            <!-- Navigation -->
            <div class="hidden md:flex absolute right-10 bottom-10 z-20 gap-3">
                <button class="hero-prev w-12 h-12 rounded-full border border-white/20 bg-black/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-primary hover:border-primary transition-all group/nav">
                    <span class="material-symbols-outlined group-hover:-translate-x-1 transition-transform">chevron_left</span>
                </button>
                <button class="hero-next w-12 h-12 rounded-full border border-white/20 bg-black/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-primary hover:border-primary transition-all group/nav">
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">chevron_right</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 pt-6 pb-12 md:py-16">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-4 md:mb-12 gap-3 md:gap-6">
            <div>
                <h2 class="text-base sm:text-2xl md:text-3xl font-bold font-headline text-on-background mb-1 md:mb-2">{{ __('site.upcoming_events') }}</h2>
                <p class="text-on-surface-variant text-xs md:text-base">{{ __('site.upcoming_events_desc') }}</p>
            </div>

            <!-- Tab Toggle -->
            <div class="bg-slate-100 p-1 rounded-full flex gap-1 shadow-sm w-full md:w-auto">
                <button
                    class="flex-1 md:flex-none px-4 md:px-8 py-1.5 md:py-2.5 rounded-full bg-primary text-white font-bold text-[11px] md:text-sm transition-all shadow-md tab-btn active"
                    data-tab-btn="calendar">{{ __('site.calendar_view') }}</button>
                <button
                    class="flex-1 md:flex-none px-4 md:px-8 py-1.5 md:py-2.5 rounded-full text-slate-600 hover:text-on-background font-medium text-[11px] md:text-sm transition-all tab-btn"
                    data-tab-btn="all">{{ __('site.all') }}</button>
            </div>
        </div>

        <!-- Tab Content 1: Calendar View — sol ~1/3 (takvim+kategoriler tek kart), sağ ~2/3; üstten hizalı -->
        <div id="calendar-view" class="tab-content active grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 lg:gap-10 lg:items-stretch"
            data-tab-content="calendar">
@php
    \Carbon\Carbon::setLocale(app()->getLocale());
    $currentDateStr = request('date', now()->format('Y-m-d'));
    $currentDate = \Carbon\Carbon::parse($currentDateStr);
    $year = $currentDate->year;
    $month = $currentDate->month;
    $startOfMonth = \Carbon\Carbon::createFromDate($year, $month, 1);
    $endOfMonth = $startOfMonth->copy()->endOfMonth();
    
    $startDayOfWeek = $startOfMonth->dayOfWeekIso;
    
    // Group events by date part
    $eventsByDate = isset($events) ? $events->groupBy(function($e) {
        return \Carbon\Carbon::parse($e->start_time)->format('Y-m-d');
    }) : collect();
    
    $selectedEvents = $eventsByDate->get($currentDateStr, collect());
@endphp
            <!-- Calendar + Kategoriler (sol kart — sağ kart ile aynı çerçeve) -->
            <div class="lg:col-span-5 w-full bg-white rounded-2xl md:rounded-[2rem] p-3 sm:p-5 md:p-6 border border-slate-100 shadow-sm flex flex-col min-h-0 lg:h-full">
                <div id="calendar-month-container">
                    @include('partials.calendar-month', ['currentDateStr' => $currentDateStr, 'events' => $events])
                </div>
                <div class="mt-3 md:mt-6 pt-3 md:pt-6 border-t border-slate-200">
                    <h4 class="text-xs md:text-sm font-bold mb-2 md:mb-3 flex items-center gap-2 text-on-background">
                        <span class="material-symbols-outlined text-primary text-xs md:text-sm"
                            style="font-variation-settings: 'FILL' 1;">category</span>
                        {{ __('site.categories') }}
                    </h4>
                    <ul class="space-y-1.5 md:space-y-3 lg:grid lg:grid-cols-2 lg:space-y-0 lg:gap-x-3 lg:gap-y-2 max-h-[180px] overflow-y-auto custom-scrollbar pr-2">
                        @php
                            $fallbackDots = ['#5d1021', '#2563eb', '#ca8a04', '#16a34a', '#9333ea', '#db2777'];
                        @endphp
                        @foreach(\App\Models\Category::take(10)->get() as $idx => $cat)
                        <li class="flex items-center text-xs md:text-sm gap-2 min-w-0">
                            @php
                                $dotColor = $cat->color;
                                $isHex = $dotColor && preg_match('/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/', trim($dotColor ?? ''));
                            @endphp
                            <span class="w-1.5 md:w-2 h-1.5 md:h-2 rounded-full shrink-0 ring-1 ring-black/5 {{ $dotColor && !$isHex ? $dotColor : '' }}"
                                @if($isHex)
                                    style="background-color: {{ trim($dotColor) }};"
                                @elseif(!$dotColor)
                                    style="background-color: {{ $fallbackDots[$idx % count($fallbackDots)] }};"
                                @endif
                            ></span>
                            <span class="text-on-surface-variant truncate" title="{{ $cat->name }}">{{ $cat->name }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Etkinlik alanı — sol takvim kartı ile aynı padding/köşe/gölge (eşit hiza) -->
            <div class="lg:col-span-7 min-w-0 w-full max-w-full bg-white rounded-2xl md:rounded-[2rem] p-3 sm:p-5 md:p-6 border border-slate-100 shadow-sm flex flex-col min-h-0 lg:h-full overflow-hidden">
                <div class="flex items-center justify-between gap-3 mb-3 md:mb-6 shrink-0">
                    <h3 id="selected-date-title" class="font-headline font-bold text-sm md:text-xl text-on-background capitalize">
                        {{ app()->getLocale() == 'en' ? $currentDate->format('F j, l') : $currentDate->translatedFormat('j F, l') }}
                    </h3>
                    <span id="event-count-badge" class="text-on-surface-variant text-[10px] md:text-sm whitespace-nowrap">
                        {{ __('site.events_found', ['count' => $selectedEvents->count()]) }}
                    </span>
                </div>
                <div id="event-list-container" class="transition-all duration-500 min-w-0 max-w-full flex-1 min-h-0 flex flex-col overflow-x-hidden">
                    @include('partials.unified-event-card', ['selectedEvents' => $selectedEvents, 'date' => $currentDateStr])
                </div>
            </div>
        </div>

        <!-- Tab Content 2: Grid View -->
        <div id="grid-view" class="tab-content grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 min-h-[400px]"
            data-tab-content="all" style="display: none;">
            <div id="events-grid-container" class="contents">
                @include('partials.event-grid-items', ['events' => $gridEvents->take(6)])
                
                @if($gridEvents->count() > 6)
                <div id="extra-grid-events" class="contents" style="display: none;">
                    @include('partials.event-grid-items', ['events' => $gridEvents->skip(6)])
                </div>
                @endif
            </div>
        </div>

        <!-- More Button - Show more then Redirect -->
        @if($totalPublishedEvents > 6)
        <div id="load-more-container" class="mt-6 md:mt-16 flex justify-center" style="display: none;">
            <button id="load-more-grid-btn"
                class="bg-white hover:bg-slate-50 text-primary px-6 md:px-10 py-2.5 md:py-4 rounded-full font-bold transition-all border border-primary/20 flex items-center gap-2 shadow-lg hover:shadow-xl text-xs md:text-base group">
                <span id="load-more-text">{{ __('site.see_more') }}</span>
                <span class="material-symbols-outlined text-xs md:text-sm group-hover:translate-x-1 transition-transform">expand_more</span>
            </button>
        </div>
        @endif
    </section>
</div>

<script>
        function toggleAdditionalEvents(btn) {
            const container = btn.parentElement.querySelector('.additional-events');
            if (container.style.display === 'none' || container.style.display === '') {
                container.style.display = 'flex';
                btn.innerHTML = '{{ __('site.see_less') }} <span class="material-symbols-outlined text-sm">expand_less</span>';
            } else {
                container.style.display = 'none';
                btn.innerHTML = '{{ __('site.see_other_events') }} <span class="material-symbols-outlined text-sm">expand_more</span>';
                btn.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        let unifiedSwiper;
        let heroSwiper;

        function initSwipers() {
            // Hero Swiper
            const heroEl = document.querySelector('.hero-swiper');
            if (heroEl) {
                heroSwiper = new Swiper(heroEl, {
                    slidesPerView: 1,
                    spaceBetween: 0,
                    loop: true,
                    effect: 'fade',
                    fadeEffect: { crossFade: true },
                    autoplay: {
                        delay: 7000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.hero-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.hero-next',
                        prevEl: '.hero-prev',
                    },
                });
            }

            // Unified Swiper
            const el = document.querySelector('.unified-events-swiper');
            if (!el) {
                if (unifiedSwiper) {
                    unifiedSwiper.destroy(true, true);
                    unifiedSwiper = null;
                }
                return;
            }
            if (unifiedSwiper) {
                unifiedSwiper.destroy(true, true);
                unifiedSwiper = null;
            }
            const prevEl = el.querySelector('.unified-events-prev');
            const nextEl = el.querySelector('.unified-events-next');

            unifiedSwiper = new Swiper(el, {
                slidesPerView: 1,
                spaceBetween: 24,
                loop: false,
                rewind: true,
                grabCursor: true,
                watchOverflow: true,
                autoHeight: false, 
                autoplay: {
                    delay: 5000, 
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                keyboard: {
                    enabled: true,
                    onlyInViewport: true,
                },
                navigation: (prevEl && nextEl) ? { prevEl, nextEl } : undefined,
            });
            unifiedSwiper.on('slideChangeTransitionEnd', function () {
                this.updateAutoHeight(0);
            });
            requestAnimationFrame(function() {
                if (unifiedSwiper) {
                    unifiedSwiper.update();
                    unifiedSwiper.updateAutoHeight(0);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initSwipers();
        });

        function changeMonth(dateStr) {
            const container = document.getElementById('calendar-month-container');
            container.style.opacity = '0.5';
            
            fetch(`{{ route('etkinlikler') }}?date=${dateStr}&view_type=calendar_month`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                container.innerHTML = data.html;
                container.style.opacity = '1';
                
                // Fetch events for the selected date in the new month
                const newSelectedDate = document.querySelector('.calendar-day.active');
                if(newSelectedDate) {
                    filterByDate(newSelectedDate.dataset.date, newSelectedDate);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.style.opacity = '1';
            });
        }

        function filterByDate(date, element) {
            // UI Update
            document.querySelectorAll('.calendar-day').forEach(el => {
                el.classList.remove('active', 'bg-primary', 'text-white', 'shadow-lg', 'ring-2', 'md:ring-4', 'ring-primary/10');
                el.classList.add('text-slate-600');
                const dot = el.querySelector('.event-dot');
                if(dot) dot.classList.replace('bg-white', 'bg-primary');
            });
            
            element.classList.add('active', 'bg-primary', 'text-white', 'shadow-lg', 'ring-2', 'md:ring-4', 'ring-primary/10');
            element.classList.remove('text-slate-600');
            const activeDot = element.querySelector('.event-dot');
            if(activeDot) activeDot.classList.replace('bg-primary', 'bg-white');

            const dateObj = new Date(date);
            const options = { day: 'numeric', month: 'long', weekday: 'long' };
            const locale = '{{ app()->getLocale() == 'en' ? 'en-US' : 'tr-TR' }}';
            document.getElementById('selected-date-title').innerText = dateObj.toLocaleDateString(locale, options);

            const eventListContainer = document.getElementById('event-list-container');
            
            eventListContainer.style.opacity = '0';
            setTimeout(() => {
                fetch(`/etkinlikler?date=${date}&view_type=calendar_list`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.json())
                .then(data => {
                    eventListContainer.innerHTML = data.html;
                    
                    // Update title and count (data-event-count avoids false matches on .group)
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.html;
                    
                    // Re-initialize sliders
                    initSwipers();
                    
                    eventListContainer.style.opacity = '1';
                    
                    const countRoot = tempDiv.querySelector('.unified-event-list-root');
                    const count = countRoot ? parseInt(countRoot.getAttribute('data-event-count') || '0', 10) : tempDiv.querySelectorAll('.swiper-slide').length;
                    const countText = '{{ __('site.events_found', ['count' => ':count']) }}'.replace(':count', count);
                    document.getElementById('event-count-badge').innerText = countText;
                });
            }, 300);
        }

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.getAttribute('data-tab-btn');
                
                // Hide/Show Load More button
                const loadMore = document.getElementById('load-more-container');
                if (loadMore) {
                    if (target === 'calendar') {
                        loadMore.style.display = 'none';
                    } else {
                        loadMore.style.display = 'flex';
                    }
                }

                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.classList.remove('active', 'bg-primary', 'text-white', 'shadow-md');
                    b.classList.add('text-slate-600', 'font-medium');
                });
                btn.classList.add('active', 'bg-primary', 'text-white', 'shadow-md');
                btn.classList.remove('text-slate-600', 'font-medium');

                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                    if(content.getAttribute('data-tab-content') === target) {
                        content.classList.add('active');
                    }
                });
                if (target === 'calendar') {
                    setTimeout(function() { initSwipers(); }, 50);
                }
            });
        });

        // Grid Load More Logic
        const loadMoreBtn = document.getElementById('load-more-grid-btn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                const extraEvents = document.getElementById('extra-grid-events');
                if (extraEvents && extraEvents.style.display === 'none') {
                    // Step 1: Show up to 15 events
                    extraEvents.style.display = 'contents';
                    document.getElementById('load-more-text').innerText = '{{ __('site.see_all_events') }}';
                    loadMoreBtn.querySelector('.material-symbols-outlined').innerText = 'arrow_forward';
                } else {
                    // Step 2: Redirect to all events page
                    window.location.href = '{{ route('tum-etkinlikler') }}';
                }
            });
        }

    </script>
@endsection