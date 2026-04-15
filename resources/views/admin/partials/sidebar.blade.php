@php
    $currentPage = 'dashboard';
    if (request()->routeIs('admin.haberler*')) $currentPage = 'news';
    elseif (request()->routeIs('admin.etkinlikler*')) $currentPage = 'events';
    elseif (request()->routeIs('admin.duyurular*')) $currentPage = 'announcements';
    elseif (request()->routeIs('admin.kulupler.uyeler*')) $currentPage = 'members';
    elseif (request()->routeIs('admin.members*')) $currentPage = 'members';
    elseif (request()->routeIs('admin.kulupler*')) $currentPage = 'clubs';
    elseif (request()->routeIs('admin.slider*')) $currentPage = 'slider';
    elseif (request()->routeIs('admin.gallery*')) $currentPage = 'gallery';
    elseif (request()->routeIs('admin.kullanicilar*')) $currentPage = 'users';
    elseif (request()->routeIs('admin.menu*')) $currentPage = 'menu';
    elseif (request()->routeIs('admin.ayarlar*')) $currentPage = 'settings';
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
        <x-admin-sidebar-link id="dashboard"     icon="dashboard" label="Anasayfa"          href="{{ route('admin.index') }}"       :current="$currentPage" />

        <p class="px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-6">İçerik Yönetimi</p>
        <x-admin-sidebar-link id="news"          icon="article"   label="Haberler"           href="{{ route('admin.haberler') }}"    :current="$currentPage" />
        <x-admin-sidebar-link id="events"        icon="event"     label="Etkinlikler"        href="{{ route('admin.etkinlikler') }}" :current="$currentPage" />
        <x-admin-sidebar-link id="announcements" icon="campaign"  label="Duyurular"          href="{{ route('admin.duyurular') }}"   :current="$currentPage" />
        <x-admin-sidebar-link id="clubs"         icon="groups"    label="Kulüpler"           href="{{ route('admin.kulupler') }}"    :current="$currentPage" />
        
        @if(auth()->user()->isEditor())
        <x-admin-sidebar-link id="my_club"      icon="edit_square" label="Kulübüm"          href="{{ route('admin.kulupler') }}"    :current="$currentPage" />
        @endif
        
        <x-admin-sidebar-link id="members"       icon="how_to_reg" label="Üyelik Yönetimi"    href="{{ auth()->user()->isEditor() && auth()->user()->club_id ? route('admin.kulupler.uyeler', auth()->user()->club_id) : route('admin.members.index') }}"    :current="$currentPage" />
        
        @if(auth()->user()->isAdmin())
        <x-admin-sidebar-link id="slider"        icon="image"     label="Slider Yönetimi"    href="{{ route('admin.slider') }}"      :current="$currentPage" />
        <x-admin-sidebar-link id="gallery"       icon="collections" label="Galeri Yönetimi"    href="{{ route('admin.gallery') }}"     :current="$currentPage" />

        <p class="px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 mt-6">Site Yönetimi</p>
        <x-admin-sidebar-link id="users"         icon="person"    label="Kullanıcı Yönetimi" href="{{ route('admin.kullanicilar') }}" :current="$currentPage" />
        <x-admin-sidebar-link id="menu"          icon="menu"      label="Menü Yönetimi"      href="{{ route('admin.menu') }}"         :current="$currentPage" />
        <x-admin-sidebar-link id="settings"      icon="settings"  label="Site Ayarları"      href="{{ route('admin.ayarlar') }}"      :current="$currentPage" />
        @endif
    </nav>


</div>
