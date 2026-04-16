@forelse($events as $event)
<a href="{{ route('etkinlik.detay', ['slug' => $event->slug]) }}"
    class="group bg-primary rounded-[1.5rem] md:rounded-[2.5rem] overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full border border-white/5 cursor-pointer">
    <div class="relative w-full overflow-hidden shrink-0">
        @if($event->image)
            @php
                $evImg = $event->image;
                $evUrl = str_starts_with($evImg, 'http') ? $evImg : (file_exists(public_path('uploads/' . $evImg)) ? asset('uploads/' . $evImg) : asset('storage/' . $evImg));
            @endphp
            <img alt="{{ $event->title }}"
                class="aspect-stable-img group-hover:scale-110 transition-transform duration-700"
                src="{{ $evUrl }}" />
        @else
            <div class="aspect-stable-img bg-slate-100 flex items-center justify-center text-slate-300">
                <span class="material-symbols-outlined text-5xl">event</span>
            </div>
        @endif
        
        <div class="absolute top-4 left-4 bg-white rounded-xl md:rounded-[1.2rem] p-2 md:p-3 text-center min-w-[55px] md:min-w-[65px] shadow-xl">
            <div class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">
                {{ \Carbon\Carbon::parse($event->start_time)->translatedFormat('M') }}
            </div>
            <div class="text-xl md:text-2xl font-black text-primary leading-tight">
                {{ \Carbon\Carbon::parse($event->start_time)->format('d') }}
            </div>
        </div>
    </div>
    <div class="p-4 md:p-8 flex flex-col flex-1 text-white card-content">
        <div class="flex items-center gap-2 md:gap-3 mb-3 md:mb-4">
            @if($event->category)
            <span class="bg-white/10 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded tracking-wider border border-white/10">
                {{ $event->category->name }}
            </span>
            @endif
            <span class="text-white/80 text-xs flex items-center gap-1.5 font-medium">
                <span class="material-symbols-outlined text-sm">schedule</span> 
                {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
            </span>
        </div>
        <h4 class="text-sm md:text-2xl font-extrabold font-headline mb-1 md:mb-4 leading-tight uppercase group-hover:text-amber-200 transition-colors">
            {{ $event->title }}
        </h4>
        <p class="text-white/60 text-xs md:text-sm mb-4 md:mb-8 line-clamp-2 leading-relaxed font-body">
            {{ $event->short_description ?? strip_tags($event->description) }}
        </p>
        <div class="mt-auto flex items-center justify-between pt-4 md:pt-5 border-t border-white/5">
            <div class="flex items-center gap-1 md:gap-2">
                <span class="material-symbols-outlined text-sm text-white/40">location_on</span>
                <span class="text-xs text-white/70 font-medium truncate max-w-[140px]">{{ $event->location ?? 'Yer Belirtilmedi' }}</span>
            </div>
            <div class="text-white font-bold text-xs md:text-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                Detaylar <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
            </div>
        </div>
    </div>
</a>
@empty
    {{-- Optional: could handle empty state differentially --}}
@endforelse
