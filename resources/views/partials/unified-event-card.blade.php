@if($selectedEvents->isNotEmpty())
    @php
        $count = $selectedEvents->count();
        $isSlider = $count > 1;
    @endphp

    <div class="{{ $isSlider ? 'swiper unified-events-swiper' : '' }} mb-8 relative">
        <div class="{{ $isSlider ? 'swiper-wrapper' : 'space-y-4' }}">
            @foreach($selectedEvents as $event)
                @php
                    $club = $event->club;
                @endphp
                <div class="{{ $isSlider ? 'swiper-slide h-auto' : '' }}">
                    <div class="bg-white border border-slate-100 rounded-[2rem] p-5 md:p-8 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full group">
                        
                        <!-- Club Part (Header) -->
                        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-50">
                            <div class="w-14 h-14 rounded-2xl bg-white shadow-md flex items-center justify-center overflow-hidden border border-primary/10 p-1.5 shrink-0 group-hover:scale-105 transition-transform">
                                @if($club && $club->logo)
                                    @php
                                        $cLogo = $club->logo;
                                        $cUrl = str_starts_with($cLogo, 'http') ? $cLogo : (file_exists(public_path('uploads/' . $cLogo)) ? asset('uploads/' . $cLogo) : asset('storage/' . $cLogo));
                                    @endphp
                                    <img src="{{ $cUrl }}" alt="{{ $club->name }}" class="w-full h-full object-contain">
                                @else
                                    <span class="material-symbols-outlined text-primary text-2xl">groups</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <span class="text-primary text-[9px] font-extrabold uppercase tracking-widest block mb-0.5">Etkinlik Ev Sahibi</span>
                                <h4 class="text-lg font-bold font-headline text-on-background group-hover:text-primary transition-colors leading-tight truncate">
                                    {{ $club ? $club->name : 'Fırat Üniversitesi' }}
                                </h4>
                            </div>
                        </div>

                        <!-- Club Description -->
                        <div class="mb-6">
                            <p class="text-on-surface-variant text-sm italic leading-relaxed line-clamp-2 bg-slate-50/50 p-4 rounded-2xl border border-slate-50">
                                "{{ $club->short_description ?? 'Kampüs hayatına değer katan etkinlikler ve topluluk ekosistemi.' }}"
                            </p>
                        </div>

                        <!-- Event Part (Specific to the day) -->
                        <div class="flex-1 space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="bg-primary/10 text-primary text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-lg">
                                    {{ $event->category->name ?? 'Etkinlik' }}
                                </span>
                                <span class="text-on-surface-variant text-xs flex items-center gap-1.5 font-bold">
                                    <span class="material-symbols-outlined text-sm">schedule</span>
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                                    @if($event->end_time) - {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }} @endif
                                </span>
                            </div>

                            <h3 class="text-xl font-bold font-headline text-on-background group-hover:text-primary transition-colors line-clamp-2 min-h-[3.5rem]">
                                {{ $event->title }}
                            </h3>

                            @if($event->image)
                                @php
                                    $eImg = $event->image;
                                    $eUrl = str_starts_with($eImg, 'http') ? $eImg : (file_exists(public_path('uploads/' . $eImg)) ? asset('uploads/' . $eImg) : asset('storage/' . $eImg));
                                @endphp
                                <div class="mb-4">
                                    <img src="{{ $eUrl }}" alt="{{ $event->title }}" class="aspect-stable-img rounded-xl group-hover:scale-110 transition-transform duration-700">
                                </div>
                            @endif

                            <div class="flex items-center gap-2 text-on-surface-variant text-sm mt-auto pt-4">
                                <span class="material-symbols-outlined text-primary text-lg shrink-0">location_on</span>
                                <span class="font-medium truncate">{{ $event->location ?? 'Yer Belirtilmedi' }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="grid grid-cols-2 gap-3 mt-8 pt-6 border-t border-slate-50">
                            <a href="{{ route('etkinlik.detay', $event->slug) }}" 
                               class="bg-slate-100 text-slate-600 text-center py-3 rounded-xl font-bold text-xs hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
                                İncele <span class="material-symbols-outlined text-sm">open_in_new</span>
                            </a>
                            <a href="{{ route('kulup.detay', $club->slug) }}" 
                               class="bg-primary text-white text-center py-3 rounded-xl font-bold text-xs hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                Kulübe Üye Ol <span class="material-symbols-outlined text-sm">person_add</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($isSlider)
            <div class="swiper-pagination unified-pagination !-bottom-8"></div>
        @endif
    </div>
@else
    <div class="bg-slate-50 border border-dashed border-slate-200 rounded-[2.5rem] p-12 text-center flex flex-col items-center justify-center h-full min-h-[300px]">
        <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center mb-6 shadow-sm">
            <span class="material-symbols-outlined text-4xl text-slate-300">event_busy</span>
        </div>
        <h4 class="text-slate-600 font-bold mb-2 text-lg font-headline">Etkinlik Bulunamadı</h4>
        <p class="text-slate-400 text-sm max-w-xs mx-auto leading-relaxed">Bu güne ait planlanmış bir etkinlik bulunmuyor. Diğer günlere göz atabilirsin.</p>
    </div>
@endif
