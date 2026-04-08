@forelse($selectedEvents as $event)
<a href="{{ route('etkinlik.detay', ['slug' => $event->slug]) }}"
    class="group bg-white border border-slate-100 hover:border-primary/30 hover:shadow-xl rounded-2xl md:rounded-[2rem] p-4 sm:p-5 md:p-6 transition-all duration-300 flex flex-col sm:flex-row gap-4 md:gap-6 items-start">
    <div class="w-full sm:w-40 md:w-48 h-32 rounded-xl md:rounded-2xl overflow-hidden shrink-0 bg-slate-100">
        @if($event->image)
            <img alt="{{ $event->title }}"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                src="{{ str_starts_with($event->image, 'http') ? $event->image : asset('storage/' . $event->image) }}" />
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
