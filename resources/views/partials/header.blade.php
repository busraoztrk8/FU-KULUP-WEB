<!-- Header -->
<header id="main-nav"
    class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl border-b border-black/5 transition-all duration-300">
    <nav class="flex justify-between items-center px-4 sm:px-6 md:px-8 py-3 md:py-4 max-w-7xl mx-auto">

        <!-- Logo / Branding -->
        <a href="{{ route('home') }}" class="flex items-center gap-2 md:gap-3 group shrink-0">
            <img src="{{ asset('images/logo_orj.png') }}" alt="FIRAT ÜNİVERSİTESİ Logo"
                class="h-8 md:h-10 w-auto object-contain">
            <div
                class="text-sm sm:text-base md:text-xl font-bold bg-gradient-to-r from-[#5d1021] to-[#8b1d35] bg-clip-text text-transparent font-headline tracking-tight">
                FIRAT ÜNİVERSİTESİ
            </div>
        </a>

        <!-- Desktop Navigation (Center) -->
        <div class="hidden md:flex items-center space-x-8 font-headline font-bold">
            @foreach($mainMenus as $menu)
                @if($menu->children->count() > 0)
                    <div class="relative group">
                        <button class="flex items-center gap-1 nav-link text-on-surface opacity-70 group-hover:text-primary transition-all">
                            {{ $menu->label }}
                            <span class="material-symbols-outlined text-[18px] group-hover:rotate-180 transition-transform duration-300">expand_more</span>
                        </button>
                        <div class="absolute top-full left-0 pt-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                            <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 py-2.5 min-w-[220px] overflow-hidden">
                                @foreach($menu->children as $child)
                                    <a href="{{ $child->url }}" target="{{ $child->target }}" class="flex items-center gap-3 px-5 py-3 text-sm font-bold text-slate-700 hover:bg-primary/5 hover:text-primary transition-all">
                                        <div class="w-1.5 h-1.5 rounded-full bg-primary/20 group-hover:bg-primary transition-colors"></div>
                                        {{ $child->label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ $menu->url }}" target="{{ $menu->target }}"
                        class="nav-link {{ request()->url() == url($menu->url) ? 'active text-primary' : 'text-on-surface opacity-70' }}">
                        {{ $menu->label }}
                    </a>
                @endif
            @endforeach
        </div>

        <!-- Right Actions -->
        <div class="flex items-center space-x-2 sm:space-x-4 md:space-x-6">
            <div class="flex items-center space-x-2 sm:space-x-4 text-on-surface">
                <span
                    class="material-symbols-outlined cursor-pointer hover:text-primary transition-colors hidden sm:inline">notifications</span>
                <span
                    class="text-xs font-bold cursor-pointer hover:text-primary transition-colors border-r border-black/10 pr-2 sm:pr-4 mr-1 sm:mr-2 hidden sm:inline">TR/EN</span>

                @auth
                    <!-- User Dropdown Component -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 py-1 px-1 rounded-full hover:bg-slate-50 transition-all">
                            <div
                                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-primary border border-slate-200 overflow-hidden shadow-sm">
                                @if(auth()->user()->profile_photo)
                                    <img src="{{ asset('uploads/' . auth()->user()->profile_photo) }}"
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
                                    <a href="{{ route('dashboard') }}"
                                        class="flex items-center gap-3 px-3 py-2.5 text-sm font-bold text-slate-700 rounded-xl hover:bg-slate-50 hover:text-primary transition-all">
                                        <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span> Yönetim Paneli
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
                class="md:hidden w-10 h-10 rounded-xl flex items-center justify-center bg-slate-100 text-slate-600 active:bg-slate-200 transition-colors">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </nav>
</header>

<!-- Mobile Menu Overlay -->
<div id="mob-overlay"
    class="mobile-menu-overlay md:hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] opacity-0 pointer-events-none transition-opacity duration-300">
</div>

<!-- Mobile Menu Side Panel -->
<div id="mob-menu" class="mobile-menu md:hidden flex flex-col bg-white shadow-2xl transition-all duration-300 ease-out">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between shrink-0">
        <span class="text-lg font-bold font-headline text-slate-800">Menü</span>
        <button id="mob-close"
            class="w-10 h-10 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <div class="flex-1 overflow-y-auto p-6 flex flex-col">
        <nav class="space-y-2 mb-8">
            @foreach($mainMenus as $menu)
                @if($menu->children->count() > 0)
                    <div x-data="{ open: false }">
                        <button @click="open = !open" class="w-full flex items-center justify-between p-4 rounded-2xl font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined">menu_open</span> {{ $menu->label }}
                            </div>
                            <span class="material-symbols-outlined transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="open" class="pl-8 space-y-1 mt-1">
                            @foreach($menu->children as $child)
                                <a href="{{ $child->url }}" target="{{ $child->target }}" class="block p-3 rounded-xl font-bold text-sm text-slate-500 hover:bg-slate-50 hover:text-primary transition-colors">
                                    {{ $child->label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $menu->url }}" target="{{ $menu->target }}"
                        class="flex items-center gap-3 p-4 rounded-2xl font-bold {{ request()->url() == url($menu->url) ? 'bg-primary/5 text-primary' : 'text-slate-600 hover:bg-slate-50' }} transition-colors">
                        <span class="material-symbols-outlined">link</span> {{ $menu->label }}
                    </a>
                @endif
            @endforeach
        </nav>

        <div class="mt-auto space-y-3">
            @auth
                <div class="bg-slate-50 rounded-2xl p-4 mb-4">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Profil</p>
                    <div class="flex items-center gap-3 mb-4">
                        <div
                            class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-primary overflow-hidden">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('uploads/' . auth()->user()->profile_photo) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="material-symbols-outlined">person</span>
                            @endif
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-3 p-4 rounded-2xl bg-red-50 text-red-600 font-bold hover:bg-red-100 transition-colors">
                        <span class="material-symbols-outlined">logout</span> Çıkış Yap
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                    class="flex items-center justify-center p-4 rounded-2xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors">Giriş
                    Yap</a>
                <a href="{{ route('register') }}"
                    class="flex items-center justify-center p-4 rounded-2xl bg-gradient-primary text-white font-bold hover:opacity-90 transition-all shadow-lg shadow-red-900/10">Kayıt
                    Ol</a>
            @endauth
        </div>
    </div>
</div>