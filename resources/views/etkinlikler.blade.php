@extends('layouts.app')

@section('title', 'Etkinlikler - Fırat Üniversitesi')
@section('data-page', 'events')

@section('content')
@php $featured = $events->where('is_featured', true)->first() ?? $events->first(); @endphp

    <!-- Hero Section -->
    <section class="relative h-[280px] sm:h-[420px] md:h-[500px] lg:h-[600px] flex items-center overflow-hidden mx-3 sm:mx-4 md:mx-8 mt-4 rounded-2xl md:rounded-3xl shadow-xl">
        @if($featured && $featured->image)
            @php
                $fPath = $featured->image;
                $fUrl = str_starts_with($fPath, 'http') ? $fPath : (file_exists(public_path('uploads/' . $fPath)) ? asset('uploads/' . $fPath) : asset('storage/' . $fPath));
            @endphp
            <img alt="{{ $featured->title }}" class="absolute inset-0 w-full h-full object-cover"
                src="{{ $fUrl }}" />
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-dark"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-r from-black/90 md:from-black/80 via-black/40 to-transparent"></div>
        <div class="relative z-10 max-w-3xl px-4 sm:px-6 md:px-20 text-center md:text-left">
            <span class="bg-primary text-white px-2.5 md:px-4 py-0.5 md:py-1 rounded-full text-[8px] md:text-[10px] font-bold mb-2 md:mb-4 inline-block uppercase tracking-widest">
                Ana Etkinlik
            </span>
            @if($featured)
            <h1 class="text-lg sm:text-3xl md:text-5xl lg:text-7xl font-bold font-headline text-white mb-2 md:mb-6 leading-tight tracking-tight uppercase">
                {{ $featured->title }}
            </h1>
            @if($featured->short_description)
            <p class="text-xs sm:text-base md:text-xl text-slate-200 mb-4 md:mb-10 max-w-xl font-body leading-relaxed mx-auto md:mx-0">
                {{ $featured->short_description }}
            </p>
            @endif
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
            @else
            <h1 class="text-3xl md:text-5xl font-bold font-headline text-white mb-4">Etkinlikler</h1>
            @endif
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 pt-6 pb-12 md:py-16">
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

        <!-- Tab Content 1: Calendar View (Default) -->
        <div id="calendar-view" class="tab-content active grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-10"
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
            <!-- Calendar Side -->
            <div class="lg:col-span-1 bg-slate-50 rounded-2xl md:rounded-[2rem] p-3 sm:p-6 md:p-8 border border-slate-100 h-fit">
                <div class="flex justify-between items-center mb-4 md:mb-8">
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
                <div class="mt-4 md:mt-8 pt-4 md:pt-8 border-t border-slate-200">
                    <h4 class="text-xs md:text-sm font-bold mb-2 md:mb-4 flex items-center gap-2 text-on-background">
                        <span class="material-symbols-outlined text-primary text-xs md:text-sm"
                            style="font-variation-settings: 'FILL' 1;">category</span>
                        Kategoriler
                    </h4>
                    <ul class="space-y-1.5 md:space-y-3 lg:grid lg:grid-cols-2 lg:space-y-0 lg:gap-2">
                        @foreach(\App\Models\Category::take(6)->get() as $cat)
                        <li class="flex items-center text-xs md:text-sm gap-2">
                            <span class="w-1.5 md:w-2 h-1.5 md:h-2 rounded-full bg-primary/80"></span>
                            <span class="text-on-surface-variant truncate" title="{{ $cat->name }}">{{ $cat->name }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Event List Side -->
            <div class="lg:col-span-1 space-y-4 md:space-y-6 mt-12 md:mt-0">
                <div class="flex items-center justify-between mb-2 md:mb-4">
                    <h3 id="selected-date-title" class="font-headline font-bold text-sm md:text-xl text-on-background capitalize">
                        {{ $currentDate->translatedFormat('j F, l') }}
                    </h3>
                    <span id="event-count-badge" class="text-on-surface-variant text-[10px] md:text-sm">
                        {{ $selectedEvents->count() }} Etkinlik Bulundu
                    </span>
                </div>
                
                <div id="event-list-container" class="transition-all duration-500">
                    @include('partials.unified-event-card', ['selectedEvents' => $selectedEvents, 'date' => $currentDateStr])
                </div>
            </div>
        </div>

        <!-- Tab Content 2: Grid View -->
        <div id="grid-view" class="tab-content grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 min-h-[400px]"
            data-tab-content="all">
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
            if (document.querySelector('.unified-events-swiper')) {
                // Destroy existing swiper if it exists
                if (unifiedSwiper) unifiedSwiper.destroy(true, true);

                unifiedSwiper = new Swiper('.unified-events-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 24,
                    loop: false,
                    grabCursor: true,
                    pagination: {
                        el: '.unified-pagination',
                        clickable: true,
                        dynamicBullets: true,
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 24,
                        }
                    }
                });
            }
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
                    
                    // Update title and count
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.html;
                    
                    // Re-initialize slider
                    initUnifiedSwiper();
                    
                    eventListContainer.style.opacity = '1';
                    
                    const count = tempDiv.querySelectorAll('.group').length;
                    document.getElementById('event-count-badge').innerText = `${count} Etkinlik Bulundu`;
                });
            }, 300);
        }

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const target = btn.getAttribute('data-tab-btn');
                
                // Hide/Show Load More button
                const loadMore = document.getElementById('load-more-container');
                if (target === 'calendar') {
                    loadMore.style.display = 'none';
                } else {
                    loadMore.style.display = 'flex';
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
            });
        });

    </script>
@endsection