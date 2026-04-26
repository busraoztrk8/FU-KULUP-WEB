@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center gap-3 md:gap-4 my-8">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full bg-slate-50 text-slate-200 cursor-not-allowed">
                <span class="material-symbols-outlined text-lg md:text-xl">chevron_left</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full bg-white border border-slate-100 text-slate-400 hover:text-primary hover:border-primary/20 hover:shadow-md transition-all active:scale-95" aria-label="{{ __('pagination.previous') }}">
                <span class="material-symbols-outlined text-lg md:text-xl">chevron_left</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="flex items-center gap-2 md:gap-3">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true" class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center text-slate-300 font-bold">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="w-12 h-12 md:w-14 md:h-14 flex items-center justify-center rounded-full bg-primary-dark text-white font-bold text-base md:text-lg shadow-[0_10px_25px_-5px_rgba(0,0,0,0.3)] z-10">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-600 font-bold text-sm md:text-base hover:text-primary hover:border-primary/20 hover:shadow-md transition-all active:scale-95" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full bg-white border border-slate-200 text-slate-400 hover:text-primary hover:border-primary/20 hover:shadow-md transition-all active:scale-95" aria-label="{{ __('pagination.next') }}">
                <span class="material-symbols-outlined text-lg md:text-xl">chevron_right</span>
            </a>
        @else
            <span class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full bg-slate-50 text-slate-200 cursor-not-allowed">
                <span class="material-symbols-outlined text-lg md:text-xl">chevron_right</span>
            </span>
        @endif
    </nav>
@endif
