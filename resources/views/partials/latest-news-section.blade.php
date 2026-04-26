@if($latestNews->count() > 0)
<section class="max-w-6xl mx-auto px-4 sm:px-6 py-12 md:py-20 border-t border-black/5">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 md:mb-12 gap-4">
        <div>
            <h2 class="text-xl md:text-3xl font-extrabold font-headline text-on-surface">{{ __('site.latest_news_heading') }}</h2>
            <p class="text-on-surface-variant text-sm mt-1">{{ __('site.latest_developments') }}</p>
        </div>
        <a href="{{ route('tum-haberler') }}" class="text-on-surface font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all">
            <span class="material-symbols-outlined text-[18px]">newspaper</span>
            {{ __('site.view_all_news') }}
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        @foreach($latestNews as $item)
        <a href="{{ route('haber.detay', $item->slug) }}"
            class="burgundy-card group rounded-2xl md:rounded-3xl overflow-hidden flex flex-col h-full shadow-lg hover:shadow-2xl transition-all duration-500 border border-white/10">
            <div class="h-44 md:h-48 overflow-hidden relative">
                @if($item->image_path)
                    @php
                        $nPath = $item->image_path;
                        $nUrl = str_starts_with($nPath, 'http') ? $nPath : (file_exists(public_path('uploads/' . $nPath)) ? asset('uploads/' . $nPath) : asset('storage/' . $nPath));
                    @endphp
                    <img src="{{ $nUrl }}" alt="{{ $item->title }}"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"/>
                @else
                    <div class="w-full h-full bg-primary-dark flex items-center justify-center">
                        <span class="material-symbols-outlined text-white/30 text-[64px]">newspaper</span>
                    </div>
                @endif
                @if($item->club)
                <div class="absolute top-3 left-3 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">
                    {{ $item->club->name }}
                </div>
                @endif
            </div>
            <div class="p-4 md:p-8 flex flex-col flex-1">
                <div class="flex items-center gap-2 text-xs text-white/70 font-bold uppercase tracking-wider mb-2 md:mb-3">
                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                    {{ $item->published_at ? $item->published_at->translatedFormat('d M Y') : $item->created_at->translatedFormat('d M Y') }}
                </div>
                <h3 class="text-base md:text-xl font-bold font-headline text-white group-hover:text-white/90 transition-colors leading-snug mb-2 line-clamp-2">
                    {{ $item->title }}
                </h3>
                <p class="text-white/80 text-xs md:text-sm mb-4 md:mb-6 leading-relaxed line-clamp-3">
                    {{ Str::limit(strip_tags($item->content), 120) }}
                </p>
                <div class="mt-auto w-full py-2.5 md:py-3.5 rounded-xl md:rounded-2xl bg-white text-primary font-bold hover:bg-slate-100 transition-all flex justify-center items-center active:scale-95 shadow-lg text-xs md:text-sm">
                    {{ __('site.read_more') }}
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif
