@extends('layouts.app')

@section('title', 'Etkinlikler - Fırat Üniversitesi')
@section('data-page', 'events')

@section('content')
    <!-- Hero Section -->
    <section class="relative h-[600px] flex items-center overflow-hidden mx-4 md:mx-8 mt-4 rounded-3xl shadow-xl">
        <img alt="Summit Event" class="absolute inset-0 w-full h-full object-cover"
            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDzgZ7wHZiMgjwpgluAsvjSzS-728fh-7EyYz0A8nKY-NlVb91E0b5f9yopQ1QbxRkANmud5pEvGECLIzbh1HAKTScv0dAVMuHnP5WxTAahbaTI5vlN1k2jpGjGK07uflPJh0p1eWCJGrNnH5AWVljCgvr4H59lWAJDuveb4DMx8LQI0D0zM8bP2w5yafS9Q6aYVk6EshhkxZORlKJ2ie0thRufJm7wNLHRApFAdOdVnIHYPCg5_8OQk9fXTsInkuBCiG99lt_yw2I" />
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
        <div class="relative z-10 max-w-3xl px-12 md:px-20">
            <span
                class="bg-primary text-white px-4 py-1 rounded-full text-[10px] font-bold mb-4 inline-block uppercase tracking-widest">Ana
                Etkinlik</span>
            <h1 class="text-5xl md:text-7xl font-bold font-headline text-white mb-6 leading-tight tracking-tight">
                Geleceğin Teknolojileri Zirvesi 2024</h1>
            <p class="text-lg md:text-xl text-slate-200 mb-10 max-w-xl font-body leading-relaxed">
                Yapay zeka, kuantum bilişim ve sürdürülebilir enerji konularında dünyanın en iyi araştırmacıları ile
                tanışın.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('etkinlik.detay', ['slug' => 'gelecegin-teknolojileri-zirvesi-2024']) }}"
                    class="bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-full font-bold transition-all flex items-center gap-2 group">
                    Kayıt Ol
                    <span
                        class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
                <a href="{{ route('etkinlik.detay', ['slug' => 'gelecegin-teknolojileri-zirvesi-2024']) }}"
                    class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white px-8 py-4 rounded-full font-bold transition-all">
                    Detayları Gör
                </a>
            </div>
        </div>
    </section>

    <!-- Main Content Area -->
    <section class="max-w-7xl mx-auto px-6 py-16">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
            <div>
                <h2 class="text-3xl font-bold font-headline text-on-background mb-2">Yaklaşan Etkinlikler</h2>
                <p class="text-on-surface-variant">Kampüs hayatındaki en güncel akademik ve sosyal etkinlikleri
                    takip edin.</p>
            </div>

            <!-- Tab Toggle -->
            <div class="bg-slate-100 p-1.5 rounded-full flex gap-1 shadow-sm">
                <button
                    class="px-6 py-2.5 rounded-full bg-primary text-white font-bold text-sm transition-all shadow-md tab-btn active"
                    data-tab-btn="calendar">Takvim</button>
                <button
                    class="px-6 py-2.5 rounded-full text-slate-600 hover:text-on-background font-medium text-sm transition-all tab-btn"
                    data-tab-btn="all">Tümü</button>
            </div>
        </div>

        <!-- Tab Content 1: Calendar View (Default) -->
        <div id="calendar-view" class="tab-content active grid grid-cols-1 lg:grid-cols-2 gap-10"
            data-tab-content="calendar">
            <!-- Calendar Side -->
            <div class="lg:col-span-1 bg-slate-50 rounded-[2rem] p-8 border border-slate-100 h-fit">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-headline font-bold text-xl text-on-background">Mayıs 2024</h3>
                    <div class="flex gap-2">
                        <button class="p-2 rounded-lg hover:bg-white text-on-surface-variant"><span
                                class="material-symbols-outlined">chevron_left</span></button>
                        <button class="p-2 rounded-lg hover:bg-white text-on-surface-variant"><span
                                class="material-symbols-outlined">chevron_right</span></button>
                    </div>
                </div>
                <div class="grid grid-cols-7 gap-y-4 text-center mb-4">
                    <div class="text-xs font-bold text-on-surface-variant opacity-60">PT</div>
                    <div class="text-xs font-bold text-on-surface-variant opacity-60">SA</div>
                    <div class="text-xs font-bold text-on-surface-variant opacity-60">ÇA</div>
                    <div class="text-xs font-bold text-on-surface-variant opacity-60">PE</div>
                    <div class="text-xs font-bold text-on-surface-variant opacity-60">CU</div>
                    <div class="text-xs font-bold text-on-surface-variant opacity-60 text-primary">CT</div>
                    <div class="text-xs font-bold text-on-surface-variant opacity-60 text-primary">PA</div>
                    <div class="p-2 text-sm text-slate-300">29</div>
                    <div class="p-2 text-sm text-slate-300">30</div>
                    <div class="p-2 text-sm text-slate-600">1</div>
                    <div class="p-2 text-sm text-slate-600">2</div>
                    <div class="p-2 text-sm bg-primary/10 text-primary rounded-xl font-bold relative">
                        3
                        <span
                            class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-primary rounded-full"></span>
                    </div>
                    <div class="p-2 text-sm text-slate-600">4</div>
                    <div class="p-2 text-sm text-slate-600">5</div>
                    <div class="p-2 text-sm text-slate-600">6</div>
                    <div
                        class="p-2 text-sm font-bold bg-primary text-white rounded-xl shadow-lg ring-4 ring-primary/10">
                        7</div>
                    <div class="p-2 text-sm text-slate-600">8</div>
                    <div class="p-2 text-sm text-slate-600">9</div>
                    <div class="p-2 text-sm text-slate-600 relative">
                        10
                        <span
                            class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-tertiary rounded-full"></span>
                    </div>
                    <div class="p-2 text-sm text-slate-600">11</div>
                    <div class="p-2 text-sm text-slate-600">12</div>
                </div>
                <div class="mt-8 pt-8 border-t border-slate-200">
                    <h4 class="text-sm font-bold mb-4 flex items-center gap-2 text-on-background">
                        <span class="material-symbols-outlined text-primary text-sm"
                            style="font-variation-settings: 'FILL' 1;">circle</span>
                        Kategoriler
                    </h4>
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-on-surface-variant">Akademik Konferans</span>
                            <span class="w-2 h-2 rounded-full bg-primary"></span>
                        </li>
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-on-surface-variant">Kariyer Günleri</span>
                            <span class="w-2 h-2 rounded-full bg-tertiary"></span>
                        </li>
                        <li class="flex items-center justify-between text-sm">
                            <span class="text-on-surface-variant">Sosyal Etkinlik</span>
                            <span class="w-2 h-2 rounded-full bg-secondary"></span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Event List Side -->
            <div class="lg:col-span-1 space-y-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-headline font-bold text-xl text-on-background">7 Mayıs, Salı</h3>
                    <span class="text-on-surface-variant text-sm">3 Etkinlik Bulundu</span>
                </div>
                <!-- Event Item 1 -->
                <a href="{{ route('etkinlik.detay', ['slug' => 'robotik-sistemler-ve-yapay-sinir-aglari']) }}"
                    class="group bg-white border border-slate-100 hover:border-primary/30 hover:shadow-xl rounded-[2rem] p-6 transition-all duration-300 flex flex-col xl:flex-row gap-6 items-start">
                    <div class="w-full xl:w-48 h-32 rounded-2xl overflow-hidden shrink-0">
                        <img alt="Robotics Lab"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuC7Pr3Bd9lO7zz1OtX8bLbtRlbvZeexoAnlTkGU7KNtHDaIsXbf49Oik9tTHE8BTZLhgEexI9p5im9I8FrDRaVV2K-VpXYkNIcWZzrtUneEpZek65YoLPgCBr5B6gN5wSFVaWkOroy8VQb7o7l6DBv0cr3KNApCdfprnirugWlW8dRDlE_8hfOqg3NL1xCa0Ec6laLEfUGAJ2GPLBFAlymhG0t6SVi5C6upGq9BKU8vBSWwsdjjU2F2QE5Kz_4kQPgbSfxftkprNq8" />
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span
                                class="bg-primary/10 text-primary text-[10px] font-extrabold uppercase px-2 py-0.5 rounded">Atölye</span>
                            <span class="text-on-surface-variant text-xs flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">schedule</span> 14:00 - 16:30
                            </span>
                        </div>
                        <h4
                            class="text-xl font-bold font-headline mb-2 text-on-background group-hover:text-primary transition-colors">
                            Robotik Sistemler ve Yapay Sinir Ağları</h4>
                        <p class="text-on-surface-variant text-sm mb-4 line-clamp-2 leading-relaxed">Mühendislik
                            fakültesi laboratuvarlarında gerçekleştirilecek olan uygulamalı eğitim serisinin ilk
                            oturumu.</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">location_on</span>
                                <span class="text-xs text-on-surface-variant">Lab 402, Mühendislik Binası</span>
                            </div>
                            <div
                                class="text-primary font-bold text-sm flex items-center gap-1 opacity-100 transition-opacity">
                                Kaydol <span class="material-symbols-outlined text-sm">open_in_new</span>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- Event Item 2 -->
                <a href="{{ route('etkinlik.detay', ['slug' => 'girisimcilik-ve-inovasyon-paneli']) }}"
                    class="group bg-white border border-slate-100 hover:border-primary/30 hover:shadow-xl rounded-[2rem] p-6 transition-all duration-300 flex flex-col xl:flex-row gap-6 items-start">
                    <div class="w-full xl:w-48 h-32 rounded-2xl overflow-hidden shrink-0">
                        <img alt="Business Seminar"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCpOtHgvlADgB0kXGp0HzLw8lamM5V-rgVXmtlAbvgqn92KzyU6L0nebIShZVccEV56D0V5af4BdjIeauGFspE2NzGYu-ARxthc8-l7qiSevfG-rUSI8q8MVhMVGsdObXNrGBxjFI_prZfIJxENOMiQeIjgkvAHjapiccs_ZwQoYuSbazhq542HdJUbHH_3Bnx7LfDqpsPyuyjRTodhzfXP2hieRrFC9mz96AFdl4gRAK_WWYW2zv7bfDwoWbI3xQhgEtpEnw-h2pw" />
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <span
                                class="bg-tertiary/10 text-tertiary text-[10px] font-extrabold uppercase px-2 py-0.5 rounded">Kariyer</span>
                            <span class="text-on-surface-variant text-xs flex items-center gap-1">
                                <span class="material-symbols-outlined text-xs">schedule</span> 10:00 - 12:00
                            </span>
                        </div>
                        <h4
                            class="text-xl font-bold font-headline mb-2 text-on-background group-hover:text-primary transition-colors">
                            Girişimcilik ve İnovasyon Paneli</h4>
                        <p class="text-on-surface-variant text-sm mb-4 line-clamp-2 leading-relaxed">Sektör
                            liderlerinden başarı hikayeleri ve yeni mezunlar için kariyer fırsatları üzerine
                            interaktif söyleşi.</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary">location_on</span>
                                <span class="text-xs text-on-surface-variant">Büyük Konferans Salonu</span>
                            </div>
                            <div
                                class="text-primary font-bold text-sm flex items-center gap-1 opacity-100 transition-opacity">
                                Kaydol <span class="material-symbols-outlined text-sm">open_in_new</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Tab Content 2: Grid View -->
        <div id="grid-view" class="tab-content grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
            data-tab-content="all">
            <!-- Card 1 -->
            <a href="{{ route('etkinlik.detay', ['slug' => 'ux-tasarim-atolyesi']) }}"
                class="group bg-primary rounded-[2rem] overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full border border-white/5 cursor-pointer">
                <div class="relative h-60 w-full overflow-hidden shrink-0">
                    <img alt="UX Design Workshop"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuC7Pr3Bd9lO7zz1OtX8bLbtRlbvZeexoAnlTkGU7KNtHDaIsXbf49Oik9tTHE8BTZLhgEexI9p5im9I8FrDRaVV2K-VpXYkNIcWZzrtUneEpZek65YoLPgCBr5B6gN5wSFVaWkOroy8VQb7o7l6DBv0cr3KNApCdfprnirugWlW8dRDlE_8hfOqg3NL1xCa0Ec6laLEfUGAJ2GPLBFAlymhG0t6SVi5C6upGq9BKU8vBSWwsdjjU2F2QE5Kz_4kQPgbSfxftkprNq8" />
                    <div
                        class="absolute top-4 left-4 bg-white rounded-2xl p-3 text-center min-w-[60px] shadow-lg text-primary">
                        <div class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">ARA</div>
                        <div class="text-2xl font-extrabold">15</div>
                    </div>
                </div>
                <div class="p-8 flex flex-col flex-1 text-white">
                    <div class="flex items-center gap-3 mb-3">
                        <span
                            class="bg-white/20 text-[10px] font-extrabold uppercase px-2 py-0.5 rounded">AKADEMİK</span>
                        <span class="text-white/80 text-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">schedule</span> 14:00 - 17:00
                        </span>
                    </div>
                    <h4 class="text-2xl font-bold font-headline mb-3 leading-tight uppercase">UX Tasarım Atölyesi
                    </h4>
                    <p class="text-white/70 text-sm mb-6 line-clamp-2 leading-relaxed">Kullanıcı deneyimi
                        tasarımının temellerini öğreneceğiniz, pratik uygulamalarla dolu interaktif bir gün.</p>
                    <div class="mt-auto flex items-center justify-between pt-4 border-t border-white/10">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-white/60">location_on</span>
                            <span class="text-xs text-white/80">Tasarım Lab 2</span>
                        </div>
                        <div class="text-white font-bold text-sm flex items-center gap-1">
                            Detaylar <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </div>
                    </div>
                </div>
            </a>
            <!-- Card 2 -->
            <div
                class="group bg-primary rounded-[2rem] overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full border border-white/5">
                <div class="relative h-60 w-full overflow-hidden shrink-0">
                    <img alt="Jazz Night"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCpOtHgvlADgB0kXGp0HzLw8lamM5V-rgVXmtlAbvgqn92KzyU6L0nebIShZVccEV56D0V5af4BdjIeauGFspE2NzGYu-ARxthc8-l7qiSevfG-rUSI8q8MVhMVGsdObXNrGBxjFI_prZfIJxENOMiQeIjgkvAHjapiccs_ZwQoYuSbazhq542HdJUbHH_3Bnx7LfDqpsPyuyjRTodhzfXP2hieRrFC9mz96AFdl4gRAK_WWYW2zv7bfDwoWbI3xQhgEtpEnw-h2pw" />
                    <div class="absolute top-4 left-4 bg-white rounded-2xl p-3 text-center min-w-[60px] shadow-lg">
                        <div class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">ARA</div>
                        <div class="text-2xl font-extrabold text-primary">18</div>
                    </div>
                </div>
                <div class="p-8 flex flex-col flex-1 text-white">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="bg-white/20 text-[10px] font-extrabold uppercase px-2 py-0.5 rounded">KÜLTÜR &
                            SANAT</span>
                        <span class="text-white/80 text-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">schedule</span> 20:30
                        </span>
                    </div>
                    <h4 class="text-2xl font-bold font-headline mb-3 leading-tight">Caz Gecesi: Kış Melodileri</h4>
                    <p class="text-white/70 text-sm mb-6 line-clamp-2 leading-relaxed">Üniversitemiz caz
                        topluluğunun hazırladığı büyüleyici bir kış konseri. Sıcak içecekler eşliğinde.</p>
                    <div class="mt-auto flex items-center justify-between pt-4 border-t border-white/10">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-white/60">location_on</span>
                            <span class="text-xs text-white/80">Ana Amfi</span>
                        </div>
                        <a class="text-white font-bold text-sm flex items-center gap-1 hover:underline"
                            href="{{ route('etkinlik.detay', ['slug' => 'caz-gecesi-kis-melodileri']) }}">
                            Detaylar <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div
                class="group bg-primary rounded-[2rem] overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full border border-white/5">
                <div class="relative h-60 w-full overflow-hidden shrink-0">
                    <img alt="Networking"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDzgZ7wHZiMgjwpgluAsvjSzS-728fh-7EyYz0A8nKY-NlVb91E0b5f9yopQ1QbxRkANmud5pEvGECLIzbh1HAKTScv0dAVMuHnP5WxTAahbaTI5vlN1k2jpGjGK07uflPJh0p1eWCJGrNnH5AWVljCgvr4H59lWAJDuveb4DMx8LQI0D0zM8bP2w5yafS9Q6aYVk6EshhkxZORlKJ2ie0thRufJm7wNLHRApFAdOdVnIHYPCg5_8OQk9fXTsInkuBCiG99lt_yw2I" />
                    <div class="absolute top-4 left-4 bg-white rounded-2xl p-3 text-center min-w-[60px] shadow-lg">
                        <div class="text-[10px] font-bold text-slate-400 uppercase leading-none mb-1">ARA</div>
                        <div class="text-2xl font-extrabold text-primary">21</div>
                    </div>
                </div>
                <div class="p-8 flex flex-col flex-1 text-white">
                    <div class="flex items-center gap-3 mb-3">
                        <span
                            class="bg-white/20 text-[10px] font-extrabold uppercase px-2 py-0.5 rounded">KARİYER</span>
                        <span class="text-white/80 text-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-xs">schedule</span> 10:00 - 18:00
                        </span>
                    </div>
                    <h4 class="text-2xl font-bold font-headline mb-3 leading-tight">Networking 101: Sektör Buluşması
                    </h4>
                    <p class="text-white/70 text-sm mb-6 line-clamp-2 leading-relaxed">Mezunlarımız ve önde gelen
                        şirketlerin temsilcileriyle tanışın, staj ve iş fırsatlarını ilk elden yakalayın.</p>
                    <div class="mt-auto flex items-center justify-between pt-4 border-t border-white/10">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-white/60">location_on</span>
                            <span class="text-xs text-white/80">Kültür Merkezi</span>
                        </div>
                        <a class="text-white font-bold text-sm flex items-center gap-1 hover:underline"
                            href="{{ route('etkinlik.detay', ['slug' => 'networking-101']) }}">
                            Detaylar <span class="material-symbols-outlined text-sm">chevron_right</span>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Create Event Card -->
            <div
                class="bg-primary rounded-[2rem] p-10 flex flex-col items-center justify-center text-center text-white border-2 border-dashed border-white/20 hover:border-white/40 transition-all group cursor-pointer">
                <div
                    class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mb-8 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-4xl">add_circle</span>
                </div>
                <h3 class="text-2xl font-bold font-headline mb-4">Kendi Etkinliğini Oluştur</h3>
                <p class="text-white/70 text-sm mb-10 leading-relaxed max-w-[200px]">Bir kulüp üyesi misin?
                    Etkinliğini şimdi planla!</p>
                <button
                    class="w-full bg-white text-primary py-4 rounded-2xl font-bold hover:bg-surface transition-all shadow-xl shadow-black/10">Etkinlik
                    Başvurusu</button>
            </div>
        </div>

        <!-- More Load Button (Common) -->
        <div class="mt-16 flex justify-center">
            <button
                class="bg-slate-50 hover:bg-slate-100 text-on-surface-variant px-10 py-3 rounded-full font-bold transition-all border border-slate-100 flex items-center gap-2 shadow-sm">
                Daha Fazla Yükle
                <span class="material-symbols-outlined text-sm">expand_more</span>
            </button>
        </div>
    </section>

    <!-- Categories / Filter Section -->
    <section class="max-w-7xl mx-auto px-6 pb-24 border-t border-slate-100 pt-16">
        <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-8">
            <h3 class="text-3xl font-bold font-headline text-on-background">Tüm Etkinlik Kategorileri</h3>
            <div class="flex flex-wrap gap-4 items-center w-full md:w-auto">
                <div class="relative flex-1 md:w-80">
                    <span
                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input type="text" placeholder="Hızlı ara..."
                        class="w-full pl-12 pr-4 py-3 bg-surface-container border-none rounded-2xl focus:ring-2 focus:ring-primary/20 font-medium">
                </div>
                <button
                    class="p-3 bg-surface-container rounded-2xl text-on-surface-variant hover:text-primary transition-all">
                    <span class="material-symbols-outlined">tune</span>
                </button>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Bento Style Card -->
            <div class="md:col-span-2 group relative h-80 rounded-[2.5rem] overflow-hidden shadow-xl">
                <img alt="Academic Research"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuACZq-VolsSL6zSF-oAqBHZtcPzNPBDJIdpKFv-9WNuhL7wX1AGzTwwsYyYFhjfFuQ9Wo2SBOSVzbC3I0GTyH8Ts-prdx77ncJw0fqdFuWmsZTjmuhxceeHoefP84QvY-T2oHF-E43fa0IzNdHWSyZaYWxrK1tIzWtN95SAPphgLMpUeKv5m2yUCG8INZgptzl7NdkqfiBVU2SbIA8orVw7BPi4xYRe4DBbO1BKpXOfKpFoJDVJCVLFgpT9hLnL8I1GzYUltJIaSC8" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-12">
                    <span class="text-tertiary text-xs font-extrabold uppercase tracking-widest mb-4 block">Öne
                        Çıkan</span>
                    <h4 class="text-4xl font-bold text-white mb-4 leading-tight">Uluslararası Tıp Kongresi 2024</h4>
                    <p class="text-slate-300 text-lg max-w-md">Genom düzenleme ve geleceğin sağlık teknolojileri
                        üzerine 3 günlük maraton.</p>
                </div>
            </div>
            <!-- Additional Categories -->
            <div
                class="group bg-surface-container border border-black/5 rounded-[2rem] p-8 flex flex-col justify-between hover:border-primary/30 transition-all">
                <div>
                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-primary text-3xl">school</span>
                    </div>
                    <h4 class="text-2xl font-bold mb-3 text-on-surface">Mezuniyet Balosu</h4>
                    <p class="text-on-surface-variant text-sm leading-relaxed">2024 Mezunları için unutulmaz bir
                        gece. Bilet satışları başladı.</p>
                </div>
                <div class="mt-8 flex items-center justify-between">
                    <span class="text-primary font-bold">21 Haziran</span>
                    <span
                        class="material-symbols-outlined text-on-surface-variant group-hover:translate-x-2 transition-transform">arrow_forward</span>
                </div>
            </div>
        </div>
    </section>
@endsection