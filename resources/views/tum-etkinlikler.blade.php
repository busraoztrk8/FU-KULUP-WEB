@extends('layouts.app')

@section('title', 'Tüm Etkinlikler - Fırat Üniversitesi')
@section('data-page', 'tum-etkinlikler')
@section('page-title', 'Tüm Etkinlikler')

@section('content')
    <!-- Hero Section -->
    <section class="bg-surface-container py-12 md:py-20 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute left-0 bottom-0 w-48 h-48 bg-primary/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold font-headline text-on-background mb-4">Tüm Etkinlikler</h1>
            <p class="text-on-surface-variant text-base md:text-xl max-w-2xl mx-auto font-body">Üniversitemizdeki tüm yaklaşan ve geçmiş etkinlikleri takip edin.</p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <span class="bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-bold">
                    <span class="material-symbols-outlined text-sm align-middle mr-1">event</span>
                    Toplam {{ $totalEvents }} Etkinlik
                </span>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 md:py-16">
        <div id="all-events-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @include('partials.event-grid-list', ['events' => $events])

            @if($events->isEmpty())
            <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-16 bg-slate-50 border border-dashed border-slate-200 rounded-3xl">
                <span class="material-symbols-outlined text-6xl text-slate-300 mb-4 inline-block">event_busy</span>
                <h3 class="text-xl font-bold text-slate-600 mb-2">Henüz Etkinlik Bulunmuyor</h3>
                <p class="text-slate-400">Şu anda planlanmış bir etkinlik verisi bulunamadı.</p>
            </div>
            @endif
        </div>
        
        <!-- AJAX Load More Button -->
        @if($events->count() >= 15)
        <div id="tum-load-more-container" class="mt-12 flex justify-center">
            <button id="tum-load-more-btn"
                data-offset="15"
                data-limit="15"
                class="bg-white hover:bg-slate-50 text-primary px-8 md:px-10 py-3 md:py-4 rounded-full font-bold transition-all border border-primary/20 flex items-center gap-2 shadow-lg hover:shadow-xl text-sm md:text-base group">
                <span id="tum-btn-text">Daha Fazla Yükle</span>
                <span id="tum-btn-icon" class="material-symbols-outlined text-sm group-hover:translate-y-1 transition-transform">expand_more</span>
                <span id="tum-btn-spinner" class="hidden">
                    <svg class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
        @endif
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreBtn = document.getElementById('tum-load-more-btn');
            const grid = document.getElementById('all-events-grid');

            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function() {
                    const offset = parseInt(this.getAttribute('data-offset'));
                    const limit = parseInt(this.getAttribute('data-limit'));
                    const btnText = document.getElementById('tum-btn-text');
                    const btnIcon = document.getElementById('tum-btn-icon');
                    const btnSpinner = document.getElementById('tum-btn-spinner');

                    // Loading state
                    loadMoreBtn.disabled = true;
                    btnText.innerText = 'Yükleniyor...';
                    btnIcon.classList.add('hidden');
                    btnSpinner.classList.remove('hidden');

                    fetch(`{{ route('tum-etkinlikler') }}?offset=${offset}&limit=${limit}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.html && data.html.trim() !== "") {
                            // Create temp container to parse new elements
                            const temp = document.createElement('div');
                            temp.innerHTML = data.html;
                            const newElements = temp.querySelectorAll('a');

                            // Animate new items in
                            newElements.forEach((el, index) => {
                                el.style.opacity = '0';
                                el.style.transform = 'translateY(20px)';
                                grid.appendChild(el);

                                // Stagger animation
                                setTimeout(() => {
                                    el.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                                    el.style.opacity = '1';
                                    el.style.transform = 'translateY(0)';
                                }, index * 80);
                            });

                            // Update offset
                            this.setAttribute('data-offset', offset + data.count);

                            // Check if more data exists
                            if (!data.has_more) {
                                loadMoreBtn.parentElement.innerHTML = `
                                    <div class="text-center text-on-surface-variant py-4">
                                        <span class="material-symbols-outlined text-2xl text-primary mb-2 block">check_circle</span>
                                        <p class="font-medium">Tüm etkinlikler yüklendi</p>
                                    </div>
                                `;
                            }
                        } else {
                            loadMoreBtn.parentElement.innerHTML = `
                                <div class="text-center text-on-surface-variant py-4">
                                    <span class="material-symbols-outlined text-2xl text-primary mb-2 block">check_circle</span>
                                    <p class="font-medium">Tüm etkinlikler yüklendi</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading more events:', error);
                        btnText.innerText = 'Hata Oluştu, Tekrar Dene';
                    })
                    .finally(() => {
                        loadMoreBtn.disabled = false;
                        btnSpinner.classList.add('hidden');
                        btnIcon.classList.remove('hidden');
                        btnText.innerText = 'Daha Fazla Yükle';
                    });
                });
            }
        });
    </script>
    @endpush
@endsection
