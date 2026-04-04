@extends('layouts.app')

@section('title', 'Kulüpler - Fırat Üniversitesi')
@section('data-page', 'clubs')
@section('page-title', 'Kulüpler')

@section('content')
    <div class="px-4 sm:px-6 md:px-12 max-w-7xl mx-auto pb-12">
        <!-- Hero Section: Featured Club -->
        <section class="mb-8 md:mb-16">
            <div class="relative overflow-hidden rounded-2xl md:rounded-3xl min-h-[280px] sm:min-h-[320px] md:h-[400px] flex items-center group shadow-2xl">
                <div class="absolute inset-0 z-0">
                    <img alt="Featured Club Image"
                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 brightness-[0.4] sm:brightness-50 md:brightness-100"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuAH9YWEBRaBtqZVOCik61rPkOarwFZBQgzK__lLaxDmwz7CKJFgLfEqSM-_wtbAwfy53A5fvvEHx4Zan2pmdhYj2FEayrIEHFOzZhxyU6KhSVCWcDRBYQQzamLTp0969xDOWo9Bajjcv85OGxUGrM4HwUUDTrHrTmILhIjvefhuJD0fBXvP8QA_MSHpokpSyqwLaCOGlI7IREDWchxqYkHdICe-MI0McOOvuj4ki3yI4e1tZ1Zeu-9qPSdqtvKfCEg1b2XdfrAyJEw" />
                    <div class="absolute inset-0 bg-gradient-to-r from-[#5d1021] md:from-[#5d1021]/90 via-[#5d1021]/80 md:via-[#5d1021]/60 to-transparent"></div>
                </div>
                <div class="relative z-10 px-5 sm:px-8 md:px-16 max-w-2xl text-white py-8 md:py-0">
                    <div class="inline-flex items-center px-3 md:px-4 py-1 md:py-1.5 rounded-full bg-white/20 mb-3 md:mb-6 backdrop-blur-md">
                        <span class="material-symbols-outlined text-sm mr-1.5 md:mr-2"
                            style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="text-[10px] md:text-xs font-bold uppercase tracking-wider">Haftanın Kulübü</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl md:text-5xl font-bold font-headline leading-tight mb-3 md:mb-4 tracking-tight">Robotik ve Yapay Zeka
                        Topluluğu</h1>
                    <p class="text-white/80 text-sm md:text-lg mb-5 md:mb-8 line-clamp-2 md:line-clamp-3">Geleceği bugün inşa ediyoruz. Otonom sistemlerden
                        derin öğrenmeye kadar teknolojinin en uç noktalarını beraber keşfediyoruz.</p>
                    <div class="flex">
                        <a href="{{ route('kulup.detay', ['slug' => 'robotik-ve-yapay-zeka-toplulugu']) }}"
                            class="bg-white text-primary px-5 sm:px-6 md:px-8 py-2.5 md:py-3 rounded-full font-bold hover:bg-slate-100 transition-all flex items-center shadow-lg active:scale-95 text-sm md:text-base">
                            Kulübü Görüntüle
                            <span class="material-symbols-outlined ml-1.5 md:ml-2">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Search & Filter Area -->
        <section class="mb-8 md:mb-12">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 md:gap-6">
                <div class="relative flex-1 max-w-2xl">
                    <span
                        class="absolute left-3 md:left-4 top-1/2 -translate-y-1/2 material-symbols-outlined text-slate-400">search</span>
                    <input id="club-search"
                        class="w-full bg-white border border-black/5 rounded-xl md:rounded-2xl py-3 md:py-4 pl-10 md:pl-12 pr-4 focus:ring-2 focus:ring-primary/20 focus:border-primary text-slate-900 placeholder:text-slate-400 transition-all shadow-sm text-sm md:text-base"
                        placeholder="Kulüp ara..." type="text" />
                </div>
                <div class="flex overflow-x-auto pb-2 lg:pb-0 scrollbar-hide gap-2 no-scrollbar">
                    <button data-filter="all"
                        class="whitespace-nowrap px-4 md:px-6 py-2 md:py-2.5 rounded-full bg-primary text-white font-bold text-xs md:text-sm transition-all shadow-lg shadow-primary/20">Hepsi</button>
                    <button data-filter="tech"
                        class="whitespace-nowrap px-4 md:px-6 py-2 md:py-2.5 rounded-full bg-white hover:bg-primary hover:text-white text-slate-600 font-bold text-xs md:text-sm transition-all border border-black/5 shadow-sm">Teknoloji</button>
                    <button data-filter="art"
                        class="whitespace-nowrap px-4 md:px-6 py-2 md:py-2.5 rounded-full bg-white hover:bg-primary hover:text-white text-slate-600 font-bold text-xs md:text-sm transition-all border border-black/5 shadow-sm">Sanat</button>
                    <button data-filter="sport"
                        class="whitespace-nowrap px-4 md:px-6 py-2 md:py-2.5 rounded-full bg-white hover:bg-primary hover:text-white text-slate-600 font-bold text-xs md:text-sm transition-all border border-black/5 shadow-sm">Spor</button>
                    <button data-filter="startup"
                        class="whitespace-nowrap px-4 md:px-6 py-2 md:py-2.5 rounded-full bg-white hover:bg-primary hover:text-white text-slate-600 font-bold text-xs md:text-sm transition-all border border-black/5 shadow-sm">Girişimcilik</button>
                </div>
            </div>
        </section>

        <!-- Clubs Grid -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
            <!-- Club Card 1 -->
            <div data-category="startup"
                class="burgundy-card group rounded-2xl md:rounded-3xl overflow-hidden flex flex-col h-full shadow-lg hover:shadow-2xl transition-all duration-500 border border-white/10 cursor-pointer">
                <div class="h-40 md:h-48 overflow-hidden relative">
                    <img alt="Girişimcilik Kulübü"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuD2yjZhfW-suXNJU1TqIWIBIJHL_gVbtxSU5iUpRb0rTXLxbJ3RzX9DPlitrc9TaMnKeKOO5HC7arDIEZ6wpiDTY-kjk6ecU55UDvl-SQy5cxmzRW2baOvCAD2_JfOH1i8w1Rc9DwS_MOajR-VGBsa40ZH7DVEj-fPYk5ak1xzKZ0Z_-bJm9rxBNGFTFWkvjZ5thRE9hcQyunTt1WgXeMjFeEpXQQDzNnuSveM_BLWkma-RccoKcItJzagOV49A-zmYZ7lMc2Sa24Y" />
                    <div
                        class="absolute top-3 md:top-4 left-3 md:left-4 bg-white/20 backdrop-blur-md px-2.5 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">
                        Girişimcilik
                    </div>
                </div>
                <div class="p-5 md:p-8 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-3 md:mb-4 gap-2">
                        <h3 data-club-name
                            class="text-lg md:text-2xl font-bold font-headline text-white group-hover:text-white/90 transition-colors">
                            İnovasyon Liderleri</h3>
                        <div class="flex items-center text-white/70 text-xs font-bold whitespace-nowrap shrink-0">
                            <span class="material-symbols-outlined text-sm mr-1">group</span>
                            2.4K Üye
                        </div>
                    </div>
                    <p class="text-white/80 text-sm mb-5 md:mb-8 leading-relaxed line-clamp-3">Yeni nesil startup ekosistemini
                        tanıyor, yatırımcılarla buluşuyor ve fikirlerimizi hayata geçiriyoruz.</p>
                    <a href="{{ route('kulup.detay', ['slug' => 'inovasyon-liderleri']) }}"
                        class="mt-auto w-full py-3 md:py-4 rounded-xl md:rounded-2xl bg-white text-primary font-bold hover:bg-slate-100 transition-all flex justify-center items-center active:scale-95 shadow-lg text-sm md:text-base">
                        Kulübü Görüntüle
                    </a>
                </div>
            </div>

            <!-- Club Card 2 -->
            <div data-category="art"
                class="burgundy-card group rounded-2xl md:rounded-3xl overflow-hidden flex flex-col h-full shadow-lg hover:shadow-2xl transition-all duration-500 border border-white/10 cursor-pointer">
                <div class="h-40 md:h-48 overflow-hidden relative">
                    <img alt="Sanat Kulübü"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBSeLtSlTffI7rprp5BJvBVL-tHLnHC9w7BvfoRUG_AK_x8FsjwYNQ4gM5g1MIOQ5XgXiuIv1fOZ3I_WzcBU0xZPK63xs01ygH9IEkaNlIGzhUzUII2qveImtuAU2rXAqrpcrQtQR-AdcsndnqjPIsElEX-w87k_TNVjrLT8dPz7MmutH8DjksRUq3AD9hDaMIUPfXzrP8YADmeQT986Q7C3X9aGfDj0MlgHPH12kpMOYRKHiBQGafKnq7dS2AKAX2MgkfT2dqTaqQ" />
                    <div
                        class="absolute top-3 md:top-4 left-3 md:left-4 bg-white/20 backdrop-blur-md px-2.5 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">
                        Sanat
                    </div>
                </div>
                <div class="p-5 md:p-8 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-3 md:mb-4 gap-2">
                        <h3 data-club-name
                            class="text-lg md:text-2xl font-bold font-headline text-white group-hover:text-white/90 transition-colors">
                            Modern Sanat Atölyesi</h3>
                        <div class="flex items-center text-white/70 text-xs font-bold whitespace-nowrap shrink-0">
                            <span class="material-symbols-outlined text-sm mr-1">group</span>
                            850 Üye
                        </div>
                    </div>
                    <p class="text-white/80 text-sm mb-5 md:mb-8 leading-relaxed line-clamp-3">Dijital sanattan klasik tekniklere
                        kadar geniş bir yelpazede yaratıcılığımızı serbest bırakıyoruz.</p>
                    <a href="{{ route('kulup.detay', ['slug' => 'modern-sanat-atolyesi']) }}"
                        class="mt-auto w-full py-3 md:py-4 rounded-xl md:rounded-2xl bg-white text-primary font-bold hover:bg-slate-100 transition-all flex justify-center items-center active:scale-95 shadow-lg text-sm md:text-base">
                        Kulübü Görüntüle
                    </a>
                </div>
            </div>

            <!-- Club Card 3 -->
            <div data-category="social"
                class="burgundy-card group rounded-2xl md:rounded-3xl overflow-hidden flex flex-col h-full shadow-lg hover:shadow-2xl transition-all duration-500 border border-white/10 cursor-pointer">
                <div class="h-40 md:h-48 overflow-hidden relative">
                    <img alt="Sosyal Sorumluluk Kulübü"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDyZZ2jIOYNYV0ekLEWCPlnYoKb4SlLtvAov-OEPV6IxxXMTvxV5uXe_BVkfwwlPHKgNhM4F2Q5y-6PKvLTz_U-AZpndyPzCFIw38dZ7jyoYcxetA2wnsbZ82ZFoWglgzmnzoWrG4p-HcXyYO2C4zv2juBwzuW8pn3lq6rk9eXoOWYGYGfJKaZb1AODJyUiRbARmObKmv0BR6LZO-2ZvFDtEkoT84DnZJ7EDLSnn6o6vJ5EIe_d16LgzKZ2XjiwPBZjC2VZNslPJq4" />
                    <div
                        class="absolute top-3 md:top-4 left-3 md:left-4 bg-white/20 backdrop-blur-md px-2.5 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">
                        Sosyal
                    </div>
                </div>
                <div class="p-5 md:p-8 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-3 md:mb-4 gap-2">
                        <h3 data-club-name
                            class="text-lg md:text-2xl font-bold font-headline text-white group-hover:text-white/90 transition-colors">
                            Gönüllü Kalpler</h3>
                        <div class="flex items-center text-white/70 text-xs font-bold whitespace-nowrap shrink-0">
                            <span class="material-symbols-outlined text-sm mr-1">group</span>
                            3.1K Üye
                        </div>
                    </div>
                    <p class="text-white/80 text-sm mb-5 md:mb-8 leading-relaxed line-clamp-3">Toplumsal sorunlara çözüm üretiyor,
                        sürdürülebilir bir gelecek için projeler geliştiriyoruz.</p>
                    <a href="{{ route('kulup.detay', ['slug' => 'gonullu-kalpler']) }}"
                        class="mt-auto w-full py-3 md:py-4 rounded-xl md:rounded-2xl bg-white text-primary font-bold hover:bg-slate-100 transition-all flex justify-center items-center active:scale-95 shadow-lg text-sm md:text-base">
                        Kulübü Görüntüle
                    </a>
                </div>
            </div>

            <!-- Club Card 4 -->
            <div data-category="sport"
                class="burgundy-card group rounded-2xl md:rounded-3xl overflow-hidden flex flex-col h-full shadow-lg hover:shadow-2xl transition-all duration-500 border border-white/10 cursor-pointer">
                <div class="h-40 md:h-48 overflow-hidden relative">
                    <img alt="Spor Kulübü"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuC-flVPphiEl7m9GOdb17yi3gARjMJNx5SZbY4DQCfhlwvjXIIDJRRoaeUPqtsxr69RUR74riG7IO43NR3DdCP8NKUDJIJcwwm7HyjgJUXt9acxhaobAW58Swi9RFiYZNTMW4bv8Y_GJWskJs9Vp8YNC3R6oXfRxfJNJ8kySYAlEY4JqhD8EByS9rO5t3LpM83lwPr3OvioFRrwXHMpQBTioaD_XYVR92DbtZHtAJ2--suzFEnUBIx1447lNCikCSGIv3PcyrWrcE" />
                    <div
                        class="absolute top-3 md:top-4 left-3 md:left-4 bg-white/20 backdrop-blur-md px-2.5 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">
                        Spor
                    </div>
                </div>
                <div class="p-5 md:p-8 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-3 md:mb-4 gap-2">
                        <h3 data-club-name
                            class="text-lg md:text-2xl font-bold font-headline text-white group-hover:text-white/90 transition-colors">
                            E-Spor ve Gaming</h3>
                        <div class="flex items-center text-white/70 text-xs font-bold whitespace-nowrap shrink-0">
                            <span class="material-symbols-outlined text-sm mr-1">group</span>
                            4.2K Üye
                        </div>
                    </div>
                    <p class="text-white/80 text-sm mb-5 md:mb-8 leading-relaxed line-clamp-3">Turnuvalar düzenliyor, strateji
                        geliştiriyor ve rekabetçi oyun dünyasında zirveyi hedefliyoruz.</p>
                    <a href="{{ route('kulup.detay', ['slug' => 'e-spor-ve-gaming']) }}"
                        class="mt-auto w-full py-3 md:py-4 rounded-xl md:rounded-2xl bg-white text-primary font-bold hover:bg-slate-100 transition-all flex justify-center items-center active:scale-95 shadow-lg text-sm md:text-base">
                        Kulübü Görüntüle
                    </a>
                </div>
            </div>

            <!-- Club Card 5 -->
            <div data-category="tech"
                class="burgundy-card group rounded-2xl md:rounded-3xl overflow-hidden flex flex-col h-full shadow-lg hover:shadow-2xl transition-all duration-500 border border-white/10 cursor-pointer">
                <div class="h-40 md:h-48 overflow-hidden relative">
                    <img alt="Teknoloji Kulübü"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuC_MHEhw-ExpIKRoN-DxJ7ja7IeNXCN2FEvIS3MYbVQCFZ2VT8qwBHa4ZmBb9gZMZ5BC5SpAnrsCLynEhTDvyjiPPK_FNkKPieCf_kslct_hiGmnxzhanfJezgJKBIWP_OFsUzM6ytmmpJyy3TTV87rCVCxQZogLdtU9N3wUJteeOBe4bbGNiG75YeCswwz3xx_5tRbAuRsDiJiVcJ1NfQOZbAdEr6296QoGch8HqVo4K2m-iujnTxkHgC9y1y82X8ESkQ1QPg08GI" />
                    <div
                        class="absolute top-3 md:top-4 left-3 md:left-4 bg-white/20 backdrop-blur-md px-2.5 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">
                        Teknoloji
                    </div>
                </div>
                <div class="p-5 md:p-8 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-3 md:mb-4 gap-2">
                        <h3 data-club-name
                            class="text-lg md:text-2xl font-bold font-headline text-white group-hover:text-white/90 transition-colors">
                            Siber Güvenlik Ekibi</h3>
                        <div class="flex items-center text-white/70 text-xs font-bold whitespace-nowrap shrink-0">
                            <span class="material-symbols-outlined text-sm mr-1">group</span>
                            1.1K Üye
                        </div>
                    </div>
                    <p class="text-white/80 text-sm mb-5 md:mb-8 leading-relaxed line-clamp-3">Dijital dünyayı güvenli hale getirmek
                        için CTF yarışmalarına katılıyor ve etik hacking çalışmaları yapıyoruz.</p>
                    <a href="{{ route('kulup.detay', ['slug' => 'siber-guvenlik-ekibi']) }}"
                        class="mt-auto w-full py-3 md:py-4 rounded-xl md:rounded-2xl bg-white text-primary font-bold hover:bg-slate-100 transition-all flex justify-center items-center active:scale-95 shadow-lg text-sm md:text-base">
                        Kulübü Görüntüle
                    </a>
                </div>
            </div>

            <!-- Club Card 6 -->
            <div data-category="social"
                class="burgundy-card group rounded-2xl md:rounded-3xl overflow-hidden flex flex-col h-full shadow-lg hover:shadow-2xl transition-all duration-500 border border-white/10 cursor-pointer">
                <div class="h-40 md:h-48 overflow-hidden relative">
                    <img alt="Sosyal Kulüp"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBhd6ZPuZUEnjY361eI8g5W_s4kXE2Zo6ITFd9WFjuWS3l3yqaxhHpWe5NxvDpCMvusLd7zyLQ3ZVEeC2ZPLPN7V8a-JMnIFnLEhNPHXZVf5koVaWbZMYgYfKNDxqZXajdhueuTZ08eshWysuSbXBFCg_DNvTyGgFtPlqqfX6F5uKfk8yDLvuFF23aiulEDgDDRmZv_6Dyoc-n5z6ZO5i4V5a1glS18RDEjPDVuMGxBWdLnL6KCY-1vKOiCl1MOW4rSycNoN1F8PMM" />
                    <div
                        class="absolute top-3 md:top-4 left-3 md:left-4 bg-white/20 backdrop-blur-md px-2.5 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] font-bold text-white uppercase tracking-widest">
                        Sosyal
                    </div>
                </div>
                <div class="p-5 md:p-8 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-3 md:mb-4 gap-2">
                        <h3 data-club-name
                            class="text-lg md:text-2xl font-bold font-headline text-white group-hover:text-white/90 transition-colors">
                            Gezi ve Fotoğrafçılık</h3>
                        <div class="flex items-center text-white/70 text-xs font-bold whitespace-nowrap shrink-0">
                            <span class="material-symbols-outlined text-sm mr-1">group</span>
                            1.8K Üye
                        </div>
                    </div>
                    <p class="text-white/80 text-sm mb-5 md:mb-8 leading-relaxed line-clamp-3">Yeni yerler keşfediyor, anları
                        ölümsüzleştiriyor ve vizyonumuzu gezerek genişletiyoruz.</p>
                    <a href="{{ route('kulup.detay', ['slug' => 'gezi-ve-fotografcilik']) }}"
                        class="mt-auto w-full py-3 md:py-4 rounded-xl md:rounded-2xl bg-white text-primary font-bold hover:bg-slate-100 transition-all flex justify-center items-center active:scale-95 shadow-lg text-sm md:text-base">
                        Kulübü Görüntüle
                    </a>
                </div>
            </div>
        </section>

        <!-- Pagination -->
        <div class="mt-10 md:mt-16 flex justify-center items-center space-x-2 md:space-x-3">
            <button
                class="w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl flex items-center justify-center bg-white text-slate-400 hover:bg-primary hover:text-white transition-all border border-black/5 shadow-sm">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <button
                class="w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl flex items-center justify-center bg-primary text-white font-bold shadow-lg shadow-primary/30 text-sm md:text-base">1</button>
            <button
                class="w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl flex items-center justify-center bg-white text-slate-600 hover:bg-primary hover:text-white transition-all border border-black/5 shadow-sm font-bold text-sm md:text-base">2</button>
            <button
                class="w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl flex items-center justify-center bg-white text-slate-600 hover:bg-primary hover:text-white transition-all border border-black/5 shadow-sm font-bold text-sm md:text-base">3</button>
            <span class="px-1 md:px-2 text-slate-400 font-bold">...</span>
            <button
                class="w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl flex items-center justify-center bg-white text-slate-400 hover:bg-primary hover:text-white transition-all border border-black/5 shadow-sm">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>
        </div>
    </div>
@endsection