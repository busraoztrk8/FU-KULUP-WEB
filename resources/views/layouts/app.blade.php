<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo_orj.png') }}">
    <title>{{ View::getSection('title', __('site.university_name') . ' - ' . __('site.hero_title')) }}</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="{{ asset('js/tailwind-config.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>

<body class="bg-background selection:bg-primary/20 font-body antialiased" data-page="{{ View::getSection('data-page', '') }}">

    @include('partials.header')

    @if(View::hasSection('page-title'))
        @include('partials.page-header')
    @endif

    <main class="pt-[80px]">
        {{-- Flash Messages --}}
        @if(session('warning'))
            <div class="max-w-4xl mx-auto mt-4 px-4">
                <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-2xl text-amber-800 text-sm font-medium shadow-sm" x-data="{ show: true }" x-show="show" x-transition>
                    <span class="material-symbols-outlined text-amber-500 text-[22px] shrink-0 mt-0.5">warning</span>
                    <p class="flex-1">{{ session('warning') }}</p>
                    <button @click="show = false" class="text-amber-400 hover:text-amber-600 transition-colors shrink-0">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="max-w-4xl mx-auto mt-4 px-4">
                <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-800 text-sm font-medium shadow-sm" x-data="{ show: true }" x-show="show" x-transition>
                    <span class="material-symbols-outlined text-red-500 text-[22px] shrink-0 mt-0.5">error</span>
                    <p class="flex-1">{{ session('error') }}</p>
                    <button @click="show = false" class="text-red-400 hover:text-red-600 transition-colors shrink-0">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>
        @endif
        @if(session('success'))
            <div class="max-w-4xl mx-auto mt-4 px-4">
                <div class="flex items-start gap-3 p-4 bg-green-50 border border-green-200 rounded-2xl text-green-800 text-sm font-medium shadow-sm" x-data="{ show: true }" x-show="show" x-transition>
                    <span class="material-symbols-outlined text-green-500 text-[22px] shrink-0 mt-0.5">check_circle</span>
                    <p class="flex-1">{{ session('success') }}</p>
                    <button @click="show = false" class="text-green-400 hover:text-green-600 transition-colors shrink-0">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>
        @endif
        {{-- Blade component slot (x-app-layout) --}}
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </main>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>