<!-- Footer -->
<footer class="bg-white w-full py-10 md:py-16 border-t border-black/5">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-12 px-4 sm:px-6 md:px-8 max-w-7xl mx-auto">
        <div class="space-y-4 md:space-y-6 text-center md:text-left">
            <a href="{{ route('home') }}" class="font-headline font-bold text-primary text-2xl md:text-3xl block transition-transform hover:scale-105 uppercase tracking-tighter">Fırat Üniversitesi</a>
            <p class="font-body text-sm text-on-surface-variant leading-relaxed opacity-80">Üniversitemizin aktif topluluk ve etkinlik ekosistemi. Öğrencileri, akademisyenleri ve kulüpleri dijital bir platformda buluşturuyoruz.</p>
        </div>
        <div class="grid grid-cols-2 gap-6 md:gap-8">
            <div class="space-y-3 md:space-y-4 text-center md:text-left">
                <h4 class="text-on-surface font-bold text-xs uppercase tracking-widest text-primary/60">HIZLI BAĞLANTILAR</h4>
                <ul class="space-y-2 font-body text-sm text-on-surface-variant font-bold">
                    <li><a class="hover:text-primary transition-colors" href="{{ route('home') }}">Ana Sayfa</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('etkinlikler') }}">Etkinlikler</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('kulupler') }}">Kulüpler</a></li>
                </ul>
            </div>
            <div class="space-y-3 md:space-y-4 text-center md:text-left">
                <h4 class="text-on-surface font-bold text-xs uppercase tracking-widest text-primary/60">KURUMSAL</h4>
                <ul class="space-y-2 font-body text-sm text-on-surface-variant font-bold">
                    <li><a class="hover:text-primary transition-colors" href="#">İletişim</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">KVKK</a></li>
                </ul>
            </div>
        </div>
        <div class="space-y-4 md:space-y-6 flex flex-col items-center md:items-start text-center md:text-left">
            <div>
                <h4 class="text-on-surface font-bold text-xs uppercase tracking-widest mb-4 text-primary/60">BİZİ TAKİP EDİN</h4>
                <div class="flex gap-3 md:gap-4">
                    <a class="w-10 h-10 border border-black/5 rounded-full flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all duration-300" href="#"><span class="material-symbols-outlined text-[20px]">share</span></a>
                    <a class="w-10 h-10 border border-black/5 rounded-full flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all duration-300" href="#"><span class="material-symbols-outlined text-[20px]">photo_camera</span></a>
                    <a class="w-10 h-10 border border-black/5 rounded-full flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all duration-300" href="#"><span class="material-symbols-outlined text-[20px]">facebook</span></a>
                </div>
            </div>
            <div class="pt-4 border-t border-black/5 w-full md:w-auto">
                <p class="font-body text-xs text-on-surface-variant">© 2024 Fırat Üniversitesi. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </div>
</footer>
