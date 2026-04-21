@if($latestNews->count() > 0)
<section class="max-w-6xl mx-auto px-4 sm:px-6 py-12 md:py-20 border-t border-black/5">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 md:mb-12 gap-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold font-headline text-on-surface">Son Haberler</h2>
            <p class="text-on-surface-variant text-sm mt-1">Üniversitemizden güncel gelişmeler</p>
        </div>
        <a href="{{ route('haberler') }}" class="text-primary font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all">
            Tüm Haberleri Gör
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        @foreach($latestNews as $item)
        <a href="{{ route('haber.detay', $item->slug) }}"
            class="group bg-white rounded-2xl overflow-hidden border border-black/5 hover:border-primary/20 hover:shadow-xl transition-all duration-300 equal-height-card">
            <div class="relative h-48 md:h-52 overflow-hidden">
                @if($item->image_path)
                    @php
                        $nPath = $item->image_path;
                        $nUrl = str_starts_with($nPath, 'http') ? $nPath : (file_exists(public_path('uploads/' . $nPath)) ? asset('uploads/' . $nPath) : asset('storage/' . $nPath));
                    @endphp
                    <img src="{{ $nUrl }}" alt="{{ $item->title }}"
                        class="aspect-stable-img group-hover:scale-110 transition-transform duration-500"/>
                @else
                    <div class="aspect-stable-img bg-gradient-to-br from-primary/10 to-primary/30 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary/40 text-[56px]">newspaper</span>
                    </div>
                @endif
                @if($item->club)
                <div class="absolute top-3 left-3 bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-lg">
                    {{ $item->club->name }}
                </div>
                @endif
            </div>
            <div class="p-5 md:p-6 card-content">
                <div class="flex items-center gap-2 text-xs text-slate-400 font-bold uppercase tracking-wider mb-3">
                    <span class="material-symbols-outlined text-[14px] text-primary">schedule</span>
                    {{ $item->published_at ? $item->published_at->format('d M Y') : $item->created_at->format('d M Y') }}
                </div>
                <h3 class="font-bold text-slate-800 group-hover:text-primary transition-colors text-lg leading-snug mb-2 line-clamp-2">
                    {{ $item->title }}
                </h3>
                <p class="text-sm text-slate-500 leading-relaxed line-clamp-3">
                    {{ Str::limit(strip_tags($item->content), 120) }}
                </p>
                <div class="mt-auto pt-4 flex items-center text-primary font-bold text-sm gap-1 group-hover:gap-2 transition-all">
                    Devamını Oku
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif
