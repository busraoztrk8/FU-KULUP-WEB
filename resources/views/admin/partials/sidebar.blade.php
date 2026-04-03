@php
    $currentPage = $page ?? 'dashboard';
@endphp

<div class="flex flex-col h-full">
    <div class="px-6 h-[72px] flex items-center border-b border-slate-100 shrink-0">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <div class="w-8 h-8 flex items-center justify-center border border-primary/20 rounded-lg bg-primary">
                <span class="material-symbols-outlined text-white text-[18px]">school</span>
            </div>
            <div class="flex flex-col">
                <span class="sidebar-label text-[15px] text-slate-800 font-bold leading-tight font-headline">Yönetim Paneli</span>
                <span class="text-[11px] text-slate-500 leading-tight">Fırat Üniversitesi</span>
            </div>
        </a>
    </div>

    <nav class="flex-1 py-4 overflow-y-auto overflow-x-hidden">
        <p class="px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-2">Ana Menü</p>
        <x-admin-sidebar-link id="dashboard"     icon="dashboard" label="Dashboard"          href="{{ route('admin.index') }}"       :current="$currentPage" />

        <p class="px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-6">İçerik Yönetimi</p>
        <x-admin-sidebar-link id="news"          icon="article"   label="Haberler"           href="{{ route('admin.haberler') }}"    :current="$currentPage" />
        <x-admin-sidebar-link id="events"        icon="event"     label="Etkinlikler"        href="{{ route('admin.etkinlikler') }}" :current="$currentPage" />
        <x-admin-sidebar-link id="announcements" icon="campaign"  label="Duyurular"          href="{{ route('admin.duyurular') }}"   :current="$currentPage" />
        <x-admin-sidebar-link id="clubs"         icon="groups"    label="Kulüpler"           href="{{ route('admin.kulupler') }}"    :current="$currentPage" />
        <x-admin-sidebar-link id="slider"        icon="image"     label="Slider Yönetimi"    href="{{ route('admin.slider') }}"      :current="$currentPage" />

        <p class="px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-6">Site Yönetimi</p>
        <x-admin-sidebar-link id="users"         icon="person"    label="Kullanıcı Yönetimi" href="{{ route('admin.kullanicilar') }}" :current="$currentPage" />
        <x-admin-sidebar-link id="menu"          icon="menu"      label="Menü Yönetimi"      href="{{ route('admin.menu') }}"         :current="$currentPage" />
        <x-admin-sidebar-link id="settings"      icon="settings"  label="Site Ayarları"      href="{{ route('admin.ayarlar') }}"      :current="$currentPage" />
    </nav>

    <div class="p-4 border-t border-slate-100 shrink-0 space-y-1">
        <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-all">
            <span class="material-symbols-outlined text-[20px]">open_in_new</span>
            <span class="sidebar-label">Siteyi Görüntüle</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-400 hover:text-red-600 hover:bg-red-50 transition-all">
                <span class="material-symbols-outlined text-[20px]">logout</span>
                <span class="sidebar-label">Çıkış Yap</span>
            </button>
        </form>
    </div>
</div>
