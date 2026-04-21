@php
    $startOfMonth  = \Carbon\Carbon::parse($date)->startOfMonth();
    $endOfMonth    = \Carbon\Carbon::parse($date)->endOfMonth();
    $startDayOfWeek = $startOfMonth->dayOfWeekIso; // 1=Mon … 7=Sun
    $year  = $startOfMonth->year;
    $month = $startOfMonth->month;
    $currentDateStr = $date;
@endphp

{{-- Gün başlıkları --}}
<div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">PT</div>
<div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">SA</div>
<div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">ÇA</div>
<div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">PE</div>
<div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60">CU</div>
<div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60 text-primary">CT</div>
<div class="text-[10px] md:text-xs font-bold text-on-surface-variant opacity-60 text-primary">PA</div>

{{-- Önceki ay boş günler --}}
@for($i = 1; $i < $startDayOfWeek; $i++)
    <div class="p-1.5 md:p-2 text-xs md:text-sm text-slate-300">
        {{ $startOfMonth->copy()->subDays($startDayOfWeek - $i)->day }}
    </div>
@endfor

{{-- Bu ayın günleri --}}
@for($day = 1; $day <= $endOfMonth->day; $day++)
    @php
        $dateStr  = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $hasEvents = isset($eventsByDate) && $eventsByDate->has($dateStr);
        $isSelected = $dateStr === $currentDateStr;
        $isToday    = $dateStr === now()->format('Y-m-d');
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

{{-- Sonraki ay boş günler --}}
@php $endDayOfWeek = $endOfMonth->dayOfWeekIso; @endphp
@if($endDayOfWeek < 7)
    @for($i = 1; $i <= (7 - $endDayOfWeek); $i++)
        <div class="p-1.5 md:p-2 text-xs md:text-sm text-slate-300">
            {{ $endOfMonth->copy()->addDays($i)->day }}
        </div>
    @endfor
@endif
