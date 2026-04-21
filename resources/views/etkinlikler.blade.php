@extends('layouts.app')

@section('title', 'Etkinlikler - Fırat Üniversitesi')
@section('data-page', 'events')

@section('content')
@php
    $customTitle = \App\Models\SiteSetting::getVal('events_hero_title');
    $customSubtitle = \App\Models\SiteSetting::getVal('events_hero_subtitle');
    $customImage = \App\Models\SiteSetting::getVal('events_hero_image');
    
    $featured = $events->where('is_featured', true)->first() ?? $events->first();
    
    $heroTitle = $customTitle ?: ($featured ? $featured->title : 'Etkinlikler');
    $heroSubtitle = $customSubtitle ?: ($featured ? $featured->short_description : 'Kampüsümüzdeki en güncel etkinlikler...');
    
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
    <section class="@include('partials.site-hero-dimensions')">
        @if($heroUrl)
            <img alt="{{ $heroTitle }}" class="absolute inset-0 w-full h-full object-cover" src="{{ $heroUrl }}" />
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-dark"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 md:from-black/60 via-black/25 to-transparent"></div>
        <div class="relative z-10 max-w-3xl px-4 sm:px-6 md:px-20 text-center md:text-left">
            @if(!$customTitle && $featured && $featured->is_featured)
            <span class="bg-primary text-white px-2.5 md:px-4 py-0.5 md:py-1 rounded-full text-[8px] md:text-[10px] font-bold mb-2 md:mb-4 inline-block uppercase tracking-widest">
                Ana Etkinlik
            </span>
            @endif
            <h1 class="text-lg sm:text-3xl md:text-5xl lg:text-5xl font-bold font-headline text-white mb-2 md:mb-6 leading-tight tracking-tight uppercase">
                {{ $heroTitle }}
            </h1>
            @if($heroSubtitle)
            <p class="text-xs sm:text-base md:text-xl text-white mb-4 md:mb-10 max-w-xl font-body leading-relaxed mx-auto md:mx-0">
                {{ $heroSubtitle }}
            </p>
            @endif
            
            @if(!$customTitle && $featured)
            <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-2 md:gap-4">
                <a href="{{ route('kulup.detay', $featured->club->slug) }}"
                    class="bg-primary hover:bg-primary-dark text-white px-4 md:px-8 py-2 md:py-4 rounded-full font-bold transition-all flex justify-center items-center gap-2 group text-[11px] md:text-base">
                    Kulübe Üye Ol
                    <span class="material-symbols-outlined text-sm md:text-base group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
                <a href="{{ route('etkinlik.detay', $featured->slug) }}"
                    class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white px-4 md:px-8 py-2 md:py-4 rounded-full font-bold transition-all text-[11px] md:text-base text-center">
                    Detayları Gör
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 pt-6 pb-12 md:py-16">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-4 md:mb-12 gap-3 md:gap-6">
            <div>
                <h2 class="text-base sm:text-2xl md:text-3xl font-bold font-headline text-on-background mb-1 md:mb-2">Yaklaşan Etkinlikler</h2>
                <p class="text-on-surface-variant text-xs md:text-base">Kampüs hayatındaki en güncel akademik ve sosyal etkinlikleri
                    takip edin.</p>
            </div>

            <!-- Tab Toggle -->
            <div class="bg-slate-100 p-1 rounded-full flex gap-1 shadow-sm w-full md:w-auto">
                <button
                    class="flex-1 md:flex-none px-4 md:px-8 py-1.5 md:py-2.5 rounded-full bg-primary text-white font-bold text-[11px] md:text-sm transition-all shadow-md tab-btn active"
                    data-tab-btn="calendar">Takvim</button>
                <button
                    class="flex-1 md:flex-none px-4 md:px-8 py-1.5 md:py-2.5 rounded-full text-slate-600 hover:text-on-background font-medium text-[11px] md:text-sm transition-all tab-btn"
                    data-tab-btn="all">Tümü</button>
            </div>
        </div>

        <!-- Tab Content 1: Calendar View — sol ~1/3 (takvim+kategoriler tek kart), sağ ~2/3; üstten hizalı -->
        <div id="calendar-view" class="tab-content active grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 lg:gap-10 lg:items-stretch"
            data-tab-content="calendar">
