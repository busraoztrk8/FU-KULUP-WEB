<!-- Header -->
<header id="main-nav"
    class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl border-b border-black/5 transition-all duration-300">
    <nav class="relative flex justify-between items-center px-4 sm:px-6 md:px-8 py-3 md:py-4 max-w-7xl mx-auto h-full">

        <!-- Logo / Branding -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 md:gap-3 group shrink-0 relative z-10">
            <img src="{{ asset('images/logo_orj.png') }}" alt="FIRAT ÜNİVERSİTESİ Logo"
                class="h-8 md:h-10 w-auto object-contain">
            <div
                class="text-sm sm:text-base md:text-xl font-bold bg-gradient-to-r from-[#5d1021] to-[#8b1d35] bg-clip-text text-transparent font-headline tracking-tight">
                FIRAT ÜNİVERSİTESİ
            </div>
        </a>

        <!-- Desktop Navigation (Center) -->
        <div class="hidden lg:flex items-center space-x-8 font-headline font-bold absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
            @foreach($mainMenus as $menu)
                @if($menu->children->count() > 0)
                    <div class="relative group">
                        <button
                            class="flex items-center gap-1 nav-link text-slate-500 hover:text-primary transition-colors">
                            {{ $menu->label }}
                            <span
                                class="material-symbols-outlined text-[18px] group-hover:rotate-180 transition-transform duration-300">expand_more</span>
                        </button>
                        <div
                            class="absolute top-[calc(100%-10px)] left-0 pt-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:top-full transition-all duration-300 z-50">
                            <div
                                class="bg-white/95 backdrop-blur-xl rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] border border-white/20 p-2 min-w-[240px] overflow-hidden">
                                {{-- Ana menü linkini alt menülerin başına ekle --}}
                                <a href="{{ $menu->url }}" target="{{ $menu->target }}"
                                    class="flex items-center justify-between px-4 py-3 mb-1 rounded-xl bg-gradient-to-r from-primary/5 to-transparent hover:from-primary/10 transition-all group/parent">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover/parent:scale-110 transition-transform">
                                            <span class="material-symbols-outlined text-[20px]">grid_view</span>
                                        </div>
                                        <div class="flex flex-col text-left">
                                            <span class="text-sm font-bold text-slate-800 leading-none">Tüm
                                                {{ $menu->label }}</span>
                                            <span
                                                class="text-[10px] text-slate-400 mt-1 uppercase tracking-wider font-bold">Genel
                                                Bakış</span>
                                        </div>
                                    </div>
                                    <span
                                        class="material-symbols-outlined text-slate-300 group-hover/parent:text-primary transition-colors text-[20px]">arrow_forward</span>
                                </a>

                                <div class="px-2 py-1 flex items-center gap-2 mb-1">
                                    <div class="h-px bg-slate-100 flex-1"></div>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest px-1">Alt
                                        Sayfalar</span>
                                    <div class="h-px bg-slate-100 flex-1"></div>
                                </div>

                                @foreach($menu->children as $child)
                                    <a href="{{ $child->url }}" target="{{ $child->target }}"
                                        class="flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-slate-600 rounded-xl hover:bg-slate-50 hover:text-primary transition-all group/item">
                                        <div
                                            class="w-1.5 h-1.5 rounded-full bg-slate-200 group-hover/item:bg-primary group-hover/item:scale-125 transition-all">
                                        </div>
                                        {{ $child->label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ $menu->url }}" target="{{ $menu->target }}"
                        class="nav-link {{ request()->url() == url($menu->url) ? 'active text-primary' : 'text-slate-500' }}">
                        {{ $menu->label }}
                    </a>
                @endif
            @endforeach
        </div>

        <!-- Right Actions -->
        <div class="flex items-center space-x-2 sm:space-x-4 md:space-x-6 relative z-10">
            <div class="flex items-center space-x-2 sm:space-x-4 text-on-surface">

                <span
                    class="text-xs font-bold cursor-pointer hover:text-primary transition-colors border-r border-black/10 pr-2 sm:pr-4 mr-1 sm:mr-2 hidden sm:inline">TR/EN</span>

                @auth
                    <!-- User Dropdown Component -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 py-1 px-1 rounded-full hover:bg-slate-50 transition-all">
                            <div
                                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-primary border border-slate-200 overflow-hidden shadow-sm">
                                @if(auth()->user()->profile_photo)
                                    @php
                                        $photo = auth()->user()->profile_photo;
                                        $photoUrl = file_exists(public_path('uploads/' . $photo)) ? asset('uploads/' . $photo) : asset('storage/' . $photo);
                                    @endphp
                                    <img src="{{ $photoUrl }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-[20px]">account_circle</span>
                                @endif
                            </div>
                        </button>
                        <!-- Dropdown panel -->
                        <div
                            class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[60]">
                            <div class="px-5 py-4 border-b border-slate-50">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="p-2 space-y-1">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-slate-700 rounded-xl hover:bg-slate-50 hover:text-primary transition-all">
                                    <span class="material-symbols-outlined text-[20px]">person</span> Profilim
                                </a>
                                @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                                    <a href="/admin"
                                        class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-white bg-primary rounded-xl hover:bg-primary-dim transition-all shadow-sm shadow-primary/20">
                                        <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span> Yönetim
                                        Paneli
                                    </a>
                                @endif
                            </div>
                            <div class="border-t border-slate-50 my-1"></div>
                            <div class="p-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-red-500 rounded-xl hover:bg-red-50 transition-colors text-left">
                                        <span class="material-symbols-outlined text-[20px]">logout</span> Çıkış Yap
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <span
                        class="material-symbols-outlined cursor-pointer hover:text-primary transition-colors hidden sm:inline">account_circle</span>
                @endauth
            </div>

            @guest
                <a href="{{ route('register') }}"
                    class="bg-gradient-primary text-white px-4 sm:px-6 py-2 rounded-full font-bold text-xs sm:text-sm scale-95 active:scale-90 transition-transform shadow-lg shadow-primary/20 block text-center whitespace-nowrap">
                    Kayıt Ol
                </a>
            @endguest

            <!-- Mobile UI Button -->
            <button id="mob-menu-btn"
                class="lg:hidden w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-600 active:bg-slate-200 transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </nav>
