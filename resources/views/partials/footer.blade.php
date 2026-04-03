<!-- Footer -->
<footer class="bg-white w-full py-16 border-t border-black/5">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 px-8 max-w-7xl mx-auto">
        <div class="space-y-6">
            <a href="{{ route('home') }}" class="font-headline font-bold text-primary text-3xl">FIRAT ÜNİVERSİTESİ</a>
            <p class="font-body text-sm text-on-surface-variant leading-relaxed">Fırat Üniversitesi Topluluk
                Portalı. Öğrencileri ve akademisyenleri dijital bir ekosistemde buluşturan topluluk platformu.</p>
        </div>
        <div class="grid grid-cols-2 gap-8">
            <div class="space-y-4">
                <h4 class="text-on-surface font-bold text-xs uppercase tracking-widest">HIZLI BAĞLANTILAR</h4>
                <ul class="space-y-2 font-body text-sm text-on-surface-variant font-bold">
                    <li><a class="hover:text-primary transition-colors" href="{{ route('home') }}">Ana Sayfa</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('etkinlikler') }}">Etkinlikler</a></li>
                    <li><a class="hover:text-primary transition-colors" href="{{ route('kulupler') }}">Kulüpler</a></li>
                </ul>
            </div>
            <div class="space-y-4">
                <h4 class="text-on-surface font-bold text-xs uppercase tracking-widest">KURUMSAL</h4>
                <ul class="space-y-2 font-body text-sm text-on-surface-variant font-bold">
                    <li><a class="hover:text-primary transition-colors" href="#">İletişim</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">KVKK</a></li>
                </ul>
            </div>
        </div>
        <div class="space-y-6">
            <div>
                <h4 class="text-on-surface font-bold text-xs uppercase tracking-widest mb-4">SOSYAL MEDYA</h4>
                <div class="flex gap-4">
                    <a class="text-primary hover:opacity-80 transition-opacity" href="#"><span
                            class="material-symbols-outlined">share</span></a>
                    <a class="text-primary hover:opacity-80 transition-opacity" href="#"><span
                            class="material-symbols-outlined">photo_camera</span></a>
                    <a class="text-primary hover:opacity-80 transition-opacity" href="#"><span
                            class="material-symbols-outlined">work</span></a>
                    <a class="text-primary hover:opacity-80 transition-opacity" href="#"><span
                            class="material-symbols-outlined">facebook</span></a>
                </div>
            </div>
            <p class="font-body text-xs text-on-surface-variant mt-8">© 2024 Fırat Üniversitesi. Tüm hakları
                saklıdır.</p>
        </div>
    </div>
</footer>
