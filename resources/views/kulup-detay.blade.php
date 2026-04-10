@extends('layouts.app')
@section('title', e($club->name) . ' - Fırat Üniversitesi')
@section('data-page', 'club-detail')
@push('styles')
<style>
    .gallery-slider .swiper-slide {
        width: auto;
    }
    .gallery-image-container::after {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.4);
        opacity: 0;
        transition: opacity 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .gallery-image-container:hover::after {
        opacity: 1;
        content: "\e3f4"; /* photo_library icon in material symbols */
        font-family: 'Material Symbols Outlined';
        color: white;
        font-size: 24px;
    }
    #lightbox-modal {
        transition: opacity 0.3s ease-out;
    }
    #lightbox-modal.hidden {
        display: none;
        opacity: 0;
    }
</style>
@endpush

@section('content')
<div class="pb-12 md:pb-20 px-4 sm:px-6 md:px-8 max-w-7xl mx-auto">

    {{-- Hero Sector --}}
    <section class="relative mb-12 rounded-3xl overflow-hidden h-[300px] md:h-[450px] shadow-2xl group">
        @if($club->cover_image)
            <img src="{{ str_starts_with($club->cover_image, 'http') ? $club->cover_image : asset('storage/' . $club->cover_image) }}" alt="{{ $club->name }}"
                class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"/>
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

        <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full flex flex-col md:flex-row items-start md:items-end gap-6">
            {{-- Logo --}}
            <div class="w-24 h-24 md:w-32 md:h-32 rounded-2xl bg-white p-2 shadow-2xl shrink-0">
                @if($club->logo)
                    <img src="{{ str_starts_with($club->logo, 'http') ? $club->logo : asset('storage/' . $club->logo) }}" alt="{{ $club->name }}"
                        class="w-full h-full object-cover rounded-xl"/>
                @else
                    <div class="w-full h-full bg-primary rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-4xl">groups</span>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h1 class="font-headline text-3xl md:text-5xl font-extrabold text-white tracking-tight leading-tight mb-4 drop-shadow-lg break-words">
                    {{ $club->name }}
                </h1>
                <div class="flex flex-wrap gap-3">
                    @if($club->category)
                    <span class="px-4 py-1.5 bg-primary/90 backdrop-blur-md text-white rounded-full text-[11px] font-bold uppercase tracking-widest border border-white/20">
                        {{ $club->category->name }}
                    </span>
                    @endif
                    <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md text-white rounded-full text-[11px] font-bold border border-white/10">
                        {{ number_format($club->member_count) }} Üye
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

        {{-- Left Column: Hakkımızda & Activities --}}
        <div class="lg:col-span-8 space-y-12">
            
            {{-- Hakkımızda --}}
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                <div class="flex items-center gap-4 mb-8">
                    <span class="w-1.5 h-10 bg-primary rounded-full"></span>
                    <h2 class="font-headline text-3xl font-bold text-slate-800">Hakkımızda</h2>
                </div>
                
                <div class="prose prose-slate max-w-none mb-10 text-slate-600 leading-relaxed text-lg break-words">
                    {!! nl2br(e($club->description ?? 'Bu kulüp hakkında henüz detaylı bir açıklama girilmemiş.')) !!}
                </div>

                {{-- Mission & Vision Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    {{-- Mission --}}
                    <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100 group hover:border-primary/30 transition-colors">
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                            <span class="material-symbols-outlined text-primary text-[28px]">rocket_launch</span>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-2">Misyonumuz</h4>
                        <p class="text-sm text-slate-500 leading-relaxed italic break-words">
                            {{ $club->mission ?? 'Öğrencilerimize sosyal ve teknik alanlarda değer katmak, üniversite vizyonunu ileriye taşımak.' }}
                        </p>
                    </div>
                    {{-- Vision --}}
                    <div class="bg-slate-50/50 p-6 rounded-2xl border border-slate-100 group hover:border-primary/30 transition-colors">
                        <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                            <span class="material-symbols-outlined text-amber-500 text-[28px]">visibility</span>
                        </div>
                        <h4 class="font-bold text-slate-800 mb-2">Vizyonumuz</h4>
                        <p class="text-sm text-slate-500 leading-relaxed italic break-words">
                            {{ $club->vision ?? 'Alanında öncü, disiplinler arası çalışmalara odaklanan sürdürülebilir bir topluluk olmak.' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Club Gallery --}}
            @if($club->images->count() > 0)
            <div class="gallery-wrapper">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <h2 class="font-headline text-2xl font-bold text-slate-800 flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary">photo_library</span>
                        Kulüp Galerisi
                    </h2>
                    <div class="flex items-center gap-4 hidden sm:flex">
                        <a href="{{ route('galeri') }}" class="text-primary font-bold flex items-center hover:underline transition-all text-sm md:text-base border-r border-slate-200 pr-4">
                            Genel Galeri <span class="material-symbols-outlined ml-1 text-sm">arrow_forward</span>
                        </a>
                        <div class="flex gap-2">
                            <button class="gallery-prev w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                            </button>
                            <button class="gallery-next w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                            </button>
                        </div>
                    </div>
                    <!-- Mobile view all -->
                    <div class="sm:hidden flex items-center justify-between w-full">
                        <div class="flex gap-2">
                            <button class="gallery-prev w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                            </button>
                            <button class="gallery-next w-10 h-10 rounded-full bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-primary hover:border-primary transition-all shadow-sm">
                                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                            </button>
                        </div>
                        <a href="{{ route('galeri') }}" class="text-primary font-bold flex items-center hover:underline transition-all text-sm">
                            Tümü <span class="material-symbols-outlined ml-1 text-sm">arrow_forward</span>
                        </a>
                    </div>
                </div>
                
                <div class="swiper gallery-slider overflow-hidden rounded-3xl">
                    <div class="swiper-wrapper">
                        @foreach($club->images as $index => $image)
                        <div class="swiper-slide !w-auto">
                            <div class="gallery-image-container relative w-[240px] md:w-[320px] aspect-[4/3] rounded-2xl overflow-hidden border border-slate-100 group shadow-sm hover:shadow-xl transition-all cursor-pointer"
                                 onclick="openLightbox({{ $index }})">
                                <img src="{{ str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path) }}" 
                                     data-full="{{ str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path) }}"
                                     class="gallery-thumb w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Upcoming Events --}}
            @php
                $upcomingEvents = $club->events()->where('start_time', '>=', now())->orderBy('start_time')->get();
            @endphp
            @if($upcomingEvents->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-8">
                    <h2 class="font-headline text-2xl font-bold text-slate-800">Yaklaşan Kulüp Etkinlikleri</h2>
                    <a href="{{ route('etkinlikler') }}" class="text-primary text-sm font-bold hover:underline">Tümünü Gör</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($upcomingEvents as $event)
                    <a href="{{ route('etkinlik.detay', $event->slug) }}" class="group bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-slate-100">
                        <div class="aspect-[16/9] relative overflow-hidden">
                            @if($event->image)
                                <img src="{{ str_starts_with($event->image, 'http') ? $event->image : asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"/>
                            @else
                                <div class="absolute inset-0 bg-gradient-to-br from-primary/30 to-primary-dark"></div>
                            @endif
                            <div class="absolute bottom-4 left-4">
                                <span class="bg-red-600 text-white text-[10px] uppercase font-bold px-2.5 py-1 rounded shadow-lg">
                                    {{ $event->start_time->format('d M') }}
                                </span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-bold text-slate-800 mb-2 group-hover:text-primary transition-colors line-clamp-2">{{ $event->title }}</h3>
                            <div class="flex items-center gap-4 text-slate-400 text-[11px] font-medium italic">
                                <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">location_on</span>{{ $event->location ?? 'Belirtilmedi' }}</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Right Column: Sidebar --}}
        <aside class="lg:col-span-4 space-y-8">

            {{-- Info Card --}}
            <div class="bg-white rounded-[32px] p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                <h3 class="font-headline text-2xl font-bold mb-8 text-slate-800">Kulüp Bilgileri</h3>
                
                <div class="space-y-6 mb-10">
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary/80 group-hover:text-primary transition-colors">person</span>
                            <span class="text-slate-400 text-sm font-medium">Kurucu</span>
                        </div>
                        <span class="font-bold text-slate-800 text-sm text-right break-words">{{ $club->founder_name ?? ($club->president->name ?? '-') }}</span>
                    </div>
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary/80 group-hover:text-primary transition-colors">groups</span>
                            <span class="text-slate-400 text-sm font-medium">Üye Sayısı</span>
                        </div>
                        <span class="font-bold text-slate-800 text-sm">{{ number_format($club->member_count) }}</span>
                    </div>
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary/80 group-hover:text-primary transition-colors">category</span>
                            <span class="text-slate-400 text-sm font-medium">Kategori</span>
                        </div>
                        <span class="font-bold text-slate-800 text-sm">{{ $club->category->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary/80 group-hover:text-primary transition-colors">calendar_today</span>
                            <span class="text-slate-400 text-sm font-medium">Kuruluş</span>
                        </div>
                        <span class="font-bold text-slate-800 text-sm">{{ $club->established_year ?? $club->created_at->format('Y') }}</span>
                    </div>
                </div>

                @auth
                    @php
                        $membership = \App\Models\ClubMember::where('club_id', $club->id)
                            ->where('user_id', auth()->id())
                            ->first();
                    @endphp

                    @if(!$membership)
                        <form action="{{ route('kulup.kayit', $club) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-4 bg-[#5d1021] text-white rounded-2xl font-bold text-base hover:opacity-90 active:scale-95 transition-all shadow-xl shadow-primary/20 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">person_add</span>
                                Kulübe Katıl
                            </button>
                        </form>
                    @elseif($membership->status === 'pending')
                         <div class="space-y-3">
                            <div class="w-full py-3 bg-amber-50 text-amber-700 rounded-2xl font-bold text-xs text-center border border-amber-100 italic">Başvuru Onay Bekliyor</div>
                            <form action="{{ route('kulup.ayril', $club) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[18px]">cancel</span>
                                    Başvuruyu Geri Al
                                </button>
                            </form>
                         </div>
                    @else
                         <div class="space-y-3">
                            <div class="w-full py-4 bg-green-50 text-green-700 rounded-2xl font-bold text-sm text-center border border-green-100">Kulüp Üyesisiniz</div>
                            <form action="{{ route('kulup.ayril', $club) }}" method="POST" onsubmit="return confirm('Kulüpten ayrılmak istediğinize emin misiniz?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-full text-center text-xs text-slate-400 hover:text-red-500 font-medium transition-colors">Kulüpten Ayrıl</button>
                            </form>
                         </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="w-full py-4 bg-[#5d1021] text-white rounded-2xl font-bold text-base hover:opacity-90 flex items-center justify-center gap-2 shadow-xl shadow-black/10">
                        <span class="material-symbols-outlined text-[20px]">login</span> Giriş Yap ve Katıl
                    </a>
                @endauth
            </div>

            {{-- Active Members / Board --}}
            @php
                $activeMembers = $club->members()->where('status', 'approved')->whereNotNull('title')->orderBy('title')->take(4)->get();
            @endphp
            @if($activeMembers->count() > 0)
            <div class="bg-white rounded-[32px] p-8 border border-slate-100 shadow-sm">
                <h3 class="font-headline text-2xl font-bold mb-8 text-slate-800">Aktif Üyeler</h3>
                <div class="space-y-6">
                    @foreach($activeMembers as $member)
                    <div class="flex items-center gap-4 group">
                        <div class="w-14 h-14 rounded-full overflow-hidden shrink-0 shadow-sm border border-slate-100">
                            @if($member->user && $member->user->profile_photo)
                                <img src="{{ asset('storage/' . $member->user->profile_photo) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-300">
                                    <span class="material-symbols-outlined text-[24px]">person</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 group-hover:text-primary transition-colors">{{ $member->user->name }}</h4>
                            <p class="text-[11px] font-medium text-slate-400 tracking-wide mt-0.5">{{ $member->title }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- See all link --}}
                <div class="mt-8 pt-6 border-t border-slate-50">
                    <button class="w-full py-3.5 bg-white text-slate-500 rounded-2xl text-sm font-bold hover:bg-slate-50 transition-all border border-slate-100">Tüm Üyeleri Görüntüle</button>
                </div>
            </div>
            @endif

            {{-- Social & Actions --}}
            <div class="flex justify-center gap-4">
                @if($club->website_url)
                    <a href="{{ $club->website_url }}" target="_blank" class="w-14 h-14 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[24px]">public</span>
                    </a>
                @endif
                <button class="w-14 h-14 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">share</span>
                </button>
                <a href="mailto:{{ $club->president->email ?? '' }}" class="w-14 h-14 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-primary hover:text-white hover:border-primary transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[24px]">alternate_email</span>
                </a>
            </div>

        </aside>
    </div>
</div>

{{-- Lightbox Modal --}}
<div id="lightbox-modal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    {{-- Close Btn --}}
    <button onclick="closeLightbox()" class="absolute top-6 right-6 w-12 h-12 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-[110]">
        <span class="material-symbols-outlined text-[32px]">close</span>
    </button>
    
    {{-- Navigation --}}
    <button onclick="prevImage()" class="absolute left-6 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-[110]">
        <span class="material-symbols-outlined text-[40px]">chevron_left</span>
    </button>
    <button onclick="nextImage()" class="absolute right-6 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center text-white transition-all z-[110]">
        <span class="material-symbols-outlined text-[40px]">chevron_right</span>
    </button>

    {{-- Image Container --}}
    <div class="relative w-full h-full flex items-center justify-center p-4 md:p-20">
        <img id="lightbox-img" src="" alt="Galeri Resim" class="max-w-full max-h-full object-contain shadow-2xl rounded-lg transform transition-transform duration-300 scale-95">
    </div>
    
    {{-- Counter --}}
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 bg-black/40 backdrop-blur-md px-6 py-2 rounded-full border border-white/10 text-white/80 font-bold text-sm tracking-wider">
        <span id="lightbox-counter-current">1</span> / <span id="lightbox-counter-total">1</span>
    </div>
</div>

@push('scripts')
<script>
    let currentImageIndex = 0;
    const galleryImages = [];
    
    document.addEventListener('DOMContentLoaded', function() {
        // Collect all images for lightbox
        document.querySelectorAll('.gallery-thumb').forEach((img, index) => {
            galleryImages.push(img.getAttribute('data-full'));
        });

        // Initialize Swiper for gallery
        new Swiper('.gallery-slider', {
            slidesPerView: 'auto',
            spaceBetween: 20,
            freeMode: true,
            navigation: {
                nextEl: '.gallery-next',
                prevEl: '.gallery-prev',
            },
            breakpoints: {
                320: { spaceBetween: 12 },
                768: { spaceBetween: 20 }
            }
        });
    });

    function openLightbox(index) {
        currentImageIndex = index;
        updateLightbox();
        const modal = document.getElementById('lightbox-modal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            document.getElementById('lightbox-img').classList.remove('scale-95');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const modal = document.getElementById('lightbox-modal');
        modal.classList.add('opacity-0');
        document.getElementById('lightbox-img').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }

    function updateLightbox() {
        const img = document.getElementById('lightbox-img');
        img.src = galleryImages[currentImageIndex];
        document.getElementById('lightbox-counter-current').innerText = currentImageIndex + 1;
        document.getElementById('lightbox-counter-total').innerText = galleryImages.length;
    }

    function nextImage() {
        currentImageIndex = (currentImageIndex + 1) % galleryImages.length;
        updateLightbox();
    }

    function prevImage() {
        currentImageIndex = (currentImageIndex - 1 + galleryImages.length) % galleryImages.length;
        updateLightbox();
    }

    // Keyboard support
    document.addEventListener('keydown', (e) => {
        if (document.getElementById('lightbox-modal').classList.contains('hidden')) return;
        
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') prevImage();
        if (e.key === 'Escape') closeLightbox();
    });
</script>
@endpush

@endsection