</header>

<!-- Mobile Menu Overlay -->
<div id="mob-overlay"
    class="mobile-menu-overlay lg:hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] opacity-0 pointer-events-none transition-opacity duration-300">
</div>

<!-- Mobile Menu Side Panel -->
<div id="mob-menu" class="mobile-menu lg:hidden flex flex-col">
    <!-- Header Area -->
    <div class="p-6 flex items-center justify-between shrink-0 bg-white/40 border-b border-black/5">
        <div class="flex items-center gap-3">
            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
            <span class="text-xl font-bold font-headline tracking-tight text-slate-800">Menü</span>
        </div>
        <button id="mob-close"
            class="w-10 h-10 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100/50 hover:text-primary transition-all">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    <!-- Navigation Area -->
    <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
        <nav class="space-y-1.5">
            @foreach($mainMenus as $menu)
                @php
                    $label = mb_strtolower($menu->label, 'UTF-8');
                    $icon = 'link';
                    if (str_contains($label, 'ana')) $icon = 'home';
                    elseif (str_contains($label, 'kulüp')) $icon = 'groups';
                    elseif (str_contains($label, 'etkinlik')) $icon = 'event_note';
                    elseif (str_contains($label, 'haber')) $icon = 'newspaper';
                    elseif (str_contains($label, 'duyuru')) $icon = 'campaign';
                    elseif (str_contains($label, 'galeri')) $icon = 'photo_library';
                    elseif (str_contains($label, 'hakkımızda')) $icon = 'info';
                    elseif (str_contains($label, 'iletişim')) $icon = 'alternate_email';
                @endphp

                @if($menu->children->count() > 0)
                    <div x-data="{ open: false }" class="mb-2">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between p-3.5 rounded-2xl font-bold text-slate-600 hover:bg-primary/5 hover:text-primary transition-all group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-primary/10 group-hover:text-primary transition-all">
                                    <span class="material-symbols-outlined text-[22px]">{{ $icon }}</span>
                                </div>
                                <span class="text-sm font-headline lowercase first-letter:uppercase">{{ $menu->label }}</span>
                            </div>
                            <span class="material-symbols-outlined text-[18px] opacity-40 transition-transform"
                                :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="open" x-collapse
                            class="ml-5 mt-1 pb-2 space-y-1.5 border-l-2 border-slate-100 pl-4">
                            @foreach($menu->children as $child)
                                <a href="{{ $child->url }}" target="{{ $child->target }}"
                                    class="flex items-center gap-3 p-3 rounded-xl font-bold text-[13px] text-slate-500 hover:text-primary hover:bg-primary/5 transition-all">
                                    {{ $child->label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $menu->url }}" target="{{ $menu->target }}"
                        class="mobile-nav-link flex items-center gap-3 p-3.5 rounded-2xl font-bold {{ request()->url() == url($menu->url) ? 'active' : 'text-slate-600 hover:bg-primary/5 hover:text-primary' }} transition-all group">
                        <div class="w-10 h-10 rounded-xl {{ request()->url() == url($menu->url) ? 'bg-primary/10 text-primary' : 'bg-slate-50 text-slate-400' }} flex items-center justify-center group-hover:bg-primary/10 group-hover:text-primary transition-all">
                            <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                        </div>
                        <span class="text-sm font-headline lowercase first-letter:uppercase">{{ $menu->label }}</span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>

    <!-- Footer / Profile Area -->
    <div class="p-6 bg-white/40 border-t border-black/5 mt-auto">
        @auth
            <div class="glass-card rounded-2xl p-4 border border-white/50 mb-4 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full border-2 border-primary/20 p-0.5">
                        <div class="w-full h-full rounded-full bg-slate-100 overflow-hidden shadow-inner">
                            @if(auth()->user()->profile_photo)
                                @php
                                    $photo = auth()->user()->profile_photo;
                                    $photoUrl = file_exists(public_path('uploads/' . $photo)) ? asset('uploads/' . $photo) : asset('storage/' . $photo);
                                @endphp
                                <img src="{{ $photoUrl }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">person</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-[10px] font-bold text-primary/60 uppercase tracking-widest leading-none mb-1">Hesabım</p>
                        <p class="text-sm font-bold text-slate-800 truncate font-headline">{{ auth()->user()->name }}</p>
                    </div>
                </div>
                
                @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                    <a href="/admin"
                        class="flex items-center justify-center gap-2 w-full py-3 bg-gradient-primary text-white text-xs font-bold rounded-xl shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span> Yönetim Paneli
                    </a>
                @endif
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl bg-red-50 text-red-600 text-xs font-black uppercase tracking-widest hover:bg-red-100 transition-all">
                    <span class="material-symbols-outlined text-[20px]">logout</span> Çıkış Yap
                </button>
            </form>
        @else
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('login') }}"
                    class="flex items-center justify-center py-4 rounded-2xl border border-slate-200 text-slate-600 text-xs font-bold hover:bg-slate-50 transition-colors uppercase tracking-widest">
                    Giriş
                </a>
                <a href="{{ route('register') }}"
                    class="flex items-center justify-center py-4 rounded-2xl bg-gradient-primary text-white text-xs font-bold hover:shadow-xl hover:shadow-primary/30 transition-all uppercase tracking-widest">
                    Kayıt
                </a>
            </div>
        @endauth
    </div>
</div>