@php
    \Carbon\Carbon::setLocale('tr');
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
                <div class="flex justify-between items-center mb-3 md:mb-6">
                    <h3 class="font-headline font-bold text-sm md:text-xl text-on-background capitalize">{{ $startOfMonth->translatedFormat('F Y') }}</h3>
                    <div class="flex gap-2">
                        <a href="?date={{ $startOfMonth->copy()->subMonth()->format('Y-m-d') }}#calendar-view" class="p-1.5 md:p-2 rounded-lg hover:bg-white text-on-surface-variant"><span
                                class="material-symbols-outlined">chevron_left</span></a>
                        <a href="?date={{ $startOfMonth->copy()->addMonth()->format('Y-m-d') }}#calendar-view" class="p-1.5 md:p-2 rounded-lg hover:bg-white text-on-surface-variant"><span
                                class="material-symbols-outlined">chevron_right</span></a>
                    </div>
                </div>
                <div class="grid grid-cols-7 gap-y-3 md:gap-y-4 text-center mb-4">
                    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">PT</div>
                    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">SA</div>
                    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">ÇA</div>
                    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">PE</div>
                    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">CU</div>
                    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60 text-primary">CT</div>
                    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60 text-primary">PA</div>
                    
                    @for($i = 1; $i < $startDayOfWeek; $i++)
                        <div class="p-1.5 md:p-2 text-xs md:text-sm text-slate-300">
                            {{ $startOfMonth->copy()->subDays($startDayOfWeek - $i)->day }}
                        </div>
                    @endfor
                    
                    @for($day = 1; $day <= $endOfMonth->day; $day++)
                        @php
                            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                            $hasEvents = $eventsByDate->has($dateStr);
                            $isSelected = $dateStr === $currentDateStr;
                            $isToday = $dateStr === now()->format('Y-m-d');
                        @endphp
                        
                        <a href="javascript:void(0)" 
                           onclick="filterByDate('{{ $dateStr }}', this)"
                           data-date="{{ $dateStr }}"
                           class="calendar-day p-1.5 md:p-2 text-xs md:text-sm {{ $isSelected ? 'active bg-primary text-white shadow-lg ring-2 md:ring-4 ring-primary/10' : ($hasEvents ? 'font-bold text-primary bg-primary/10' : ($isToday ? 'text-primary font-bold' : 'text-slate-600')) }} relative block hover:scale-105 rounded-lg md:rounded-xl transition-all">
                            {{ $day }}
                            @if($hasEvents)
                                <span class="absolute bottom-0.5 md:bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 {{ $isSelected ? 'bg-white' : 'bg-primary' }} rounded-full event-dot"></span>
                            @endif
                        </a>
                    @endfor
                    
                    @php $endDayOfWeek = $endOfMonth->dayOfWeekIso; @endphp
                    @if($endDayOfWeek < 7)
                        @for($i = 1; $i <= (7 - $endDayOfWeek); $i++)
                            <div class="p-1.5 md:p-2 text-xs md:text-sm text-slate-300">
                                {{ $endOfMonth->copy()->addDays($i)->day }}
                            </div>
                        @endfor
                    @endif
                </div>
                <div class="mt-3 md:mt-6 pt-3 md:pt-6 border-t border-slate-200">
                    <h4 class="text-xs md:text-sm font-bold mb-2 md:mb-3 flex items-center gap-2 text-on-background">
                        <span class="material-symbols-outlined text-primary text-xs md:text-sm"
                            style="font-variation-settings: 'FILL' 1;">category</span>
                        Kategoriler
                    </h4>
                    <ul class="space-y-1.5 md:space-y-3 lg:grid lg:grid-cols-2 lg:space-y-0 lg:gap-x-3 lg:gap-y-2">
                        @php
                            $fallbackDots = ['#5d1021', '#2563eb', '#ca8a04', '#16a34a', '#9333ea', '#db2777'];
                        @endphp
                        @foreach(\App\Models\Category::take(6)->get() as $idx => $cat)
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
                        {{ $currentDate->translatedFormat('j F, l') }}
                    </h3>
                    <span id="event-count-badge" class="text-on-surface-variant text-[10px] md:text-sm whitespace-nowrap">
                        {{ $selectedEvents->count() }} Etkinlik Bulundu
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
                @include('partials.event-grid-items', ['events' => $gridEvents])
            </div>
        </div>

        <!-- More Button - Redirect to Tüm Etkinlikler page -->
        @if($totalPublishedEvents > 10)
        <div id="load-more-container" class="mt-6 md:mt-16 flex justify-center" style="display: none;">
            <a href="{{ route('tum-etkinlikler') }}"
                class="bg-white hover:bg-slate-50 text-primary px-6 md:px-10 py-2.5 md:py-4 rounded-full font-bold transition-all border border-primary/20 flex items-center gap-2 shadow-lg hover:shadow-xl text-xs md:text-base group">
                <span>Tüm Etkinlikleri Gör</span>
                <span class="material-symbols-outlined text-xs md:text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
        </div>
        @endif
    </section>
</div>

<script>
        function toggleAdditionalEvents(btn) {
            const container = btn.parentElement.querySelector('.additional-events');
            if (container.style.display === 'none' || container.style.display === '') {
                container.style.display = 'flex';
                btn.innerHTML = 'Daha Az Gör <span class="material-symbols-outlined text-sm">expand_less</span>';
            } else {
                container.style.display = 'none';
                btn.innerHTML = 'Diğer Etkinlikleri Gör <span class="material-symbols-outlined text-sm">expand_more</span>';
                btn.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        let unifiedSwiper;

        function initUnifiedSwiper() {
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
            const paginationEl = el.querySelector('.unified-pagination');

            unifiedSwiper = new Swiper(el, {
                slidesPerView: 1,
                spaceBetween: 24,
                loop: false,
                rewind: true,
                grabCursor: true,
                watchOverflow: true,
                autoHeight: false, // Stretch to match container
                autoplay: {
                    delay: 5000, // 5 seconds
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
            initUnifiedSwiper();
        });

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
            document.getElementById('selected-date-title').innerText = dateObj.toLocaleDateString('tr-TR', options);

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
                    
                    // Re-initialize slider
                    initUnifiedSwiper();
                    
                    eventListContainer.style.opacity = '1';
                    
                    const countRoot = tempDiv.querySelector('.unified-event-list-root');
                    const count = countRoot ? parseInt(countRoot.getAttribute('data-event-count') || '0', 10) : tempDiv.querySelectorAll('.swiper-slide').length;
                    document.getElementById('event-count-badge').innerText = `${count} Etkinlik Bulundu`;
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
                    setTimeout(function() { initUnifiedSwiper(); }, 50);
                }
            });
        });

    </script>
@endsection