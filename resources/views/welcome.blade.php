@extends('layouts.app')
@section('title', 'Fırat Üniversitesi - Üniversite Hayatını Keşfedin')
@section('data-page', 'home')
@section('content')

    <!-- Hero Section with Image Slider -->
    <section class="relative h-[800px] flex items-center justify-center overflow-hidden bg-surface-container-low">
        <!-- Slider Content -->
        <div class="absolute inset-0 z-0">
            <div class="hero-slider-item active h-full w-full">
                <img alt="University Library" class="w-full h-full object-cover brightness-75"
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBGXyLOwSo27k1_6Ddg5vXCAGtTwpzk90fHHcbFmXnMq5aIBKeKGr0UhTzQH7c8B7Z-Zjlfpq2DTtT8Eh_VfP5YFf2SSNjtOZ_zO8ftmO4Mxzk8F_wwoHnZyFVDtTBk1jpkdPAVw_p1brUdDUJq8F8lyzl6Oy-KEqdeIOoESaDzEi0d0BRD8dGJERB0nFhYcoKDf6jcn9RAtpiE5DqymljGU-NTHD_fB9_BmG2PdLhPcVHkCNvum-DdK6erFUECPnPUAzpaw41m2hE" />
            </div>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <div class="glass-card inline-block px-10 py-16 rounded-[2rem] max-w-4xl mx-auto shadow-2xl">
                <h1
                    class="font-headline text-5xl md:text-7xl font-extrabold tracking-tight mb-6 leading-tight text-on-surface">
                    Üniversite Hayatını <span class="text-gradient">Keşfedin</span>
                </h1>
                <p
                    class="font-body text-lg md:text-xl text-on-surface-variant max-w-2xl mx-auto mb-10 leading-relaxed">
                    Etkinlikleri keşfedin, kulüplere katılın ve kampüs topluluğunuzla ağ oluşturun. Geleceğin
                    akademik ekosistemine bugün adım atın.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('etkinlikler') }}"
                        class="w-full sm:w-auto px-8 py-4 bg-gradient-primary text-white rounded-full font-bold text-lg hover:opacity-90 transition-all shadow-xl shadow-primary/30 text-center">
                        Etkinlikleri Keşfedin
                    </a>
                    <a href="{{ route('kulupler') }}"
                        class="w-full sm:w-auto px-8 py-4 bg-white border border-outline-variant text-on-surface rounded-full font-bold text-lg hover:bg-surface-container transition-all text-center">
                        Kulüpleri Keşfedin
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 border-b border-black/5 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <div class="space-y-2">
                    <div class="text-4xl font-headline font-extrabold text-gradient">100+ Kulüp</div>
                    <div class="text-on-surface-variant font-medium tracking-wide uppercase text-xs">Aktif Topluluk
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="text-4xl font-headline font-extrabold text-gradient">10K+ Öğrenci</div>
                    <div class="text-on-surface-variant font-medium tracking-wide uppercase text-xs">Kayıtlı Üye
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="text-4xl font-headline font-extrabold text-gradient">50+ Günlük Etkinlik</div>
                    <div class="text-on-surface-variant font-medium tracking-wide uppercase text-xs">Sürekli
                        Hareketlilik</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trending Events Section -->
    <section class="py-24 px-6 bg-surface-bright">
        <div class="max-w-7xl mx-auto">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-headline font-bold mb-2 text-on-surface">Trend Etkinlikler</h2>
                    <div class="h-1.5 w-20 bg-primary rounded-full"></div>
                </div>
                <a class="text-primary font-bold flex items-center hover:underline transition-all"
                    href="{{ route('etkinlikler') }}">
                    Tümünü Görüntüle <span class="material-symbols-outlined ml-1 text-sm">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Event Card 1 -->
                <a href="{{ route('etkinlik.detay', ['slug' => 'ai-gelecek-zirvesi']) }}"
                    class="bg-white rounded-2xl overflow-hidden group hover:shadow-2xl transition-all duration-300 cursor-pointer border border-black/5 block">
                    <div class="p-6">
                        <div class="flex gap-4 mb-4">
                            <div
                                class="bg-surface-container rounded-lg p-3 text-center min-w-[60px] flex flex-col justify-center items-center">
                                <span class="text-xs font-bold text-primary uppercase">Eki</span>
                                <span class="text-2xl font-bold text-on-surface">14</span>
                            </div>
                            <div class="flex-1">
                                <h3
                                    class="text-xl font-headline font-bold mb-1 group-hover:text-primary transition-colors text-on-surface">
                                    AI & Gelecek Zirvesi</h3>
                                <div class="flex items-center text-sm text-on-surface-variant">
                                    <span class="material-symbols-outlined text-sm mr-1">location_on</span>
                                    Mühendislik Fakültesi
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 mb-6 flex-wrap">
                            <span
                                class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider">Teknoloji</span>
                            <span
                                class="px-3 py-1 rounded-full bg-black/5 text-on-surface-variant text-xs font-bold uppercase tracking-wider">Seminer</span>
                        </div>
                        <img alt="Futuristic conference room" class="w-full h-48 object-cover rounded-xl"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuB0Sb1kxFQdu4q7prG4kHp4DK2fFSxKAJwVDUbApoWrvYoprYClsKcUGSSfo_2-aswx_BYOyrqkP3YRRL506im2HcW5B6iAi26snLbZxtRcPhG8V558qxLPBERafbhRwZF-2pe-Fdz9B3Y3aYdWhoLZjs9lo12JdMoYtDD5CKSdHdRy8T0zr6-PX26zcfkmhDIPiP7aj42RX0nFoQ8CNoPh1wtgBiN-x3xePrlbo9oZx1RsLT9DTUSu-2mjz7y-KpceHMAvKY2-lhM" />
                    </div>
                </a>
                <!-- Event Card 2 -->
                <a href="{{ route('etkinlik.detay', ['slug' => 'jazz-gecesi']) }}"
                    class="bg-white rounded-2xl overflow-hidden group hover:shadow-2xl transition-all duration-300 cursor-pointer border border-black/5 block">
                    <div class="p-6">
                        <div class="flex gap-4 mb-4">
                            <div
                                class="bg-surface-container rounded-lg p-3 text-center min-w-[60px] flex flex-col justify-center items-center">
                                <span class="text-xs font-bold text-primary uppercase">Eki</span>
                                <span class="text-2xl font-bold text-on-surface">18</span>
                            </div>
                            <div class="flex-1">
                                <h3
                                    class="text-xl font-headline font-bold mb-1 group-hover:text-primary transition-colors text-on-surface">
                                    Jazz Gecesi</h3>
                                <div class="flex items-center text-sm text-on-surface-variant">
                                    <span class="material-symbols-outlined text-sm mr-1">location_on</span> Kampüs
                                    Meydanı
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 mb-6 flex-wrap">
                            <span
                                class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider">Sanat</span>
                            <span
                                class="px-3 py-1 rounded-full bg-black/5 text-on-surface-variant text-xs font-bold uppercase tracking-wider">Müzik</span>
                        </div>
                        <img alt="Jazz concert" class="w-full h-48 object-cover rounded-xl"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBl6qv9GU0nkP8zaoZ5ZILWRNhRaQAhEaVfmKgszBNP-vhBsncYMP6RRrBLFsi_XGB_bIMHxoBvCgYtj9E1IynwOp2zsMHEeR8AUzY_pRSQdljsuCE62fXxdR4XD1FO-SwEkPUyhXj6I2cwxqAWaP1WBlLECbQal6KC4jHcXO3rZZEK7WIjwZQ5b6cszxJYKCl-2sXaK5lkgoz3V5Y8qt-t-Ruv7JrJiqiQ8kVbOU4X_1zmTwK26l_BsFVpHcemmPj80BkwVH0tpVo" />
                    </div>
                </a>
                <!-- Event Card 3 -->
                <a href="{{ route('etkinlik.detay', ['slug' => 'girisimcilik-atolyesi']) }}"
                    class="bg-white rounded-2xl overflow-hidden group hover:shadow-2xl transition-all duration-300 cursor-pointer border border-black/5 block">
                    <div class="p-6">
                        <div class="flex gap-4 mb-4">
                            <div
                                class="bg-surface-container rounded-lg p-3 text-center min-w-[60px] flex flex-col justify-center items-center">
                                <span class="text-xs font-bold text-primary uppercase">Eki</span>
                                <span class="text-2xl font-bold text-on-surface">22</span>
                            </div>
                            <div class="flex-1">
                                <h3
                                    class="text-xl font-headline font-bold mb-1 group-hover:text-primary transition-colors text-on-surface">
                                    Girişimcilik Atölyesi</h3>
                                <div class="flex items-center text-sm text-on-surface-variant">
                                    <span class="material-symbols-outlined text-sm mr-1">location_on</span>
                                    İnovasyon Merkezi
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 mb-6 flex-wrap">
                            <span
                                class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-wider">İş
                                Dünyası</span>
                            <span
                                class="px-3 py-1 rounded-full bg-black/5 text-on-surface-variant text-xs font-bold uppercase tracking-wider">Workshop</span>
                        </div>
                        <img alt="Workshop" class="w-full h-48 object-cover rounded-xl"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCI-phTIncpexy4EJkXKPMGa1baV2o1ojG_2f4DuEhcHTOedldxLHzYJYyR8F7ktk74ez-smjUEx2Nag2aW73HPQ8GjGRUOeGLu7PllFguxJnGfaMdsiTRWcZkBtyiiLQcB-i1nJeNeQfnKUo3hBzcyi10LXU_N0VLg9VEd7tZ5RocwmnkZLgAmJbUjSPlbPHt_DO058a_RJky9jvarVnToVOd7_ZpSu4rumwioD1wjE55qpe6UH-BAsXcGH6B7xId5iZ4wtHjAB9w" />
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Active Clubs Section -->
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="mb-16">
                <h2 class="text-4xl font-headline font-bold text-center mb-4 text-on-surface">Aktif Kulüpler</h2>
                <p class="text-on-surface-variant text-center max-w-xl mx-auto">İlgi alanlarınıza göre bir topluluk
                    seçin ve yeteneklerinizi benzer düşünen insanlarla geliştirin.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Club Card 1 -->
                <a href="{{ route('kulup.detay', ['slug' => 'robotik-ai-toplulugu']) }}"
                    class="group bg-surface-container-high rounded-3xl overflow-hidden shadow-sm flex flex-col lg:flex-row hover:shadow-xl transition-all duration-500 block text-left">
                    <div class="lg:w-1/2 relative overflow-hidden h-64 lg:h-auto">
                        <img alt="Robotics Lab"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAz7URG6_p0JO-x6_Vupc5rZ8JbvZgCrMFSuAJdKgzhqNpaj-gVzNSfclC1Rk7HfF2FgQknEUsWBormzII56uxnEcR1HAwwOl-lFAnLrmEHx377iu64rJtxsGxqvbSHj9vumlKTCVo0md5vAaOlqUBAUIYi35Y7P7cWtNb_SVjdM5zuTSpbKQxuBer-q_7ViGu7WHrNtujO5wDJpOO050AhkyLAYHmppee8rgaeM3gEZlsEdVw6WLtjlj64LLgenRQ58OWHsnA_jAc" />
                    </div>
                    <div class="lg:w-1/2 p-10 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span
                                    class="px-3 py-1 bg-primary text-white rounded-full text-[10px] font-bold uppercase tracking-tighter">Tech</span>
                                <span class="text-on-surface-variant text-sm flex items-center"><span
                                        class="material-symbols-outlined text-xs mr-1">group</span> 1.2k Üye</span>
                            </div>
                            <h3
                                class="text-2xl font-headline font-bold mb-3 text-on-surface group-hover:text-primary transition-colors">
                                Robotik ve AI Topluluğu</h3>
                            <p class="text-on-surface-variant text-sm leading-relaxed mb-6">Geleceğin
                                teknolojilerini tasarlayan ve otonom sistemler üzerinde çalışan disiplinlerarası bir
                                ekip.</p>
                        </div>
                        <div class="text-primary font-bold text-sm flex items-center gap-2">
                            Kulübü Görüntüle <span class="material-symbols-outlined">arrow_right_alt</span>
                        </div>
                    </div>
                </a>
                <!-- Club Card 2 -->
                <a href="{{ route('kulup.detay', ['slug' => 'modern-sanatlar-kolektifi']) }}"
                    class="group bg-surface-container-high rounded-3xl overflow-hidden shadow-sm flex flex-col lg:flex-row hover:shadow-xl transition-all duration-500 block text-left">
                    <div class="lg:w-1/2 relative overflow-hidden h-64 lg:h-auto">
                        <img alt="Art Studio"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBiBYs5Uii3lKc97qJYISOll8LrlpK0pGITV0BbrHJXxleWEYduH0cQIXYkGECCL4I0NJYeVjYYG-N-SPDcY8V_hPQOal5gRigZTKWkVckmb0BeTHUqw3AvSlZE9FHEJe0CPoxP_UDgHcjXnUPHc5Pi-E3P_27CVoK5s5oFVNc5voj7EW_YitSdFinwQun5-BxtjiD8gMbQGzZx-SRAVA9S1HPFr90F8Z_1NdlGmBfGMBMPwDmcaC2niIswFv6Bgc-dypwVayTVoz4" />
                    </div>
                    <div class="lg:w-1/2 p-10 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span
                                    class="px-3 py-1 bg-secondary text-white rounded-full text-[10px] font-bold uppercase tracking-tighter">Art</span>
                                <span class="text-on-surface-variant text-sm flex items-center"><span
                                        class="material-symbols-outlined text-xs mr-1">group</span> 850 Üye</span>
                            </div>
                            <h3
                                class="text-2xl font-headline font-bold mb-3 text-on-surface group-hover:text-primary transition-colors">
                                Modern Sanatlar Kolektifi</h3>
                            <p class="text-on-surface-variant text-sm leading-relaxed mb-6">Dijital ve geleneksel
                                sanatı birleştiren, sergiler düzenleyen yaratıcı bir topluluk.</p>
                        </div>
                        <div class="text-primary font-bold text-sm flex items-center gap-2">
                            Kulübü Görüntüle <span class="material-symbols-outlined">arrow_right_alt</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Campus Life Gallery Section -->
    <section class="py-24 px-6 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="mb-16">
                <h2 class="text-4xl font-headline font-bold text-center mb-4 text-on-surface">Kampüs Yaşamından
                    Kareler</h2>
                <div class="h-1 w-20 bg-primary mx-auto rounded-full"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                <div class="space-y-4 md:space-y-6">
                    <img alt="Students studying"
                        class="w-full h-80 object-cover rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuD3C89Z9V_fP17pS5W9V0wO1xX6Pz_n9R1_k4m1_u5y6u7v8w9x0y1z2a3b4c5d6e7f8g9h0i1j2k3l4m5n6o7p8q9r" />
                    <img alt="University campus"
                        class="w-full h-64 object-cover rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBGXyLOwSo27k1_6Ddg5vXCAGtTwpzk90fHHcbFmXnMq5aIBKeKGr0UhTzQH7c8B7Z-Zjlfpq2DTtT8Eh_VfP5YFf2SSNjtOZ_zO8ftmO4Mxzk8F_wwoHnZyFVDtTBk1jpkdPAVw_p1brUdDUJq8F8lyzl6Oy-KEqdeIOoESaDzEi0d0BRD8dGJERB0nFhYcoKDf6jcn9RAtpiE5DqymljGU-NTHD_fB9_BmG2PdLhPcVHkCNvum-DdK6erFUECPnPUAzpaw41m2hE" />
                </div>
                <div class="space-y-4 md:space-y-6 pt-12">
                    <img alt="Research lab"
                        class="w-full h-64 object-cover rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuB0Sb1kxFQdu4q7prG4kHp4DK2fFSxKAJwVDUbApoWrvYoprYClsKcUGSSfo_2-aswx_BYOyrqkP3YRRL506im2HcW5B6iAi26snLbZxtRcPhG8V558qxLPBERafbhRwZF-2pe-Fdz9B3Y3aYdWhoLZjs9lo12JdMoYtDD5CKSdHdRy8T0zr6-PX26zcfkmhDIPiP7aj42RX0nFoQ8CNoPh1wtgBiN-x3xePrlbo9oZx1RsLT9DTUSu-2mjz7y-KpceHMAvKY2-lhM" />
                    <img alt="Campus concert"
                        class="w-full h-80 object-cover rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBl6qv9GU0nkP8zaoZ5ZILWRNhRaQAhEaVfmKgszBNP-vhBsncYMP6RRrBLFsi_XGB_bIMHxoBvCgYtj9E1IynwOp2zsMHEeR8AUzY_pRSQdljsuCE62fXxdR4XD1FO-SwEkPUyhXj6I2cwxqAWaP1WBlLECbQal6KC4jHcXO3rZZEK7WIjwZQ5b6cszxJYKCl-2sXaK5lkgoz3V5Y8qt-t-Ruv7JrJiqiQ8kVbOU4X_1zmTwK26l_BsFVpHcemmPj80BkwVH0tpVo" />
                </div>
                <div class="space-y-4 md:space-y-6">
                    <img alt="Workshop activity"
                        class="w-full h-80 object-cover rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCI-phTIncpexy4EJkXKPMGa1baV2o1ojG_2f4DuEhcHTOedldxLHzYJYyR8F7ktk74ez-smjUEx2Nag2aW73HPQ8GjGRUOeGLu7PllFguxJnGfaMdsiTRWcZkBtyiiLQcB-i1nJeNeQfnKUo3hBzcyi10LXU_N0VLg9VEd7tZ5RocwmnkZLgAmJbUjSPlbPHt_DO058a_RJky9jvarVnToVOd7_ZpSu4rumwioD1wjE55qpe6UH-BAsXcGH6B7xId5iZ4wtHjAB9w" />
                    <img alt="Innovation center"
                        class="w-full h-64 object-cover rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuAz7URG6_p0JO-x6_Vupc5rZ8JbvZgCrMFSuAJdKgzhqNpaj-gVzNSfclC1Rk7HfF2FgQknEUsWBormzII56uxnEcR1HAwwOl-lFAnLrmEHx377iu64rJtxsGxqvbSHj9vumlKTCVo0md5vAaOlqUBAUIYi35Y7P7cWtNb_SVjdM5zuTSpbKQxuBer-q_7ViGu7WHrNtujO5wDJpOO050AhkyLAYHmppee8rgaeM3gEZlsEdVw6WLtjlj64LLgenRQ58OWHsnA_jAc" />
                </div>
                <div class="space-y-4 md:space-y-6 pt-12">
                    <img alt="Art gallery"
                        class="w-full h-64 object-cover rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBiBYs5Uii3lKc97qJYISOll8LrlpK0pGITV0BbrHJXxleWEYduH0cQIXYkGECCL4I0NJYeVjYYG-N-SPDcY8V_hPQOal5gRigZTKWkVckmb0BeTHUqw3AvSlZE9FHEJe0CPoxP_UDgHcjXnUPHc5Pi-E3P_27CVoK5s5oFVNc5voj7EW_YitSdFinwQun5-BxtjiD8gMbQGzZx-SRAVA9S1HPFr90F8Z_1NdlGmBfGMBMPwDmcaC2niIswFv6Bgc-dypwVayTVoz4" />
                    <img alt="Student community"
                        class="w-full h-80 object-cover rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBNN3R6WmQkZhUlfWfLh_59tkdibGYR_PpW_tsGWohLE-Yzcbg9IQNLKSn-qmLrL7Texrn28tCQLHxSREJNThjTPhe-burBOa4jpKDiZmHqZwXwUl7jRUaqJBKaJa9KWU06Vw9R30Qhl7yMgCtQSNl4nw5XSJhi-OZ2YJeIKZwprWmWM2OwkrA7kjvcrZSusUswl6T4vsk0KFfIzVDCLyw5MZWaH7regdhS2-DB_UmDnJAsfiB4FLUKQzQfn5jKcFYwb8D_Xvg9Y68" />
                </div>
            </div>
        </div>
    </section>

    <!-- Success Stories Section -->
    <section class="py-24 px-6 bg-surface-bright">
        <div class="max-w-7xl mx-auto">
            <div class="mb-16">
                <h2 class="text-4xl font-headline font-bold text-center mb-4 text-on-surface">Başarı Hikayeleri</h2>
                <p class="text-on-surface-variant text-center max-w-xl mx-auto">Topluluğumuzun birlikte imza attığı
                    gurur dolu anlar ve önemli başarılar.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Success Card 1 -->
                <div
                    class="bg-white p-8 rounded-3xl border border-black/5 shadow-sm hover:shadow-lg transition-all text-center">
                    <div
                        class="w-16 h-16 bg-primary-container text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-3xl">emoji_events</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-3">Robotik Ödülü</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">Robotik ekibimiz ulusal yarışmada 'En
                        İyi İnovasyon' ödülünü kazandı.</p>
                </div>
                <!-- Success Card 2 -->
                <div
                    class="bg-white p-8 rounded-3xl border border-black/5 shadow-sm hover:shadow-lg transition-all text-center">
                    <div
                        class="w-16 h-16 bg-primary-container text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-3xl">campaign</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-3">100. Etkinlik</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">Bu dönem topluluklarımız tarafından
                        düzenlenen 100. etkinliği tamamladık.</p>
                </div>
                <!-- Success Card 3 -->
                <div
                    class="bg-white p-8 rounded-3xl border border-black/5 shadow-sm hover:shadow-lg transition-all text-center">
                    <div
                        class="w-16 h-16 bg-primary-container text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-3xl">groups</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-3">Global İş Birliği</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">Modern Sanatlar Kolektifi, Avrupa'dan
                        3 farklı okul ile partnerlik kurdu.</p>
                </div>
                <!-- Success Card 4 -->
                <div
                    class="bg-white p-8 rounded-3xl border border-black/5 shadow-sm hover:shadow-lg transition-all text-center">
                    <div
                        class="w-16 h-16 bg-primary-container text-primary rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-outlined text-3xl">star</span>
                    </div>
                    <h3 class="font-headline font-bold text-on-surface mb-3">Yılın Topluluğu</h3>
                    <p class="text-on-surface-variant text-sm leading-relaxed">Girişimcilik Kulübü, en yüksek
                        öğrenci katılım oranıyla yılın kulübü seçildi.</p>
                </div>
            </div>
        </div>
    </section>
@endsection