@if($selectedEvents->isNotEmpty())
    @php
        $count = $selectedEvents->count();
        $isSlider = $count > 1;
    @endphp

    <div class="unified-event-list-root mb-0 relative w-full max-w-full min-w-0 {{ $isSlider ? '' : 'h-full min-h-0 flex flex-col' }}" data-event-count="{{ $count }}">
    <div class="{{ $isSlider ? 'swiper h-full unified-events-swiper calendar-events-swiper relative pb-10 md:pb-12' : 'flex-1 min-h-0 flex flex-col' }} relative w-full max-w-full min-w-0 overflow-hidden">
        <div class="{{ $isSlider ? 'swiper-wrapper h-full' : 'flex-1 min-h-0 flex flex-col space-y-4' }}">
            @foreach($selectedEvents as $event)
                @php
                    $club = $event->club;
                @endphp
                <div class="{{ $isSlider ? 'swiper-slide h-full flex flex-col min-w-0' : 'flex-1 min-h-0 flex flex-col' }}">
                    @php
                        $eImg = $event->image;
                        $eUrl = ($eImg && str_starts_with($eImg, 'http')) ? $eImg : ($eImg && file_exists(public_path('uploads/' . $eImg)) ? asset('uploads/' . $eImg) : ($eImg ? asset('storage/' . $eImg) : null));
                    @endphp
                    {{-- Duyurular kartıyla benzer görsel yüksekliği; lg: yatay düzen → toplam yükseklik kısalır, takvimle hiza --}}
                    <div class="bg-slate-50/50 border border-slate-100 rounded-xl md:rounded-2xl p-3 sm:p-4 md:p-5 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col lg:flex-row lg:items-stretch lg:gap-4 xl:gap-5 w-full max-w-full min-w-0 flex-1 min-h-0 group">
                        @if($eUrl)
                        <div class="w-full lg:w-[min(45%,290px)] xl:w-[min(43%,310px)] shrink-0 lg:min-h-0 lg:self-stretch max-w-full min-w-0">
                            <div class="relative h-40 sm:h-44 md:h-48 lg:h-full lg:min-h-[26rem] rounded-xl overflow-hidden">
                                <img src="{{ $eUrl }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-[1.02] transition-transform duration-500">
                            </div>
                        </div>
                        @endif
                        <div class="flex-1 min-w-0 min-h-0 flex flex-col pt-3 lg:pt-0">
                        <!-- Club Part (Header) — tıklanınca kulüp sayfası -->
                        @if($club)
                        <a href="{{ route('kulup.detay', $club->slug) }}" class="flex items-center gap-3 mb-3 pb-3 border-b border-slate-200/80 rounded-xl -mx-0.5 px-1 py-0.5 hover:bg-white/80 transition-colors cursor-pointer text-left">
                            <div class="w-11 h-11 rounded-xl bg-white shadow-sm flex items-center justify-center overflow-hidden border border-primary/10 p-1 shrink-0 group-hover:scale-105 transition-transform">
                                @if($club->logo)
                                    @php
                                        $cLogo = $club->logo;
                                        $cUrl = str_starts_with($cLogo, 'http') ? $cLogo : (file_exists(public_path('uploads/' . $cLogo)) ? asset('uploads/' . $cLogo) : asset('storage/' . $cLogo));
                                    @endphp
                                    <img src="{{ $cUrl }}" alt="{{ $club->name }}" class="w-full h-full object-contain">
                                @else
                                    <span class="material-symbols-outlined text-primary text-xl">groups</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <span class="text-primary text-[8px] font-extrabold uppercase tracking-widest block mb-0.5">Etkinlik Ev Sahibi</span>
                                <h4 class="text-sm md:text-base font-bold font-headline text-on-background group-hover:text-primary transition-colors leading-tight truncate">
                                    {{ $club->name }}
                                </h4>
                            </div>
                        </a>
                        @else
                        <div class="flex items-center gap-3 mb-3 pb-3 border-b border-slate-200/80">
                            <div class="w-11 h-11 rounded-xl bg-white shadow-sm flex items-center justify-center overflow-hidden border border-primary/10 p-1 shrink-0">
                                <span class="material-symbols-outlined text-primary text-xl">groups</span>
                            </div>
                            <div class="min-w-0">
                                <span class="text-primary text-[8px] font-extrabold uppercase tracking-widest block mb-0.5">Etkinlik Ev Sahibi</span>
                                <h4 class="text-sm md:text-base font-bold font-headline text-on-background leading-tight truncate">Fırat Üniversitesi</h4>
                            </div>
                        </div>
                        @endif

                        <!-- Club Description -->
                        <div class="mb-3">
                            <p class="text-on-surface-variant text-xs italic leading-snug line-clamp-2 bg-white/60 p-2.5 rounded-xl border border-slate-100">
                                "{{ ($club && $club->short_description) ? $club->short_description : 'Kampüs hayatına değer katan etkinlikler ve topluluk ekosistemi.' }}"
                            </p>
                        </div>

                        <!-- Event Part -->
                        <div class="flex-1 flex flex-col gap-2 min-h-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="bg-primary/10 text-primary text-[9px] font-extrabold uppercase px-2 py-0.5 rounded-md">
                                    {{ $event->category->name ?? 'Etkinlik' }}
                                </span>
                                <span class="text-on-surface-variant text-[11px] md:text-xs flex items-center gap-1 font-bold">
                                    <span class="material-symbols-outlined text-sm">schedule</span>
                                    {{ \Carbon\Carbon::parse($event->start_time)->format('H:i') }}
                                    @if($event->end_time) – {{ \Carbon\Carbon::parse($event->end_time)->format('H:i') }} @endif
                                </span>
                            </div>

                            <h3 class="text-base md:text-lg font-bold font-headline text-on-background group-hover:text-primary transition-colors line-clamp-2 leading-snug">
                                {{ $event->title }}
                            </h3>

                            @if(!$eUrl)
                            <div class="rounded-xl bg-slate-100/80 border border-dashed border-slate-200 flex items-center justify-center py-8 text-slate-300">
                                <span class="material-symbols-outlined text-4xl">image</span>
                            </div>
                            @endif

                            <div class="flex items-center gap-1.5 text-on-surface-variant text-xs md:text-sm mt-1">
                                <span class="material-symbols-outlined text-primary text-base shrink-0">location_on</span>
                                <span class="font-medium truncate">{{ $event->location ?? 'Yer Belirtilmedi' }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="grid {{ $club ? 'grid-cols-2' : 'grid-cols-1' }} gap-2 mt-auto pt-3 border-t border-slate-200/80 shrink-0 min-w-0 w-full">
                            <a href="{{ route('etkinlik.detay', $event->slug) }}" 
                               class="bg-white text-slate-600 text-center py-2.5 rounded-lg font-bold text-[11px] md:text-xs hover:bg-slate-100 border border-slate-200/80 transition-all flex items-center justify-center gap-1 min-w-0">
                                İncele <span class="material-symbols-outlined text-sm shrink-0">open_in_new</span>
                            </a>
                            @if($club)
                            <a href="{{ route('kulup.detay', $club->slug) }}" 
                               class="bg-primary text-white text-center py-2.5 rounded-lg font-bold text-[11px] md:text-xs hover:shadow-md transition-all flex items-center justify-center gap-1 min-w-0">
                                 Kulübe Üye Ol <span class="material-symbols-outlined text-[16px] md:text-[18px]">person_add</span>
                            </a>
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($isSlider)
            <button type="button"
                class="unified-events-prev swiper-nav-btn absolute left-0 top-1/2 z-10 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200/80 bg-white/90 text-primary shadow-md backdrop-blur-sm transition-all hover:bg-primary hover:text-white focus:outline-none [&.swiper-button-disabled]:opacity-0"
                aria-label="Önceki etkinlikler">
                <span class="material-symbols-outlined text-[22px] sm:text-[24px]">chevron_left</span>
            </button>
            <button type="button"
                class="unified-events-next swiper-nav-btn absolute right-0 top-1/2 z-10 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200/80 bg-white/90 text-primary shadow-md backdrop-blur-sm transition-all hover:bg-primary hover:text-white focus:outline-none [&.swiper-button-disabled]:opacity-0"
                aria-label="Sonraki etkinlikler">
                <span class="material-symbols-outlined text-[22px] sm:text-[24px]">chevron_right</span>
            </button>
        @endif
    </div>
    </div>
@else
    <div class="space-y-4 md:space-y-5 h-full min-h-0 flex flex-col flex-1">
        <div class="bg-slate-50 border border-dashed border-slate-200 rounded-xl md:rounded-2xl p-5 md:p-7 text-center flex flex-col items-center justify-center">
            <div class="w-12 h-12 md:w-14 md:h-14 bg-white rounded-2xl flex items-center justify-center mb-3 shadow-sm">
                <span class="material-symbols-outlined text-2xl md:text-3xl text-slate-300">event_busy</span>
            </div>
            <h4 class="text-slate-600 font-bold mb-1 text-sm md:text-base font-headline">Etkinlik Bulunamadı</h4>
            <p class="text-slate-400 text-xs md:text-sm max-w-xs mx-auto leading-relaxed">Bu güne ait planlanmış bir etkinlik gözükmüyor.</p>
        </div>
        @include('partials.events-empty-discovery')
    </div>
@endif
