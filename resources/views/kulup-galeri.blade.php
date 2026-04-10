@extends('layouts.app')
@section('title', $club->name . ' Galerisi - Fırat Üniversitesi')
@section('data-page', 'club-gallery')

@push('styles')
<style>
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        grid-auto-rows: 250px;
        gap: 1.5rem;
    }
    
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 1.5rem;
        cursor: pointer;
        transition: all 0.5s ease;
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
    }
    
    .gallery-item:hover img {
        transform: scale(1.08);
    }
    
    .gallery-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 50%, transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 1.5rem;
        pointer-events: none;
    }
    
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
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
<div class="pt-8 pb-16 md:pb-24 px-4 sm:px-6 max-w-7xl mx-auto">
    
    {{-- Breadcrumb / Back --}}
    <div class="mb-8">
        <a href="{{ route('kulup.detay', $club->slug) }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-primary transition-colors font-medium">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Kulübe Dön
        </a>
    </div>

    {{-- Header --}}
    <div class="mb-12 md:mb-16 text-center">
        <h1 class="text-3xl md:text-5xl font-headline font-extrabold text-slate-800 mb-4 tracking-tight">
            {{ $club->name }}<br><span class="text-primary text-2xl md:text-4xl">Galeri</span>
        </h1>
        <div class="h-1.5 w-24 bg-primary mx-auto rounded-full mb-6"></div>
    </div>

    {{-- Grid --}}
    @if($club->images->count() > 0)
        <div class="gallery-grid">
            @foreach($club->images as $index => $image)
                <div class="gallery-item shadow-sm hover:shadow-2xl border border-slate-100" onclick="openLightbox({{ $index }})">
                    <img src="{{ str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path) }}" 
                         data-full="{{ str_starts_with($image->image_path, 'http') ? $image->image_path : asset('storage/' . $image->image_path) }}"
                         class="gallery-thumb" alt="Galeri Görseli">
                    <div class="gallery-overlay">
                        <div class="absolute top-4 right-4 w-10 h-10 rounded-full bg-black/30 backdrop-blur-md flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <span class="material-symbols-outlined text-white text-xl">zoom_in</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="py-20 text-center bg-slate-50 rounded-3xl border border-slate-100">
            <span class="material-symbols-outlined text-6xl text-slate-300 mb-4 block">photo_library</span>
            <p class="text-slate-500 font-medium text-lg">Bu kulübün galerisinde henüz görsel bulunmuyor.</p>
        </div>
    @endif
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

@endsection

@push('scripts')
<script>
    let currentImageIndex = 0;
    const galleryItems = [];
    
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.gallery-thumb').forEach((img) => {
            galleryItems.push(img.getAttribute('data-full'));
        });
    });

    function openLightbox(index) {
        if(galleryItems.length === 0) return;
        currentImageIndex = index;
        updateLightbox();
        const modal = document.getElementById('lightbox-modal');
        modal.classList.remove('hidden');
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        document.getElementById('lightbox-img').classList.remove('scale-95');
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
        img.src = galleryItems[currentImageIndex];
        document.getElementById('lightbox-counter-current').innerText = currentImageIndex + 1;
        document.getElementById('lightbox-counter-total').innerText = galleryItems.length;
    }

    function nextImage() {
        if(galleryItems.length === 0) return;
        currentImageIndex = (currentImageIndex + 1) % galleryItems.length;
        updateLightbox();
    }

    function prevImage() {
        if(galleryItems.length === 0) return;
        currentImageIndex = (currentImageIndex - 1 + galleryItems.length) % galleryItems.length;
        updateLightbox();
    }

    // Keyboard support
    document.addEventListener('keydown', (e) => {
        const modal = document.getElementById('lightbox-modal');
        if (!modal || modal.classList.contains('hidden')) return;
        
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') prevImage();
        if (e.key === 'Escape') closeLightbox();
    });
</script>
@endpush
