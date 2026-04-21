@extends('layouts.app')

@section('title', 'Tüm Duyurular - Fırat Üniversitesi')
@section('data-page', 'tum-duyurular')

@section('content')
    <!-- Hero Section -->
    <section class="bg-surface-container py-12 md:py-20 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-amber-600/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute left-0 bottom-0 w-48 h-48 bg-amber-600/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold font-headline text-on-background mb-4 uppercase tracking-tight">Tüm Duyurular</h1>
            <p class="text-on-surface-variant text-base md:text-xl max-w-2xl mx-auto font-body">Üniversitemiz ve kulüplerimizden güncel haber ve duyuruları takip edin.</p>
            
            <!-- Search Bar -->
            <div class="mt-8 max-w-xl mx-auto relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                <input type="text" id="announcement-search" placeholder="Duyuru başlığı, içeriği veya kulüp adı ile ara..." 
                    class="w-full bg-white border border-black/10 rounded-2xl pl-12 pr-4 py-4 text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none shadow-sm font-bold">
            </div>

            <div class="mt-6 flex items-center justify-center gap-3">
                <span class="bg-amber-600/10 text-amber-600 px-4 py-2 rounded-full text-sm font-bold">
                    <span class="material-symbols-outlined text-sm align-middle mr-1">campaign</span>
                    Toplam <span id="total-count">{{ $totalAnnouncements }}</span> Duyuru
                </span>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 md:py-16">
        <div id="announcement-results" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 min-h-[300px] transition-opacity duration-300">
            @include('partials.announcement-grid-items', ['announcements' => $announcements])
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let searchTimer;
            const searchInput = document.getElementById('announcement-search');
            const resultsContainer = document.getElementById('announcement-results');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    let search = this.value;
                    
                    searchTimer = setTimeout(function() {
                        fetchAnnouncements(search, 1);
                    }, 500);
                });
            }

            document.addEventListener('click', function(e) {
                const paginationLink = e.target.closest('.ajax-pagination a');
                if (paginationLink) {
                    e.preventDefault();
                    let url = new URL(paginationLink.href);
                    let page = url.searchParams.get('page');
                    let search = searchInput ? searchInput.value : '';
                    
                    fetchAnnouncements(search, page);
                    
                    window.scrollTo({
                        top: resultsContainer.offsetTop - 120,
                        behavior: 'smooth'
                    });
                }
            });

            function fetchAnnouncements(search, page) {
                resultsContainer.style.opacity = '0.5';
                
                let url = new URL("{{ route('tum-duyurular') }}");
                url.searchParams.append('search', search);
                url.searchParams.append('page', page);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    resultsContainer.innerHTML = html;
                    resultsContainer.style.opacity = '1';
                })
                .catch(error => {
                    console.error('Error:', error);
                    resultsContainer.style.opacity = '1';
                });
            }
        });
    </script>
    @endpush
@endsection
