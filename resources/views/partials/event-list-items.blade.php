@if($selectedEvents->isNotEmpty())
    {{-- Always show the first event --}}
    @php $firstEvent = $selectedEvents->first(); @endphp
    <a href="{{ route('etkinlik.detay', ['slug' => $firstEvent->slug]) }}"
        class="group bg-white border border-slate-100 hover:border-primary/30 hover:shadow-xl rounded-2xl md:rounded-[2rem] p-4 sm:p-5 md:p-6 transition-all duration-300 flex flex-col sm:flex-row gap-4 md:gap-6 items-start w-full">
        <div class="w-full sm:w-40 md:w-48 h-24 md:h-32 rounded-xl md:rounded-2xl overflow-hidden shrink-0 bg-slate-100">
            @if($firstEvent->image)
                @php
                    $eImg = $firstEvent->image;
                    $eUrl = str_starts_with($eImg, 'http') ? $eImg : (file_exists(public_path('uploads/' . $eImg)) ? asset('uploads/' . $eImg) : asset('storage/' . $eImg));
                @endphp
                <img alt="{{ $firstEvent->title }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                    src="{{ $eUrl }}" />
            @else
                <div class="w-full h-full flex items-center justify-center text-slate-300">
                    <span class="material-symbols-outlined text-4xl">event</span>
                </div>
            @endif
        </div>
        <div class="flex-1 min-w-0 flex flex-col h-full">
            <div class="flex items-center gap-2 md:gap-3 mb-2 flex-wrap">
                @if($firstEvent->category)
                <span class="bg-primary/10 text-primary text-[10px] font-extrabold uppercase px-2 py-0.5 rounded truncate max-w-[120px]">{{ $firstEvent->category->name }}</span>
                @endif
                <span class="text-on-surface-variant text-xs flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">schedule</span> 
                    {{ \Carbon\Carbon::parse($firstEvent->start_time)->format('H:i') }}
                    @if($firstEvent->end_time)
                     - {{ \Carbon\Carbon::parse($firstEvent->end_time)->format('H:i') }}
                    @endif
                </span>
            </div>
            <h4 class="text-sm md:text-xl font-bold font-headline mb-1 md:mb-2 text-on-background group-hover:text-primary transition-colors">
                {{ $firstEvent->title }}</h4>
            <p class="text-on-surface-variant text-xs md:text-sm mb-2 md:mb-4 line-clamp-2 leading-relaxed flex-1">
                {{ $firstEvent->short_description ?? strip_tags($firstEvent->description) }}
            </p>
            <div class="flex items-center justify-between mt-auto flex-wrap gap-2">
                <div class="flex items-center gap-1 md:gap-2">
                    <span class="material-symbols-outlined text-primary text-base md:text-lg">location_on</span>
                    <span class="text-xs text-on-surface-variant truncate max-w-[150px]">{{ $firstEvent->location ?? 'Belirtilmedi' }}</span>
                </div>
                <div class="text-primary font-bold text-sm flex items-center gap-1 opacity-100 transition-opacity">
                    İncele <span class="material-symbols-outlined text-sm">open_in_new</span>
                </div>
            </div>
        </div>
    </a>

    {{-- If there are more events, show a link to the daily page --}}
    @if($selectedEvents->count() > 1)
        <a href="{{ route('etkinlikler.daily', ['date' => $date]) }}" 
            class="w-full py-4 rounded-2xl md:rounded-[2rem] border-2 border-dashed border-slate-200 text-slate-500 font-bold hover:bg-slate-50 hover:border-primary/30 hover:text-primary transition-all flex items-center justify-center gap-2 mt-2">
            Tüm Etkinlikleri Gör ({{ $selectedEvents->count() }})
            <span class="material-symbols-outlined text-sm">open_in_new</span>
        </a>
    @endif
@else
    <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl md:rounded-[2rem] p-5 md:p-12 text-center flex flex-col items-center justify-center h-full min-h-[150px] md:min-h-[200px] mb-8">
        <span class="material-symbols-outlined text-3xl md:text-5xl text-slate-300 mb-2 md:mb-3">event_busy</span>
        <h4 class="text-slate-600 font-bold mb-1 text-sm md:text-lg">Etkinlik Bulunamadı</h4>
        <p class="text-slate-400 text-xs md:text-sm">Bu güne ait planlanmış bir etkinlik gözükmüyor.</p>
    </div>

    <!-- Fallback Content: Suggested Discovery -->
    <div class="bg-white border border-slate-100 rounded-2xl md:rounded-[2.5rem] p-6 shadow-sm animate-fade-in">
        <h4 class="font-headline font-bold text-slate-800 mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-xl">explore</span>
            Kampüsü Keşfetmeye Devam Et
        </h4>
        
        <div class="grid grid-cols-2 gap-3 mb-8">
            <a href="{{ route('kulupler') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-primary/5 hover:bg-primary/10 border border-primary/10 transition-all group">
                <span class="material-symbols-outlined text-primary text-3xl mb-2 group-hover:scale-110 transition-transform">groups</span>
                <span class="text-xs font-bold text-primary">Tüm Kulüpler</span>
            </a>
            <a href="{{ route('etkinlikler') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-tertiary/5 hover:bg-tertiary/10 border border-tertiary/10 transition-all group">
                <span class="material-symbols-outlined text-tertiary text-3xl mb-2 group-hover:scale-110 transition-transform">event_note</span>
                <span class="text-xs font-bold text-tertiary">Takvime Dön</span>
            </a>
        </div>

        <div class="space-y-4">
            <p class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 px-1">Popüler Kategoriler</p>
            <div class="flex flex-wrap gap-2">
                @php $categories = \App\Models\Category::take(5)->get(); @endphp
                @foreach($categories as $cat)
                <a href="{{ route('etkinlikler') }}?category={{ $cat->id }}" class="px-4 py-2 rounded-xl bg-slate-50 text-slate-600 text-xs font-semibold hover:bg-primary hover:text-white transition-all">
                    #{{ $cat->name }}
                </a>
                @endforeach
            </div>
        </div>

        <div class="mt-8 p-4 rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-[10px] font-bold text-primary-light uppercase tracking-widest mb-1">Yeni Bir Şeyler Başlat</p>
                <h5 class="font-bold text-sm mb-3">Kendi etkinliğini mi planlamak istiyorsun?</h5>
                <a href="#" class="inline-flex items-center gap-2 text-xs font-bold bg-white text-slate-900 px-4 py-2 rounded-full hover:bg-primary hover:text-white transition-all">
                    Kulüp Paneli <span class="material-symbols-outlined text-xs">arrow_forward</span>
                </a>
            </div>
            <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-7xl text-white/5 rotate-12 group-hover:scale-110 transition-transform">rocket_launch</span>
        </div>
    </div>
@endif


