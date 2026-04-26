@php
    \Carbon\Carbon::setLocale(app()->getLocale());
    $currentDateStr = request('date', $currentDateStr ?? now()->format('Y-m-d'));
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
@endphp
<div class="flex justify-between items-center mb-3 md:mb-6">
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-xl md:text-2xl">calendar_month</span>
        <h3 class="font-headline font-bold text-sm md:text-xl text-on-background capitalize">{{ app()->getLocale() == 'en' ? $startOfMonth->format('F Y') : $startOfMonth->translatedFormat('F Y') }}</h3>
    </div>
    <div class="flex gap-2">
        <button type="button" onclick="changeMonth('{{ $startOfMonth->copy()->subMonth()->format('Y-m-d') }}')" class="p-1.5 md:p-2 rounded-lg hover:bg-white text-on-surface-variant transition-colors"><span
                class="material-symbols-outlined">chevron_left</span></button>
        <button type="button" onclick="changeMonth('{{ $startOfMonth->copy()->addMonth()->format('Y-m-d') }}')" class="p-1.5 md:p-2 rounded-lg hover:bg-white text-on-surface-variant transition-colors"><span
                class="material-symbols-outlined">chevron_right</span></button>
    </div>
</div>
<div class="grid grid-cols-7 gap-y-3 md:gap-y-4 text-center mb-4">
    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">{{ __('site.day_mo') }}</div>
    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">{{ __('site.day_tu') }}</div>
    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">{{ __('site.day_we') }}</div>
    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">{{ __('site.day_th') }}</div>
    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">{{ __('site.day_fr') }}</div>
    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60 text-primary">{{ __('site.day_sa') }}</div>
    <div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60 text-primary">{{ __('site.day_su') }}</div>
    
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
