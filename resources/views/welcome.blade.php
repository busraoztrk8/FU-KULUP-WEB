@extends('layouts.app')
@section('title', 'Fırat Üniversitesi - Üniversite Hayatını Keşfedin')
@section('data-page', 'home')
@section('content')

    <!-- Hero Section with Image Slider -->
    <section class="relative h-[480px] sm:h-[550px] md:h-[700px] lg:h-[800px] flex items-center justify-center overflow-hidden bg-surface-container-low">
        <!-- Slider Content -->
        <div class="absolute inset-0 z-0">
            <div class="hero-slider-item active h-full w-full">
                <img alt="University Library" class="w-full h-full object-cover brightness-[0.5] sm:brightness-[0.6] md:brightness-75"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBGXyLOwSo27k1_6Ddg5vXCAGtTwpzk90fHHcbFmXnMq5aIBKeKGr0UhTzQH7c8B7Z-Zjlfpq2DTtT8Eh_VfP5YFf2SSNjtOZ_zO8ftmO4Mxzk8F_wwoHnZyFVDtTBk1jpkdPAVw_p1brUdDUJq8F8lyzl6Oy-KEqdeIOoESaDzEi0d0BRD8dGJERB0nFhYcoKDf6jcn9RAtpiE5DqymljGU-NTHD_fB9_BmG2PdLhPcVHkCNvum-DdK6erFUECPnPUAzpaw41m2hE" />
            </div>
        </div>
        <div class="container mx-auto px-4 sm:px-6 relative z-10 text-center">
            <div class="glass-card inline-block px-5 py-8 sm:px-6 sm:py-10 md:px-10 md:py-16 rounded-2xl md:rounded-[2rem] max-w-4xl mx-auto shadow-2xl">
                <h1
                    class="font-headline text-2xl sm:text-3xl md:text-5xl lg:text-7xl font-extrabold tracking-tight mb-4 md:mb-6 leading-tight text-on-surface">
                    Üniversite Hayatını <span class="text-gradient">Keşfedin</span>
                </h1>
                <p
                    class="font-body text-sm sm:text-base md:text-xl text-on-surface-variant max-w-2xl mx-auto mb-6 md:mb-10 leading-relaxed">
                    Etkinlikleri keşfedin, kulüplere katılın ve kampüs topluluğunuzla ağ oluşturun. Geleceğin
                    akademik ekosistemine bugün adım atın.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 md:gap-4">
                    <a href="{{ route('etkinlikler') }}"
                        class="w-full sm:w-auto px-6 md:px-8 py-3 md:py-4 bg-gradient-primary text-white rounded-full font-bold text-sm md:text-lg hover:opacity-90 transition-all shadow-xl shadow-primary/30 text-center">
                        Etkinlikleri Keşfedin
                    </a>
                    <a href="{{ route('kulupler') }}"
                        class="w-full sm:w-auto px-6 md:px-8 py-3 md:py-4 bg-white border border-outline-variant text-on-surface rounded-full font-bold text-sm md:text-lg hover:bg-surface-container transition-all text-center">
                        Kulüpleri Keşfedin
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-10 md:py-16 border-b border-black/5 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 md:gap-12 text-center">
                <div class="p-4 md:p-6 rounded-2xl hover:bg-slate-50 transition-colors flex flex-col items-center justify-center h-full" data-animate>
                    <div class="text-4xl md:text-5xl font-headline font-extrabold text-gradient mb-1 flex items-center justify-center tabular-nums">
                        <span class="stat-counter" data-target="100">0</span><span>+</span>
                    </div>
                    <div class="text-lg md:text-xl font-bold text-on-surface mb-1">Kulüp</div>
                    <div class="text-on-surface-variant font-medium tracking-wide uppercase text-[10px] md:text-xs">Aktif Topluluk</div>
                </div>
                <div class="p-4 md:p-6 rounded-2xl hover:bg-slate-50 transition-colors flex flex-col items-center justify-center h-full" data-animate>
                    <div class="text-4xl md:text-5xl font-headline font-extrabold text-gradient mb-1 flex items-center justify-center tabular-nums">
                        <span class="stat-counter" data-target="10">0</span><span>K+</span>
                    </div>
                    <div class="text-lg md:text-xl font-bold text-on-surface mb-1">Öğrenci</div>
                    <div class="text-on-surface-variant font-medium tracking-wide uppercase text-[10px] md:text-xs">Kayıtlı Üye</div>
                </div>
                <div class="p-4 md:p-6 rounded-2xl hover:bg-slate-50 transition-colors flex flex-col items-center justify-center h-full" data-animate>
                    <div class="text-4xl md:text-5xl font-headline font-extrabold text-gradient mb-1 flex items-center justify-center tabular-nums">
                        <span class="stat-counter" data-target="50">0</span><span>+</span>
                    </div>
                    <div class="text-lg md:text-xl font-bold text-on-surface mb-1">Günlük Etkinlik</div>
                    <div class="text-on-surface-variant font-medium tracking-wide uppercase text-[10px] md:text-xs">Sürekli Hareketlilik</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trending Events Section -->
    <section class="py-16 md:py-24 px-4 sm:px-6 bg-surface-bright">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-8 md:mb-12 gap-4">
                <div>
                    <h2 class="text-2xl md:text-3xl font-headline font-bold mb-2 text-on-surface">Trend Etkinlikler</h2>
                    <div class="h-1.5 w-20 bg-primary rounded-full"></div>
                </div>
                <a class="text-primary font-bold flex items-center hover:underline transition-all text-sm md:text-base"
                    href="{{ route('tum-etkinlikler') }}">
                    Tümünü Görüntüle <span class="material-symbols-outlined ml-1 text-sm">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @forelse($trendingEvents as $event)
                <a href="{{ route('etkinlik.detay', ['slug' => $event->slug]) }}"
                    class="bg-white rounded-2xl overflow-hidden group hover:shadow-2xl transition-all duration-300 cursor-pointer border border-black/5 block">
                    <div class="p-4 md:p-6">
                        <div class="flex gap-3 md:gap-4 mb-3 md:mb-4">
                            <div
                                class="bg-surface-container rounded-lg p-2 md:p-3 text-center min-w-[50px] md:min-w-[60px] flex flex-col justify-center items-center">
                                <span class="text-xs font-bold text-primary uppercase">{{ $event->start_time->translatedFormat('M') }}</span>
                                <span class="text-xl md:text-2xl font-bold text-on-surface">{{ $event->start_time->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="text-lg md:text-xl font-headline font-bold mb-1 group-hover:text-primary transition-colors text-on-surface truncate">
                                    {{ $event->title }}</h3>
                                <div class="flex items-center text-sm text-on-surface-variant">
                                    <span class="material-symbols-outlined text-sm mr-1">location_on</span>
                                    {{ $event->location ?? 'Yer Belirtilmedi' }}
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 mb-4 md:mb-6 flex-wrap">
                            @if($event->category)
                            <span
                                class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider">{{ $event->category->name }}</span>
                            @endif
                        </div>
                        @if($event->image)
                            <img alt="{{ $event->title }}" class="w-full h-40 md:h-48 object-cover rounded-xl"
                                src="{{ asset('storage/' . $event->image) }}" />
                        @else
                            <div class="w-full h-40 md:h-48 bg-slate-100 flex items-center justify-center rounded-xl text-slate-300">
                                <span class="material-symbols-outlined text-4xl">event</span>
                            </div>
                        @endif
                    </div>
                </a>
                @empty
                <div class="col-span-full py-12 text-center text-slate-400">
                    Henüz öne çıkarılan bir etkinlik bulunmuyor.
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Active Clubs Section -->
    <section class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 max-w-7xl">
            <div class="mb-10 md:mb-16">
                <h2 class="text-2xl md:text-4xl font-headline font-bold text-center mb-4 text-on-surface">Aktif Kulüpler</h2>
                <p class="text-on-surface-variant text-center max-w-xl mx-auto text-sm md:text-base">İlgi alanlarınıza göre bir topluluk
                    seçin ve yeteneklerinizi benzer düşünen insanlarla geliştirin.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                @forelse($activeClubs as $club)
                <a href="{{ route('kulup.detay', ['slug' => $club->slug]) }}"
                    class="group bg-surface-container-high rounded-2xl md:rounded-3xl overflow-hidden shadow-sm flex flex-col lg:flex-row hover:shadow-xl transition-all duration-500 block text-left">
                    <div class="lg:w-1/2 relative overflow-hidden h-48 sm:h-64 lg:h-auto">
                        @if($club->logo)
                            <img src="{{ str_starts_with($club->logo, 'http') ? $club->logo : asset('storage/' . $club->logo) }}" alt="{{ $club->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-300">
                                <span class="material-symbols-outlined text-5xl">groups</span>
                            </div>
                        @endif
                    </div>
                    <div class="lg:w-1/2 p-6 md:p-8 lg:p-10 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3 md:mb-4">
                                @if($club->category)
                                <span
                                    class="px-3 py-1 bg-primary text-white rounded-full text-[10px] font-bold uppercase tracking-tighter">{{ $club->category->name }}</span>
                                @endif
                                <span class="text-on-surface-variant text-sm flex items-center"><span
                                        class="material-symbols-outlined text-xs mr-1">group</span> {{ $club->member_count ?? 0 }} Üye</span>
                            </div>
                            <h3
                                class="text-xl md:text-2xl font-headline font-bold mb-2 md:mb-3 text-on-surface group-hover:text-primary transition-colors">
                                {{ $club->name }}</h3>
                            <p class="text-on-surface-variant text-sm leading-relaxed mb-4 md:mb-6">
                                {{ $club->short_description ?? Str::limit(strip_tags($club->description), 100) }}
                            </p>
                        </div>
                        <div class="text-primary font-bold text-sm flex items-center gap-2">
                            Kulübü Görüntüle <span class="material-symbols-outlined">arrow_right_alt</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full py-12 text-center text-slate-400">
                    Henüz aktif bir kulüp bulunmuyor.
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Campus Life Gallery Section -->
    <section class="py-16 md:py-24 px-4 sm:px-6 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="mb-10 md:mb-16">
                <h2 class="text-2xl md:text-4xl font-headline font-bold text-center mb-4 text-on-surface">Kampüs Yaşamından
                    Kareler</h2>
                <div class="h-1 w-20 bg-primary mx-auto rounded-full"></div>
            </div>
            <div class="swiper gallery-swiper overflow-hidden rounded-2xl md:rounded-3xl pb-12">
                <div class="swiper-wrapper flex items-start">
                    @php
                        $columns = $galleryImages->chunk(2);
                    @endphp
                    @forelse($columns as $index => $chunk)
                        <div class="swiper-slide !w-[240px] sm:!w-[280px] md:!w-[320px] transition-all duration-300">
                            <div class="flex flex-col gap-4 md:gap-6 {{ $index % 2 != 0 ? 'pt-8 md:pt-16' : '' }}">
                                @foreach($chunk as $imgIndex => $image)
                                    @php
                                        // "Stone-like" staggered heights
                                        $heightClass = ($index % 2 == 0) 
                                            ? ($imgIndex % 2 == 0 ? 'h-60 md:h-80' : 'h-48 md:h-64') 
                                            : ($imgIndex % 2 == 0 ? 'h-48 md:h-64' : 'h-60 md:h-80');
                                    @endphp
                                    <div class="relative group cursor-pointer {{ $heightClass }} overflow-hidden rounded-2xl md:rounded-3xl shadow-md hover:shadow-2xl transition-all duration-500">
                                        <img alt="{{ $image->title ?? 'Kampüs Yaşamı' }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                            src="{{ str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path) }}" />
                                        <div class="absolute inset-0 pointer-events-none bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-end p-4 md:p-6">
                                            <p class="text-white text-sm font-bold transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">{{ $image->title }}</p>
                                        </div>
                                        <div class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/30 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                                            <span class="material-symbols-outlined text-white text-base">zoom_in</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="w-full py-12 text-center text-slate-400">
                            <p>Henüz galeriye resim eklenmemiş.</p>
                        </div>
                    @endforelse
                </div>
                <!-- Only Pagination dots remain -->
                <div class="swiper-pagination !-bottom-1 !bottom-0"></div>
            </div>
        </div>
    </section>

    <!-- Success Stories Section -->
    <section class="py-16 md:py-24 px-4 sm:px-6 bg-surface-bright">
        <div class="max-w-7xl mx-auto">
            <div class="mb-10 md:mb-16">
                <h2 class="text-2xl md:text-4xl font-headline font-bold text-center mb-4 text-on-surface">Başarı Hikayeleri</h2>
                <p class="text-on-surface-variant text-center max-w-xl mx-auto text-sm md:text-base">Topluluğumuzun birlikte imza attığı
                    gurur dolu anlar ve önemli başarılar.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <!-- Success Card 1 -->
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl border border-black/5 shadow-sm hover:shadow-lg transition-all text-center">
                    <div
                        class="w-14 h-14 md:w-16 md:h-16 bg-primary-container text-primary rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <span class="material-symbols-outlined text-2xl md:text-3xl">emoji_events</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2 md:mb-3">Robotik Ödülü</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">Robotik ekibimiz ulusal yarışmada 'En
                        İyi İnovasyon' ödülünü kazandı.</p>
                </div>
                <!-- Success Card 2 -->
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl border border-black/5 shadow-sm hover:shadow-lg transition-all text-center">
                    <div
                        class="w-14 h-14 md:w-16 md:h-16 bg-primary-container text-primary rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <span class="material-symbols-outlined text-2xl md:text-3xl">campaign</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2 md:mb-3">100. Etkinlik</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">Bu dönem topluluklarımız tarafından
                        düzenlenen 100. etkinliği tamamladık.</p>
                </div>
                <!-- Success Card 3 -->
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl border border-black/5 shadow-sm hover:shadow-lg transition-all text-center">
                    <div
                        class="w-14 h-14 md:w-16 md:h-16 bg-primary-container text-primary rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <span class="material-symbols-outlined text-2xl md:text-3xl">groups</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2 md:mb-3">Global İş Birliği</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">Modern Sanatlar Kolektifi, Avrupa'dan
                        3 farklı okul ile partnerlik kurdu.</p>
                </div>
                <!-- Success Card 4 -->
                <div
                    class="bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl border border-black/5 shadow-sm hover:shadow-lg transition-all text-center">
                    <div
                        class="w-14 h-14 md:w-16 md:h-16 bg-primary-container text-primary rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <span class="material-symbols-outlined text-2xl md:text-3xl">star</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-2 md:mb-3">Yılın Topluluğu</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">Girişimcilik Kulübü, en yüksek
                        öğrenci katılım oranıyla yılın kulübü seçildi.</p>
                </div>
            </div>
        </div>
    </section>
@endsection