{{-- Takvim görünümünde etkinlik yokken: kampüsü keşfet bloğu (etkinlikler sayfası ile uyumlu) --}}
<div class="bg-slate-50/80 border border-slate-100 rounded-xl md:rounded-2xl p-4 md:p-5 shadow-sm animate-fade-in">
    <h4 class="font-headline font-bold text-slate-800 mb-3 md:mb-4 flex items-center gap-2 text-sm md:text-base">
        <span class="material-symbols-outlined text-primary text-lg md:text-xl">explore</span>
        Kampüsü Keşfetmeye Devam Et
    </h4>

    <div class="grid grid-cols-2 gap-2 md:gap-3 mb-4">
        <a href="{{ route('kulupler') }}" class="flex flex-col items-center justify-center p-3 md:p-3.5 rounded-xl bg-primary/5 hover:bg-primary/10 border border-primary/10 transition-all group">
            <span class="material-symbols-outlined text-primary text-2xl md:text-3xl mb-1 group-hover:scale-110 transition-transform">groups</span>
            <span class="text-[11px] md:text-xs font-bold text-primary text-center leading-tight">Tüm Kulüpler</span>
        </a>
        <a href="{{ route('etkinlikler') }}#calendar-view" class="flex flex-col items-center justify-center p-3 md:p-3.5 rounded-xl bg-tertiary/5 hover:bg-tertiary/10 border border-tertiary/10 transition-all group">
            <span class="material-symbols-outlined text-tertiary text-2xl md:text-3xl mb-1 group-hover:scale-110 transition-transform">event_note</span>
            <span class="text-[11px] md:text-xs font-bold text-tertiary text-center leading-tight">Takvime Dön</span>
        </a>
    </div>



    <div class="mt-4 p-3 md:p-4 rounded-xl md:rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 text-white relative overflow-hidden group">
        <div class="relative z-10">
            <p class="text-[10px] font-bold text-primary-light uppercase tracking-widest mb-0.5">Yeni Bir Şeyler Başlat</p>
            <h5 class="font-bold text-xs md:text-sm mb-2">Kendi etkinliğini mi planlamak istiyorsun?</h5>
            <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 text-xs font-bold bg-white text-slate-900 px-4 py-2 rounded-full hover:bg-primary hover:text-white transition-all">
                Kulüp Paneli <span class="material-symbols-outlined text-xs">arrow_forward</span>
            </a>
        </div>
        <span class="material-symbols-outlined absolute -right-4 -bottom-4 text-7xl text-white/5 rotate-12 group-hover:scale-110 transition-transform">rocket_launch</span>
    </div>
</div>
