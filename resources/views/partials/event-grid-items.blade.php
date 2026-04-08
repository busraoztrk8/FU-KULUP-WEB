@forelse($events as $event)
<a href="{{ route('etkinlik.detay', ['slug' => $event->slug]) }}"
    class="group bg-primary rounded-[1.5rem] md:rounded-[2.5rem] overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full border border-white/5 cursor-pointer">
    <div class="relative h-48 md:h-64 w-full overflow-hidden shrink-0">
        @if($event->image)
            <img alt="{{ $event->title }}"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                src="{{ str_starts_with($event->image, 'http') ? $event->image : asset('storage/' . $event->image) }}" />
        @else
            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
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
    <div class="p-5 md:p-8 flex flex-col flex-1 text-white">
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
        <h4 class="text-xl md:text-2xl font-extrabold font-headline mb-2 md:mb-4 leading-tight uppercase group-hover:text-amber-200 transition-colors">
            {{ $event->title }}
        </h4>
        <p class="text-white/60 text-sm mb-6 md:mb-8 line-clamp-2 leading-relaxed font-body">
            {{ $event->short_description ?? strip_tags($event->description) }}
        </p>
        <div class="mt-auto flex items-center justify-between pt-4 md:pt-5 border-t border-white/5">
            <div class="flex items-center gap-1 md:gap-2">
                <span class="material-symbols-outlined text-sm text-white/40">location_on</span>
                <span class="text-xs text-white/70 font-medium truncate max-w-[140px]">{{ $event->location ?? 'Yer Belirtilmedi' }}</span>
            </div>
            <div class="text-white font-bold text-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                Detaylar <span class="material-symbols-outlined text-sm">arrow_forward_ios</span>
            </div>
        </div>
    </div>
</a>
@empty
<div class="col-span-1 sm:col-span-2 lg:col-span-3 py-20 text-center text-slate-400 bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200">
    <span class="material-symbols-outlined text-5xl mb-4 text-slate-300">event_busy</span>
    <p class="font-medium text-lg">Henüz aktif bir etkinlik bulunmuyor.</p>
</div>
@endforelse

<!-- "Add New" Card styled to match the premium dark theme -->
<div class="bg-primary rounded-[1.5rem] md:rounded-[2.5rem] p-8 md:p-12 flex flex-col items-center justify-center text-center text-white border-2 border-dashed border-white/20 hover:border-white/40 transition-all group cursor-pointer sm:col-span-2 lg:col-span-1 min-h-[400px]">
    <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 rounded-full flex items-center justify-center mb-6 md:mb-8 group-hover:scale-110 transition-transform shadow-xl">
        <span class="material-symbols-outlined text-3xl md:text-4xl">add_circle</span>
    </div>
    <h3 class="text-2xl md:text-3xl font-bold font-headline mb-3 md:mb-4">Kendi Etkinliğini Oluştur</h3>
    <p class="text-white/60 text-sm mb-8 md:mb-10 leading-relaxed max-w-[240px]">Bir kulüp üyesi misin? Projeni tüm kampüse duyurmak için hemen başvur.</p>
    <button class="w-full bg-white text-primary py-4 md:py-5 rounded-2xl md:rounded-[1.5rem] font-black text-sm uppercase tracking-widest hover:bg-amber-50 transition-all shadow-2xl shadow-black/20">
        Etkinlik Başvurusu
    </button>
</div>
