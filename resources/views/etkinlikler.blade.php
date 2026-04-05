@extends('layouts.app')

@section('title', 'Etkinlikler - Fırat Üniversitesi')
@section('data-page', 'events')
@section('page-title', 'Etkinlikler')

@section('content')
    <!-- Hero Section -->
    <section class="relative h-[350px] sm:h-[420px] md:h-[500px] lg:h-[600px] flex items-center overflow-hidden mx-3 sm:mx-4 md:mx-8 mt-4 rounded-2xl md:rounded-3xl shadow-xl">
        <img alt="Summit Event" class="absolute inset-0 w-full h-full object-cover brightness-[0.4] sm:brightness-50 md:brightness-100"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDzgZ7wHZiMgjwpgluAsvjSzS-728fh-7EyYz0A8nKY-NlVb91E0b5f9yopQ1QbxRkANmud5pEvGECLIzbh1HAKTScv0dAVMuHnP5WxTAahbaTI5vlN1k2jpGjGK07uflPJh0p1eWCJGrNnH5AWVljCgvr4H59lWAJDuveb4DMx8LQI0D0zM8bP2w5yafS9Q6aYVk6EshhkxZORlKJ2ie0thRufJm7wNLHRApFAdOdVnIHYPCg5_8OQk9fXTsInkuBCiG99lt_yw2I" />
        <div class="absolute inset-0 bg-gradient-to-r from-black/90 md:from-black/80 via-black/40 to-transparent"></div>
        <div class="relative z-10 max-w-3xl px-4 sm:px-6 md:px-20 text-center md:text-left">
            <span
                class="bg-primary text-white px-3 md:px-4 py-1 rounded-full text-[10px] font-bold mb-3 md:mb-4 inline-block uppercase tracking-widest">Ana
                Etkinlik</span>
            <h1 class="text-2xl sm:text-3xl md:text-5xl lg:text-7xl font-bold font-headline text-white mb-4 md:mb-6 leading-tight tracking-tight uppercase">
                Geleceğin Teknolojileri Zirvesi 2024</h1>
            <p class="text-sm sm:text-base md:text-xl text-slate-200 mb-6 md:mb-10 max-w-xl font-body leading-relaxed mx-auto md:mx-0">
                Yapay zeka, kuantum bilişim ve sürdürülebilir enerji konularında dünyanın en iyi araştırmacıları ile
                tanışın.
            </p>
            <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-3 md:gap-4">
                <a href="{{ route('etkinlik.detay', ['slug' => 'gelecegin-teknolojileri-zirvesi-2024']) }}"
                    class="bg-primary hover:bg-primary-dark text-white px-6 md:px-8 py-3 md:py-4 rounded-full font-bold transition-all flex justify-center items-center gap-2 group text-sm md:text-base">
                    Kayıt Ol
                    <span
                        class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
                <a href="{{ route('etkinlik.detay', ['slug' => 'gelecegin-teknolojileri-zirvesi-2024']) }}"
                    class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white px-6 md:px-8 py-3 md:py-4 rounded-full font-bold transition-all text-sm md:text-base text-center">
                    Detayları Gör
                </a>
            </div>
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
                            $eventDotClass = 'bg-primary';
                        @endphp
                        
                        @if($isSelected)
                            <a href="?date={{ $dateStr }}#calendar-view" class="p-1.5 md:p-2 text-xs md:text-sm font-bold bg-primary text-white rounded-lg md:rounded-xl shadow-lg ring-2 md:ring-4 ring-primary/10 relative block hover:scale-105 transition-transform">
                                {{ $day }}
                                @if($hasEvents)
                                    <span class="absolute bottom-0.5 md:bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-white rounded-full"></span>
                                @endif
                            </a>
                        @else
                            <a href="?date={{ $dateStr }}#calendar-view" class="p-1.5 md:p-2 text-xs md:text-sm {{ $hasEvents ? 'font-bold text-primary bg-primary/10 rounded-lg md:rounded-xl' : ($isToday ? 'text-primary font-bold' : 'text-slate-600') }} relative block hover:bg-slate-100 rounded-lg transition-colors">
                                {{ $day }}
                                @if($hasEvents)
                                    <span class="absolute bottom-0.5 md:bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 {{ $eventDotClass }} rounded-full"></span>
                                @endif
                            </a>
                        @endif
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
                    <h3 class="font-headline font-bold text-lg md:text-xl text-on-background capitalize">{{ $currentDate->translatedFormat('j F, l') }}</h3>
                    <span class="text-on-surface-variant text-xs md:text-sm">{{ $selectedEvents->count() }} Etkinlik Bulundu</span>
                </div>
                
                @forelse($selectedEvents as $event)
                <a href="{{ route('etkinlik.detay', ['slug' => $event->slug]) }}"
                    class="group bg-white border border-slate-100 hover:border-primary/30 hover:shadow-xl rounded-2xl md:rounded-[2rem] p-4 sm:p-5 md:p-6 transition-all duration-300 flex flex-col sm:flex-row gap-4 md:gap-6 items-start">
                    <div class="w-full sm:w-40 md:w-48 h-32 rounded-xl md:rounded-2xl overflow-hidden shrink-0 bg-slate-100">
                        @if($event->image)
                            <img alt="{{ $event->title }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                src="{{ asset('storage/' . $event->image) }}" />
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <span class="material-symbols-outlined text-4xl">event</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0 flex flex-col h-full">
                        <div class="flex items-center gap-2 md:gap-3 mb-2 flex-wrap">
                            @if($event->category)
                            <span class="bg-primary/10 text-primary text-[10px] font-extrabold uppercase px-2 py-0.5 rounded truncate max-w-[120px]">{{ $event->category->name }}</span>
                            @endif
                            <span class="text-on-surface-variant text-xs flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">schedule</span> 
                                {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                                @if($event->end_time)
                                 - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }}
                                @endif
                            </span>
                        </div>
                        <h4 class="text-lg md:text-xl font-bold font-headline mb-2 text-on-background group-hover:text-primary transition-colors">
                            {{ $event->title }}</h4>
                        <p class="text-on-surface-variant text-sm mb-3 md:mb-4 line-clamp-2 leading-relaxed flex-1">
                            {{ $event->short_description ?? strip_tags($event->description) }}
                        </p>
                        <div class="flex items-center justify-between mt-auto flex-wrap gap-2">
                            <div class="flex items-center gap-1 md:gap-2">
                                <span class="material-symbols-outlined text-primary text-base md:text-lg">location_on</span>
                                <span class="text-xs text-on-surface-variant truncate max-w-[150px]">{{ $event->location ?? 'Belirtilmedi' }}</span>
                            </div>
                            <div class="text-primary font-bold text-sm flex items-center gap-1 opacity-100 transition-opacity">
                                İncele <span class="material-symbols-outlined text-sm">open_in_new</span>
                            </div>
                        </div>
                    </div>
                </a>
                @empty
                <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl md:rounded-[2rem] p-8 md:p-12 text-center flex flex-col items-center justify-center h-full min-h-[200px]">
                    <span class="material-symbols-outlined text-4xl md:text-5xl text-slate-300 mb-3">event_busy</span>
                    <h4 class="text-slate-600 font-bold mb-1 text-lg">Etkinlik Bulunamadı</h4>
                    <p class="text-slate-400 text-sm">Bu güne ait planlanmış bir etkinlik gözükmüyor.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Tab Content 2: Grid View -->
        <div id="grid-view" class="tab-content grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8"
            data-tab-content="all">
            <!-- Card 1 -->
            <a href="{{ route('etkinlik.detay', ['slug' => 'ux-tasarim-atolyesi']) }}"
                class="group bg-primary rounded-2xl md:rounded-[2rem] overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full border border-white/5 cursor-pointer">
                <div class="relative h-48 md:h-60 w-full overflow-hidden shrink-0">
                    <img alt="UX Design Workshop"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuC7Pr3Bd9lO7zz1OtX8bLbtRlbvZeexoAnlTkGU7KNtHDaIsXbf49Oik9tTHE8BTZLhgEexI9p5im9I8FrDRaVV2K-VpXYkNIcWZzrtUneEpZek65YoLPgCBr5B6gN5wSFVaWkOroy8VQb7o7l6DBv0cr3KNApCdfprnirugWlW8dRDlE_8hfOqg3NL1xCa0Ec6laLEfUGAJ2GPLBFAlymhG0t6SVi5C6upGq9BKU8vBSWwsdjjU2F2QE5Kz_4kQPgbSfxftkprNq8" />
                    <div
                        class="absolute top-3 md:top-4 left-3 md:left-4 bg-white rounded-xl md:rounded-2xl p-2 md:p-3 text-center min-w-[50px] md:min-w-[60px] shadow-lg text-primary">
                        <div class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-0.5 md:mb-1">ARA</div>
                        <div class="text-xl md:text-2xl font-extrabold">15</div>
                    </div>
                </div>
                <div class="p-5 md:p-8 flex flex-col flex-1 text-white">
                    <div class="flex items-center gap-2 md:gap-3 mb-2 md:mb-3">
                        <span
                            class="bg-white/20 text-[10px] font-extrabold uppercase px-2 py-0.5 rounded">AKADEMİK</span>
                        <span class="text-white/80 text-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">schedule</span> 14:00 - 17:00
                        </span>
                    </div>
                    <h4 class="text-xl md:text-2xl font-bold font-headline mb-2 md:mb-3 leading-tight uppercase">UX Tasarım Atölyesi
                    </h4>
                    <p class="text-white/70 text-sm mb-4 md:mb-6 line-clamp-2 leading-relaxed">Kullanıcı deneyimi
                        tasarımının temellerini öğreneceğiniz, pratik uygulamalarla dolu interaktif bir gün.</p>
                    <div class="mt-auto flex items-center justify-between pt-3 md:pt-4 border-t border-white/10">
                        <div class="flex items-center gap-1 md:gap-2">
                            <span class="material-symbols-outlined text-sm text-white/60">location_on</span>
                            <span class="text-xs text-white/80">Tasarım Lab 2</span>
                        </div>
                        <div class="text-white font-bold text-sm flex items-center gap-1">
                            Detaylar <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- Card 2 -->
            <div
                class="group bg-primary rounded-2xl md:rounded-[2rem] overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full border border-white/5">
                <div class="relative h-48 md:h-60 w-full overflow-hidden shrink-0">
                    <img alt="Jazz Night"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCpOtHgvlADgB0kXGp0HzLw8lamM5V-rgVXmtlAbvgqn92KzyU6L0nebIShZVccEV56D0V5af4BdjIeauGFspE2NzGYu-ARxthc8-l7qiSevfG-rUSI8q8MVhMVGsdObXNrGBxjFI_prZfIJxENOMiQeIjgkvAHjapiccs_ZwQoYuSbazhq542HdJUbHH_3Bnx7LfDqpsPyuyjRTodhzfXP2hieRrFC9mz96AFdl4gRAK_WWYW2zv7bfDwoWbI3xQhgEtpEnw-h2pw" />
                    <div class="absolute top-3 md:top-4 left-3 md:left-4 bg-white rounded-xl md:rounded-2xl p-2 md:p-3 text-center min-w-[50px] md:min-w-[60px] shadow-lg">
                        <div class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-0.5 md:mb-1">ARA</div>
                        <div class="text-xl md:text-2xl font-extrabold text-primary">18</div>
                    </div>
                </div>
                <div class="p-5 md:p-8 flex flex-col flex-1 text-white">
                    <div class="flex items-center gap-2 md:gap-3 mb-2 md:mb-3">
                        <span class="bg-white/20 text-[10px] font-extrabold uppercase px-2 py-0.5 rounded">KÜLTÜR &
                            SANAT</span>
                        <span class="text-white/80 text-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">schedule</span> 20:30
                        </span>
                    </div>
                    <h4 class="text-xl md:text-2xl font-bold font-headline mb-2 md:mb-3 leading-tight">Caz Gecesi: Kış Melodileri</h4>
                    <p class="text-white/70 text-sm mb-4 md:mb-6 line-clamp-2 leading-relaxed">Üniversitemiz caz
                        topluluğunun hazırladığı büyüleyici bir kış konseri. Sıcak içecekler eşliğinde.</p>
                    <div class="mt-auto flex items-center justify-between pt-3 md:pt-4 border-t border-white/10">
                        <div class="flex items-center gap-1 md:gap-2">
                            <span class="material-symbols-outlined text-sm text-white/60">location_on</span>
                            <span class="text-xs text-white/80">Ana Amfi</span>
                        </div>
                        <a class="text-white font-bold text-sm flex items-center gap-1 hover:underline"
                            href="{{ route('etkinlik.detay', ['slug' => 'caz-gecesi-kis-melodileri']) }}">
                            Detaylar <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div
                class="group bg-primary rounded-2xl md:rounded-[2rem] overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full border border-white/5">
                <div class="relative h-48 md:h-60 w-full overflow-hidden shrink-0">
                    <img alt="Networking"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDzgZ7wHZiMgjwpgluAsvjSzS-728fh-7EyYz0A8nKY-NlVb91E0b5f9yopQ1QbxRkANmud5pEvGECLIzbh1HAKTScv0dAVMuHnP5WxTAahbaTI5vlN1k2jpGjGK07uflPJh0p1eWCJGrNnH5AWVljCgvr4H59lWAJDuveb4DMx8LQI0D0zM8bP2w5yafS9Q6aYVk6EshhkxZORlKJ2ie0thRufJm7wNLHRApFAdOdVnIHYPCg5_8OQk9fXTsInkuBCiG99lt_yw2I" />
                    <div class="absolute top-3 md:top-4 left-3 md:left-4 bg-white rounded-xl md:rounded-2xl p-2 md:p-3 text-center min-w-[50px] md:min-w-[60px] shadow-lg">
                        <div class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-0.5 md:mb-1">ARA</div>
                        <div class="text-xl md:text-2xl font-extrabold text-primary">21</div>
                    </div>
                </div>
                <div class="p-5 md:p-8 flex flex-col flex-1 text-white">
                    <div class="flex items-center gap-2 md:gap-3 mb-2 md:mb-3">
                        <span
                            class="bg-white/20 text-[10px] font-extrabold uppercase px-2 py-0.5 rounded">KARİYER</span>
                        <span class="text-white/80 text-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">schedule</span> 10:00 - 18:00
                        </span>
                    </div>
                    <h4 class="text-xl md:text-2xl font-bold font-headline mb-2 md:mb-3 leading-tight">Networking 101: Sektör Buluşması
                    </h4>
                    <p class="text-white/70 text-sm mb-4 md:mb-6 line-clamp-2 leading-relaxed">Mezunlarımız ve önde gelen
                        şirketlerin temsilcileriyle tanışın, staj ve iş fırsatlarını ilk elden yakalayın.</p>
                    <div class="mt-auto flex items-center justify-between pt-3 md:pt-4 border-t border-white/10">
                        <div class="flex items-center gap-1 md:gap-2">
                            <span class="material-symbols-outlined text-sm text-white/60">location_on</span>
                            <span class="text-xs text-white/80">Kültür Merkezi</span>
                        </div>
                        <a class="text-white font-bold text-sm flex items-center gap-1 hover:underline"
                            href="{{ route('etkinlik.detay', ['slug' => 'networking-101']) }}">
                            Detaylar <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Create Event Card -->
            <div
                class="bg-primary rounded-2xl md:rounded-[2rem] p-6 md:p-10 flex flex-col items-center justify-center text-center text-white border-2 border-dashed border-white/20 hover:border-white/40 transition-all group cursor-pointer sm:col-span-2 lg:col-span-1">
                <div
                    class="w-16 h-16 md:w-20 md:h-20 bg-white/10 rounded-full flex items-center justify-center mb-6 md:mb-8 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl md:text-4xl">add_circle</span>
                </div>
                <h3 class="text-xl md:text-2xl font-bold font-headline mb-3 md:mb-4">Kendi Etkinliğini Oluştur</h3>
                <p class="text-white/70 text-sm mb-6 md:mb-10 leading-relaxed max-w-[200px]">Bir kulüp üyesi misin?
                    Etkinliğini şimdi planla!</p>
                <button
                    class="w-full bg-white text-primary py-3 md:py-4 rounded-xl md:rounded-2xl font-bold hover:bg-surface transition-all shadow-xl shadow-black/10">Etkinlik
                    Başvurusu</button>
            </div>
        </div>

        <!-- More Load Button (Common) -->
        <div class="mt-10 md:mt-16 flex justify-center">
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
@endsection