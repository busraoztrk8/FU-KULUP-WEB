<div class="flex items-center justify-between h-[72px] px-6 md:px-8 bg-white border-b border-slate-100">
    <div class="flex items-center gap-4">
        <button id="sidebar-toggle" class="lg:hidden text-slate-600 hover:text-primary transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <h1 class="text-xl font-bold font-headline text-slate-800">{{ View::getSection('page_title', 'Yönetim') }}</h1>
    </div>
    <div class="flex items-center gap-4">
        <!-- Siteyi Görüntüle -->
        <a href="{{ route('home') }}" target="_blank" class="hidden md:flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition-all font-bold text-sm">
            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
            Siteyi Görüntüle
        </a>




        <!-- Admin Profile Dropdown -->
        <div class="relative group">
            <div class="flex items-center gap-3 pl-4 border-l border-slate-200 cursor-pointer">
                <div class="w-9 h-9 bg-primary/10 rounded-full flex items-center justify-center text-primary shadow-sm group-hover:bg-primary group-hover:text-white transition-all">
                    <span class="material-symbols-outlined text-[20px]">person</span>
                </div>
                <div class="hidden md:block">
                    <p class="text-sm font-bold text-slate-800 leading-tight">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-[11px] text-slate-500 leading-tight">Yönetici</p>
                </div>
            </div>
            <!-- Dropdown Menu -->
            <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-2xl border border-slate-100 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-[60]">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[18px]">settings</span> Ayarlar
                </a>
                <div class="h-px bg-slate-50 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 transition-colors text-left">
                        <span class="material-symbols-outlined text-[18px]">logout</span> Çıkış Yap
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
