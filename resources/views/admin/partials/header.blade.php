<div class="flex items-center justify-between h-[72px] px-6 md:px-8 bg-white border-b border-slate-100">
    <div class="flex items-center gap-4">
        <button id="sidebar-toggle" class="lg:hidden text-slate-600 hover:text-primary transition-colors">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <h1 class="text-xl font-bold font-headline text-slate-800">@yield('page_title', 'Dashboard')</h1>
    </div>
    <div class="flex items-center gap-6">
        <div class="relative hidden md:block">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
            <input type="text" placeholder="Ara..." class="bg-slate-100 border-none rounded-xl text-sm pl-10 pr-4 py-2.5 w-64 focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all shadow-sm" onkeypress="if(event.key === 'Enter') showToast('Arama sonuçları backend bağlandığında gelecek: ' + this.value, 'info')"/>
        </div>
        <button onclick="showToast('Şu an için yeni bildiriminiz yok.', 'info')" class="relative text-slate-500 hover:text-primary transition-colors p-2">
            <span class="material-symbols-outlined">notifications</span>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
        <div class="flex items-center gap-3 pl-4 border-l border-slate-200 cursor-pointer" onclick="showToast('Profil ayarlarına yönlendirilecek', 'info')">
            <div class="w-9 h-9 bg-primary/10 rounded-full flex items-center justify-center text-primary shadow-sm">
                <span class="material-symbols-outlined text-[20px]">person</span>
            </div>
            <div class="hidden md:block">
                <p class="text-sm font-bold text-slate-800 leading-tight">Admin</p>
                <p class="text-[11px] text-slate-500 leading-tight">Yönetici</p>
            </div>
        </div>
    </div>
</div>
