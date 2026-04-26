@extends('layouts.app')
@section('title', __('site.page_title_announcements') . ' - ' . __('site.university_name'))
@section('data-page', 'announcements')

@section('content')
@php
    $defaultTitle = app()->getLocale() == 'en' ? __('site.page_title_announcements') : 'Duyurular';
    $defaultSubtitle = app()->getLocale() == 'en' ? __('site.important_notices') : 'Üniversitemizin ve kulüplerimizin önemli duyurularını takip edin.';
    
    $heroTitle = app()->getLocale() == 'en' ? \App\Models\SiteSetting::getVal('announcements_hero_title_en', $defaultTitle) : \App\Models\SiteSetting::getVal('announcements_hero_title', $defaultTitle);
    $heroSubtitle = app()->getLocale() == 'en' ? \App\Models\SiteSetting::getVal('announcements_hero_subtitle_en', $defaultSubtitle) : \App\Models\SiteSetting::getVal('announcements_hero_subtitle', $defaultSubtitle);
    $heroImage = \App\Models\SiteSetting::getVal('announcements_hero_image');
    $heroUrl = $heroImage ? (file_exists(public_path('uploads/' . $heroImage)) ? asset('uploads/' . $heroImage) : asset('storage/' . $heroImage)) : null;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6">
    <!-- Hero — etkinlikler sayfası ile aynı yükseklik -->
    <section class="@include('partials.site-hero-dimensions') {{ $heroUrl ? '' : 'bg-gradient-to-br from-amber-600 via-amber-700 to-slate-900' }}">
        @if($heroUrl)
            <img src="{{ $heroUrl }}" alt="{{ $heroTitle }}" class="absolute inset-0 w-full h-full object-cover" />
            <div class="absolute inset-0 bg-black/40"></div>
        @else
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>
        @endif
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 md:px-10 lg:px-16 text-center">
            <span class="inline-block bg-white/15 backdrop-blur-sm text-white px-4 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-widest mb-4 border border-white/20">
                <span class="material-symbols-outlined text-[14px] align-middle mr-1">campaign</span>
                {{ __('site.important_notices') }}
            </span>
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold font-headline text-white mb-4 tracking-tight">
                {{ $heroTitle }}
            </h1>
            <p class="text-white/90 text-sm md:text-lg max-w-2xl mx-auto leading-relaxed">
                {{ $heroSubtitle }}
            </p>
        </div>
    </section>

    <!-- Announcements Grid -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 py-12 md:py-20">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 md:mb-12 gap-4">
            <div>
                <h2 class="text-xl md:text-3xl font-extrabold font-headline text-on-surface">{{ __('site.current_announcements') }}</h2>
                <p class="text-on-surface-variant text-sm mt-1">{{ __('site.total_announcements_found', ['count' => $announcements->total()]) }}</p>
            </div>
            <a href="{{ route('tum-duyurular') }}" class="text-on-surface font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all">
                <span class="material-symbols-outlined text-[18px]">campaign</span>
                {{ __('site.view_all_announcements') }}
            </a>
        </div>

        @if($announcements->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @foreach($announcements as $item)
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
                        {{ $item->club->name }}
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
                        {{ $item->published_at ? $item->published_at->translatedFormat('d M Y') : $item->created_at->translatedFormat('d M Y') }}
                    </div>
                    <h3 class="text-sm md:text-2xl font-bold font-headline text-white group-hover:text-white/90 transition-colors leading-snug mb-2 line-clamp-2">
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


        @else
        <div class="text-center py-20">
            <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-amber-200 text-[40px]">campaign</span>
            </div>
            <h3 class="text-xl font-bold text-slate-400 mb-2">{{ __('site.no_announcements_yet') }}</h3>
            <p class="text-slate-400 text-sm">{{ __('site.announcements_will_appear') }}</p>
        </div>
        @endif
    </section>
    
    @include('partials.latest-news-section')
</div>

@endsection
