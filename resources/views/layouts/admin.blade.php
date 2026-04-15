<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo_orj.png') }}">
    <title>{{ View::getSection('title', 'Admin') }} - Fırat Üniversitesi Yönetim Paneli</title>
    
    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="{{ asset('js/tailwind-config.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"/>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    @stack('styles')
</head>
<body class="admin-body font-body" data-page="{{ View::getSection('data-page', 'dashboard') }}">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside id="admin-sidebar" class="admin-sidebar shrink-0 hidden lg:flex flex-col h-full">
        @include('admin.partials.sidebar', ['page' => $page ?? 'dashboard'])
    </aside>
    
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-50 hidden lg:hidden"></div>

    <!-- Main Content -->
    <div class="flex-1 min-w-0 flex flex-col overflow-hidden">
        <!-- Header -->
        <header id="admin-header" class="bg-white border-b border-slate-100 shrink-0">
            @include('admin.partials.header')
        </header>

        <!-- Main View Area -->
        <main class="flex-1 min-w-0 overflow-y-auto p-6 md:p-8">
            @yield('content')
        </main>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/admin.js') }}"></script>
@stack('scripts')

</body>
</html>
