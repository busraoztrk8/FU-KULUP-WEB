@extends('layouts.app')

@section('title', 'Etkinlikler - Fırat Üniversitesi')
@section('data-page', 'events')

@section('content')
@php $featured = $events->where('is_featured', true)->first() ?? $events->first(); @endphp

    <!-- Hero Section -->
    <section class="relative h-[350px] sm:h-[420px] md:h-[500px] lg:h-[600px] flex items-center overflow-hidden mx-3 sm:mx-4 md:mx-8 mt-4 rounded-2xl md:rounded-3xl shadow-xl">
        @if($featured && $featured->image)
            <img alt="{{ $featured->title }}" class="absolute inset-0 w-full h-full object-cover"
                src="{{ str_starts_with($featured->image, 'http') ? $featured->image : asset('storage/' . $featured->image) }}" />
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-primary to-primary-dark"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-r from-black/90 md:from-black/80 via-black/40 to-transparent"></div>
        <div class="relative z-10 max-w-3xl px-4 sm:px-6 md:px-20 text-center md:text-left">
            <span class="bg-primary text-white px-3 md:px-4 py-1 rounded-full text-[10px] font-bold mb-3 md:mb-4 inline-block uppercase tracking-widest">
                Ana Etkinlik
            </span>
            @if($featured)
            <h1 class="text-2xl sm:text-3xl md:text-5xl lg:text-7xl font-bold font-headline text-white mb-4 md:mb-6 leading-tight tracking-tight uppercase">
                {{ $featured->title }}
            </h1>
            @if($featured->short_description)
            <p class="text-sm sm:text-base md:text-xl text-slate-200 mb-6 md:mb-10 max-w-xl font-body leading-relaxed mx-auto md:mx-0">
                {{ $featured->short_description }}
            </p>
            @endif
            <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-3 md:gap-4">
                <a href="{{ route('etkinlik.detay', $featured->slug) }}"
                    class="bg-primary hover:bg-primary-dark text-white px-6 md:px-8 py-3 md:py-4 rounded-full font-bold transition-all flex justify-center items-center gap-2 group text-sm md:text-base">
                    Kayıt Ol
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
                <a href="{{ route('etkinlik.detay', $featured->slug) }}"
                    class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white px-6 md:px-8 py-3 md:py-4 rounded-full font-bold transition-all text-sm md:text-base text-center">
                    Detayları Gör
                </a>
            </div>
            @else
            <h1 class="text-3xl md:text-5xl font-bold font-headline text-white mb-4">Etkinlikler</h1>
            @endif
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 md:py-16">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 md:mb-12 gap-4 md:gap-6">
            <div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold font-headline text-on-background mb-2">Yaklaşan Etkinlikler</h2>
                <p class="text-on-surface-variant text-sm md:text-base">Kampüs hayatındaki en güncel akademik ve sosyal etkinlikleri
                    takip edin.</p>
            </div>

            <!-- Tab Toggle -->
            <div class="bg-slate-100 p-1 rounded-full flex gap-1 shadow-sm w-full md:w-auto">
                <button
                    class="flex-1 md:flex-none px-6 md:px-8 py-2.5 rounded-full bg-primary text-white font-bold text-sm transition-all shadow-md tab-btn active"
                    data-tab-btn="calendar">Takvim</button>
                <button
                    class="flex-1 md:flex-none px-6 md:px-8 py-2.5 rounded-full text-slate-600 hover:text-on-background font-medium text-sm transition-all tab-btn"
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
            <div class="lg:col-span-1 bg-slate-50 rounded-2xl md:rounded-[2rem] p-4 sm:p-6 md:p-8 border border-slate-100 h-fit">
                <div class="flex justify-between items-center mb-6 md:mb-8">
                    <h3 class="font-headline font-bold text-lg md:text-xl text-on-background capitalize">{{ $startOfMonth->translatedFormat('F Y') }}</h3>
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
                <div class="mt-6 md:mt-8 pt-6 md:pt-8 border-t border-slate-200">
                    <h4 class="text-sm font-bold mb-3 md:mb-4 flex items-center gap-2 text-on-background">
                        <span class="material-symbols-outlined text-primary text-sm"
                            style="font-variation-settings: 'FILL' 1;">category</span>
                        Kategoriler
                    </h4>
                    <ul class="space-y-2 md:space-y-3 lg:grid lg:grid-cols-2 lg:space-y-0 lg:gap-2">
                        @foreach(\App\Models\Category::take(6)->get() as $cat)
                        <li class="flex items-center text-sm gap-2">
                            <span class="w-2 h-2 rounded-full bg-primary/80"></span>
                            <span class="text-on-surface-variant truncate" title="{{ $cat->name }}">{{ $cat->name }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Event List Side -->
            <div class="lg:col-span-1 space-y-4 md:space-y-6">
                <div class="flex items-center justify-between mb-3 md:mb-4">
                    <h3 id="selected-date-title" class="font-headline font-bold text-lg md:text-xl text-on-background capitalize">
                        {{ $currentDate->translatedFormat('j F, l') }}
                    </h3>
                    <span id="event-count-badge" class="text-on-surface-variant text-xs md:text-sm">
                        {{ $selectedEvents->count() }} Etkinlik Bulundu
                    </span>
                </div>
                
                <div id="club-profile-container" class="transition-all duration-500">
                    @if(isset($initialClubs) && $initialClubs->isNotEmpty())
                        @include('partials.club-mini-profile', ['clubs' => $initialClubs])
                    @endif
                </div>

                <div id="event-list-container" class="space-y-4 md:space-y-6 transition-all duration-300">
                    @include('partials.event-list-items', ['date' => $currentDateStr])
                </div>
            </div>
        </div>

        <!-- Tab Content 2: Grid View -->
        <div id="grid-view" class="tab-content grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 min-h-[400px]"
            data-tab-content="all">
            @include('partials.event-grid-items')
        </div>

        <!-- More Load Button (Common) -->
        <div id="load-more-container" class="mt-10 md:mt-16 flex justify-center" style="display: none;">
            <button
                class="bg-slate-50 hover:bg-slate-100 text-on-surface-variant px-8 md:px-10 py-3 rounded-full font-bold transition-all border border-slate-100 flex items-center gap-2 shadow-sm text-sm md:text-base">
                Daha Fazla Yükle
                <span class="material-symbols-outlined text-sm">expand_more</span>
            </button>
        </div>
    </section>

    <!-- Categories / Filter Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 pb-16 md:pb-24 border-t border-slate-100 pt-10 md:pt-16">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 md:mb-12 gap-4 md:gap-8">
            <h3 class="text-2xl md:text-3xl font-bold font-headline text-on-background">Tüm Etkinlik Kategorileri</h3>
            <div class="flex flex-wrap gap-3 md:gap-4 items-center w-full md:w-auto">
                <div class="relative flex-1 md:w-80">
                    <span
                        class="material-symbols-outlined absolute left-3 md:left-4 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg md:text-xl">search</span>
                    <input type="text" placeholder="Hızlı ara..."
                        class="w-full pl-10 md:pl-12 pr-4 py-3 bg-surface-container border-none rounded-xl md:rounded-2xl focus:ring-2 focus:ring-primary/20 font-medium text-sm md:text-base">
                </div>
                <button
                    class="p-3 bg-surface-container rounded-xl md:rounded-2xl text-on-surface-variant hover:text-primary transition-all">
                    <span class="material-symbols-outlined">tune</span>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            <!-- Bento Style Card -->
            <div class="md:col-span-2 group relative h-60 sm:h-72 md:h-80 rounded-2xl md:rounded-[2.5rem] overflow-hidden shadow-xl">
                <img alt="Academic Research"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuACZq-VolsSL6zSF-oAqBHZtcPzNPBDJIdpKFv-9WNuhL7wX1AGzTwwsYyYFhjfFuQ9Wo2SBOSVzbC3I0GTyH8Ts-prdx77ncJw0fqdFuWmsZTjmuhxceeHoefP84QvY-T2oHF-E43fa0IzNdHWSyZaYWxrK1tIzWtN95SAPphgLMpUeKv5m2yUCG8INZgptzl7NdkqfiBVU2SbIA8orVw7BPi4xYRe4DBbO1BKpXOfKpFoJDVJCVLFgpT9hLnL8I1GzYUltJIaSC8" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6 sm:p-8 md:p-12">
                    <span class="text-tertiary text-xs font-extrabold uppercase tracking-widest mb-2 md:mb-4 block">Öne
                        Çıkan</span>
                    <h4 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-2 md:mb-4 leading-tight">Uluslararası Tıp Kongresi 2024</h4>
                    <p class="text-slate-300 text-sm md:text-lg max-w-md line-clamp-2">Genom düzenleme ve geleceğin sağlık teknolojileri
                        üzerine 3 günlük maraton.</p>
                </div>
            </div>
            <!-- Additional Categories -->
            <div
                class="group bg-surface-container border border-black/5 rounded-2xl md:rounded-[2rem] p-6 md:p-8 flex flex-col justify-between hover:border-primary/30 transition-all">
                <div>
                    <div class="w-12 h-12 md:w-14 md:h-14 bg-primary/10 rounded-xl md:rounded-2xl flex items-center justify-center mb-4 md:mb-6">
                        <span class="material-symbols-outlined text-primary text-2xl md:text-3xl">school</span>
                    </div>
                    <h4 class="text-xl md:text-2xl font-bold mb-2 md:mb-3 text-on-surface">Mezuniyet Balosu</h4>
                    <p class="text-on-surface-variant text-sm leading-relaxed">2024 Mezunları için unutulmaz bir
                        gece. Bilet satışları başladı.</p>
                </div>
                <div class="mt-6 md:mt-8 flex items-center justify-between">
                    <span class="text-primary font-bold text-sm md:text-base">21 Haziran</span>
                    <span
                        class="material-symbols-outlined text-on-surface-variant group-hover:translate-x-2 transition-transform">arrow_forward</span>
                </div>
            </div>
        </div>
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

        let clubSwiper;

        function initClubSwiper() {
            if (document.querySelector('.club-swiper')) {
                // Destroy existing swiper if it exists
                if (clubSwiper) clubSwiper.destroy(true, true);

                clubSwiper = new Swiper('.club-swiper', {
                    slidesPerView: 1,
                    spaceBetween: 24,
                    loop: true,
                    grabCursor: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
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
            initClubSwiper();
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

            const container = document.getElementById('event-list-container');
            const clubContainer = document.getElementById('club-profile-container');
            container.style.opacity = '0.5';
            clubContainer.style.opacity = '0.5';

            fetch(`/etkinlikler?date=${date}&view_type=calendar_list`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                container.innerHTML = data.html;
                clubContainer.innerHTML = data.club_html;
                
                container.style.opacity = '1';
                clubContainer.style.opacity = '1';
                
                const count = container.querySelectorAll('.group').length;
                document.getElementById('event-count-badge').innerText = `${count} Etkinlik Bulundu`;
                
                // Re-initialize swiper for new content
                setTimeout(initClubSwiper, 100);
            });
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