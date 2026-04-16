@if(isset($clubs) && $clubs->isNotEmpty())
    @php
        $count = $clubs->count();
        $isSlider = $count > 1;
    @endphp

    <div class="{{ $isSlider ? 'swiper club-swiper' : '' }} mb-8">
        <div class="{{ $isSlider ? 'swiper-wrapper' : 'grid grid-cols-1 sm:grid-cols-2 gap-4' }}">
            @foreach($clubs as $club)
                <div class="{{ $isSlider ? 'swiper-slide h-auto' : 'h-full' }}">
                    <div class="bg-gradient-to-br from-primary/5 to-primary/10 border border-primary/20 rounded-[1.5rem] md:rounded-[2rem] p-5 md:p-6 relative overflow-hidden group h-full flex flex-col justify-between">
                        <!-- Decorative elements -->
                        <div class="absolute -right-10 -top-10 w-24 h-24 bg-primary/10 rounded-full blur-3xl opacity-50 group-hover:scale-150 transition-transform duration-700"></div>

                        <div class="relative z-10 flex flex-col h-full">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-xl bg-white shadow-lg flex items-center justify-center overflow-hidden border border-primary/10 p-1.5 shrink-0">
                                    @if($club->logo)
                                        @php
                                            $cLogo = $club->logo;
                                            $cUrl = str_starts_with($cLogo, 'http') ? $cLogo : (file_exists(public_path('uploads/' . $cLogo)) ? asset('uploads/' . $cLogo) : asset('storage/' . $cLogo));
                                        @endphp
                                        <img src="{{ $cUrl }}" 
                                             alt="{{ $club->name }}" class="w-full h-full object-contain">
                                    @else
                                        <span class="material-symbols-outlined text-primary text-2xl">groups</span>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <span class="text-primary text-[8px] md:text-[9px] font-extrabold uppercase tracking-widest block truncate">Etkinlik Ev Sahibi</span>
                                    <h4 class="text-base md:text-lg font-bold font-headline text-on-background group-hover:text-primary transition-colors leading-tight truncate">
                                        {{ $club->name }}
                                    </h4>
                                </div>
                            </div>

                            <div class="flex-1 bg-white/50 backdrop-blur-sm rounded-xl p-3 border border-white/80 mb-4">
                                <p class="text-on-surface-variant text-[11px] md:text-xs italic leading-relaxed line-clamp-3">
                                    "{{ $club->mission ?? $club->short_description ?? 'Topluluğumuza katılarak kampüs hayatına renk katabilirsin.' }}"
                                </p>
                            </div>

                            <div class="flex gap-2">
                                <a href="{{ route('kulup.detay', $club->slug) }}" 
                                   class="flex-1 bg-primary text-white text-center py-2 md:py-2.5 rounded-lg font-bold text-[10px] md:text-xs hover:shadow-lg transition-all flex items-center justify-center gap-1">
                                    Tanı <span class="material-symbols-outlined text-xs">arrow_forward</span>
                                </a>
                                <button onclick="window.location.href='{{ route('kulup.detay', $club->slug) }}#membership'" 
                                        class="flex-1 bg-white border border-primary/20 text-primary text-center py-2 md:py-2.5 rounded-lg font-bold text-[10px] md:text-xs hover:bg-primary/5 transition-all">
                                    Katıl
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if($isSlider)
            <div class="swiper-pagination !-bottom-6"></div>
        @endif
    </div>
@endif

