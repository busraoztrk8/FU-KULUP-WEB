@forelse($announcements as $item)
<a href="{{ route('duyuru.detay', $item->slug) }}"
    class="burgundy-card group rounded-2xl md:rounded-3xl overflow-hidden flex flex-col h-full shadow-lg hover:shadow-2xl transition-all duration-500 border border-white/10">
    <div class="h-44 md:h-48 overflow-hidden relative">
        @if($item->image_path)
            @php
                $iPath = $item->image_path;
                $iUrl = str_starts_with($iPath, 'http') ? $iPath : (file_exists(public_path('uploads/' . $iPath)) ? asset('uploads/' . $iPath) : asset('storage/' . $iPath));
            @endphp
            <img src="{{ $iUrl }}" alt="{{ $item->title }}"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"/>
        @else
            <div class="w-full h-full bg-primary-dark flex items-center justify-center">
                <span class="material-symbols-outlined text-white/30 text-[64px]">campaign</span>
            </div>
        @endif
        @if($item->club)
        <div class="absolute top-3 left-3 bg-white/20 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">
            {{ app()->getLocale() == 'en' && $item->club->name_en ? $item->club->name_en : $item->club->name }}
        </div>
        @endif
        <div class="absolute top-3 right-3">
            <div class="w-8 h-8 bg-white/20 backdrop-blur-md text-white rounded-full flex items-center justify-center shadow-lg">
                <span class="material-symbols-outlined text-[16px]">campaign</span>
            </div>
        </div>
    </div>
    <div class="p-4 md:p-8 flex flex-col flex-1">
        <div class="flex items-center gap-2 text-xs text-white/70 font-bold uppercase tracking-wider mb-2 md:mb-3">
            <span class="material-symbols-outlined text-[14px]">schedule</span>
            {{ $item->published_at ? \Carbon\Carbon::parse($item->published_at)->translatedFormat('d M Y') : $item->created_at->translatedFormat('d M Y') }}
        </div>
        <h3 class="text-sm md:text-2xl font-bold font-headline text-white group-hover:text-white/90 transition-colors leading-snug mb-2 line-clamp-2">
            {{ app()->getLocale() == 'en' && $item->title_en ? $item->title_en : $item->title }}
        </h3>
        <p class="text-white/80 text-xs md:text-sm mb-4 md:mb-6 leading-relaxed line-clamp-3">
            {{ app()->getLocale() == 'en' && $item->content_en ? Str::limit(strip_tags($item->content_en), 120) : Str::limit(strip_tags($item->content), 120) }}
        </p>
        <div class="mt-auto w-full py-2.5 md:py-3.5 rounded-xl md:rounded-2xl bg-white text-primary font-bold hover:bg-slate-100 transition-all flex justify-center items-center active:scale-95 shadow-lg text-xs md:text-sm">
            {{ __('site.read_more') }}
        </div>
    </div>
</a>
@empty
<div class="col-span-full text-center py-20">
    <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-6">
        <span class="material-symbols-outlined text-amber-200 text-[40px]">campaign</span>
    </div>
    <h3 class="text-xl font-bold text-slate-400 mb-2">{{ __('site.no_announcements_yet') }}</h3>
    <p class="text-slate-400 text-sm">{{ __('site.announcements_will_appear') }}</p>
</div>
@endforelse

<div class="col-span-full mt-12 flex justify-center ajax-pagination">
    {{ $announcements->links('partials.custom-pagination') }}
</div>
