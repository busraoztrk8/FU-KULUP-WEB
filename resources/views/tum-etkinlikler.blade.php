@extends('layouts.app')

@section('title', ($filteredClub ? $filteredClub->name . ' - ' : '') . __('site.all_events_title') . ' - Fırat Üniversitesi')
@section('data-page', 'tum-etkinlikler')
@section('page-title', __('site.all_events_title'))

@section('content')
    <!-- Hero Section -->
    <section class="bg-surface-container py-12 md:py-20 px-4 sm:px-6 relative overflow-hidden">
        <div class="absolute right-0 top-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute left-0 bottom-0 w-48 h-48 bg-primary/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>
        <div class="max-w-7xl mx-auto text-center relative z-10">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold font-headline text-on-background mb-4 uppercase tracking-tight">
                {{ $filteredClub ? $filteredClub->name . ' ' . __('site.events_page_title') : __('site.all_events_title') }}
            </h1>
            <p class="text-on-surface-variant text-base md:text-xl max-w-2xl mx-auto font-body">
                {{ $filteredClub ? $filteredClub->name . ' ' . __('site.club_all_events_desc') : __('site.all_events_desc') }}
            </p>
            
            <!-- Search Bar -->
            <div class="mt-8 max-w-xl mx-auto relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                <input type="text" id="event-search" placeholder="{{ __('site.search_events_placeholder') }}" 
                    class="w-full bg-white border border-black/10 rounded-2xl pl-12 pr-4 py-4 text-sm focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all outline-none shadow-sm font-bold">
            </div>

            <div class="mt-6 flex items-center justify-center gap-3">
                <span class="bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-bold">
                    <span class="material-symbols-outlined text-sm align-middle mr-1">event</span>
                    {!! __('site.total_events_count', ['count' => '<span id="total-count">' . $totalEvents . '</span>']) !!}
                </span>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 py-10 md:py-16">
        <div id="all-events-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 min-h-[400px] transition-opacity duration-300">
            @include('partials.event-grid-list', ['events' => $events])
        </div>
        
        <!-- Pagination -->
        <div id="pagination-container" class="mt-12 md:mt-16">
            @if($events->hasPages())
                {{ $events->links('partials.custom-pagination') }}
            @endif
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let searchTimer;
            const searchInput = document.getElementById('event-search');
            const resultsContainer = document.getElementById('all-events-grid');
            const paginationContainer = document.getElementById('pagination-container');
            const totalCountSpan = document.getElementById('total-count');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    let search = this.value;
                    
                    searchTimer = setTimeout(function() {
                        fetchEvents(search, 1);
                    }, 500);
                });
            }

            document.addEventListener('click', function(e) {
                const paginationLink = e.target.closest('#pagination-container a');
                if (paginationLink) {
                    e.preventDefault();
                    let url = new URL(paginationLink.href);
                    let page = url.searchParams.get('page');
                    let search = searchInput ? searchInput.value : '';
                    
                    fetchEvents(search, page);
                    
                    window.scrollTo({
                        top: resultsContainer.offsetTop - 120,
                        behavior: 'smooth'
                    });
                }
            });

            function fetchEvents(search, page) {
                resultsContainer.style.opacity = '0.5';
                
                let url = new URL("{{ route('tum-etkinlikler') }}");
                url.searchParams.append('search', search);
                url.searchParams.append('page', page);
                
                const currentUrl = new URL(window.location.href);
                const club = currentUrl.searchParams.get('club');
                if (club) {
                    url.searchParams.append('club', club);
                }

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = data.html;
                    paginationContainer.innerHTML = data.pagination;
                    if (totalCountSpan) totalCountSpan.textContent = data.total;
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
