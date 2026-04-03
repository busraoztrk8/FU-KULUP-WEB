<!-- Header -->
<header id="main-nav"
    class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-xl border-b border-black/5 transition-all duration-300">
    <nav class="flex justify-between items-center px-8 py-4 max-w-7xl mx-auto">

        <!-- Logo -->
        <a href="{{ route('home') }}"
            class="text-xl font-bold bg-gradient-to-r from-[#5d1021] to-[#8b1d35] bg-clip-text text-transparent font-headline tracking-tight">
            FIRAT ÜNİVERSİTESİ
        </a>

        <!-- Desktop Nav -->
        <div class="hidden md:flex items-center space-x-8 font-headline font-bold">
            <a class="{{ request()->routeIs('home') ? 'text-primary relative after:content-[\'\'] after:absolute after:-bottom-1 after:left-0 after:w-full after:h-0.5 after:bg-primary' : 'text-on-surface opacity-70 hover:text-primary hover:opacity-100' }} transition-all duration-300"
                href="{{ route('home') }}">Ana Sayfa</a>
            <a class="{{ request()->routeIs('etkinlikler') ? 'text-primary relative after:content-[\'\'] after:absolute after:-bottom-1 after:left-0 after:w-full after:h-0.5 after:bg-primary' : 'text-on-surface opacity-70 hover:text-primary hover:opacity-100' }} transition-all duration-300"
                href="{{ route('etkinlikler') }}">Etkinlikler</a>
            <a class="{{ request()->routeIs('kulupler') ? 'text-primary relative after:content-[\'\'] after:absolute after:-bottom-1 after:left-0 after:w-full after:h-0.5 after:bg-primary' : 'text-on-surface opacity-70 hover:text-primary hover:opacity-100' }} transition-all duration-300"
                href="{{ route('kulupler') }}">Kulüpler</a>
        </div>

        <!-- Right Actions -->
        <div class="flex items-center gap-4">

            @auth
                {{-- Giriş yapılmış: kullanıcı menüsü --}}
                <div class="flex items-center gap-3">
                    @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                        <a href="{{ route('admin.index') }}"
                            class="hidden md:flex items-center gap-1.5 text-sm font-bold text-primary hover:text-primary-dark transition-colors">
                            <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span>
                            Admin Panel
                        </a>
                    @endif

                    <div class="relative group">
                        <button class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-full transition-all">
                            <span class="material-symbols-outlined text-primary text-[20px]">account_circle</span>
                            <span class="hidden md:block text-sm font-bold text-slate-700">{{ Str::limit(auth()->user()->name, 12) }}</span>
                            <span class="material-symbols-outlined text-slate-400 text-[16px]">expand_more</span>
                        </button>
                        <!-- Dropdown -->
                        <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">dashboard</span>
                                Dashboard
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                                Profilim
                            </a>
                            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                            <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[18px]">settings</span>
                                Yönetim Paneli
                            </a>
                            @endif
                            <div class="border-t border-slate-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-500 hover:bg-red-50 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">logout</span>
                                    Çıkış Yap
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                {{-- Giriş yapılmamış --}}
                <div class="hidden md:flex items-center gap-3">
                    <a href="{{ route('login') }}"
                        class="text-sm font-bold text-slate-600 hover:text-primary transition-colors px-4 py-2">
                        Giriş Yap
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-gradient-primary text-white px-6 py-2.5 rounded-full font-bold text-sm hover:opacity-90 active:scale-95 transition-all shadow-lg shadow-primary/20">
                        Kayıt Ol
                    </a>
                </div>
            @endauth

            <!-- Mobile menu button -->
            <button id="mob-menu-btn" class="md:hidden text-on-surface opacity-70">
                <span class="material-symbols-outlined">menu</span>
            </button>
        </div>
    </nav>
</header>

<!-- Mobile Menu Overlay -->
<div id="mob-overlay" class="mobile-menu-overlay md:hidden"></div>
<div id="mob-menu" class="mobile-menu md:hidden">
    <div class="flex justify-between items-center mb-8">
        <span class="text-xl font-bold font-headline text-on-surface">Menü</span>
        <button id="mob-close" class="text-on-surface opacity-70 hover:text-primary transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <div class="flex flex-col">
        <a class="{{ request()->routeIs('home') ? 'text-primary' : 'text-on-surface opacity-70' }} font-bold text-lg transition-colors py-4 border-b border-black/5"
            href="{{ route('home') }}">Ana Sayfa</a>
        <a class="{{ request()->routeIs('etkinlikler') ? 'text-primary' : 'text-on-surface opacity-70' }} font-bold text-lg transition-colors py-4 border-b border-black/5"
            href="{{ route('etkinlikler') }}">Etkinlikler</a>
        <a class="{{ request()->routeIs('kulupler') ? 'text-primary' : 'text-on-surface opacity-70' }} font-bold text-lg transition-colors py-4 border-b border-black/5"
            href="{{ route('kulupler') }}">Kulüpler</a>

        @auth
            <a href="{{ route('dashboard') }}" class="text-on-surface opacity-70 font-bold text-lg transition-colors py-4 border-b border-black/5">Dashboard</a>
            @if(auth()->user()->isAdmin() || auth()->user()->isEditor())
                <a href="{{ route('admin.index') }}" class="text-primary font-bold text-lg transition-colors py-4 border-b border-black/5">Yönetim Paneli</a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="py-4">
                @csrf
                <button type="submit" class="text-red-500 font-bold text-lg">Çıkış Yap</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="text-on-surface opacity-70 font-bold text-lg transition-colors py-4 border-b border-black/5">Giriş Yap</a>
            <a href="{{ route('register') }}" class="text-primary font-bold text-lg transition-colors py-4">Kayıt Ol</a>
        @endauth
    </div>
</div>
