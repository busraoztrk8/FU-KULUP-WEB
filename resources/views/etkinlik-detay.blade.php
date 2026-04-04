@extends('layouts.app')

@section('title', 'Geleceğin Teknolojileri Zirvesi 2024 - Fırat Üniversitesi')
@section('data-page', 'event-detail')

@section('content')
<!-- Hero Section -->
<section class="relative h-[350px] sm:h-[450px] md:h-[600px] w-full overflow-hidden">
    <img alt="Geleceğin Teknolojileri Zirvesi Hero" class="w-full h-full object-cover"
        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBbOYaoZIm4TdJEY_18xxAxgJndk01MZ3r7-jdE5zmQqnK-jut8HiGjrBuvYMO8r2DtKLe0lQJNe9K0kKbZ0XPROVhKJx0xHWEKmKoAGqxkTPOyGQSMLfu_xm_9ojIbrKY9_acCYDb273_eSzFqRQBaIKNzTHI0yrWynWaM5-orWxFae9L33aFgpvbIxzxGC_l2jCV55C_RONubc4scnSDrFLicoqXJ075pIWaMxxG1gPPpMLlyCMt5mw52EuVzRmbIQINYyH5015c" />
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-full p-5 sm:p-8 md:p-16 max-w-7xl mx-auto">
        <span
            class="inline-block px-3 md:px-4 py-1 md:py-1.5 rounded-full bg-primary text-white font-bold text-[10px] md:text-xs uppercase tracking-widest mb-3 md:mb-4 shadow-lg shadow-primary/20">Akademik</span>
        <h1 class="text-2xl sm:text-3xl md:text-5xl lg:text-6xl font-headline font-extrabold text-white tracking-tight mb-4 md:mb-6">Geleceğin
            Teknolojileri Zirvesi 2024</h1>
        <div class="flex flex-wrap items-center gap-4 md:gap-8 text-white/90 font-medium pb-4 md:pb-8 border-b border-white/10 text-sm md:text-base">
            <div class="flex items-center">
                <span class="material-symbols-outlined mr-2 md:mr-3 text-primary text-lg md:text-xl">calendar_today</span>
                15 Mayıs 2024
            </div>
            <div class="flex items-center">
                <span class="material-symbols-outlined mr-2 md:mr-3 text-primary text-lg md:text-xl">location_on</span>
                Mühendislik Fakültesi
            </div>
            <div class="flex items-center">
                <span class="material-symbols-outlined mr-2 md:mr-3 text-primary text-lg md:text-xl">schedule</span>
                14:00 - 17:00
            </div>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 py-12 md:py-24">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-16 items-start">
        <!-- Left Column (Main) -->
        <div class="lg:col-span-2 space-y-10 md:space-y-16">
            <div class="space-y-6 md:space-y-8">
                <h2 class="text-2xl md:text-3xl font-headline font-bold text-on-surface flex items-center">
                    <span class="w-1.5 md:w-2 h-8 md:h-10 bg-primary rounded-full mr-4 md:mr-6"></span>
                    Etkinlik Hakkında
                </h2>
                <div class="text-on-surface-variant leading-relaxed text-base md:text-lg space-y-4 md:space-y-6">
                    <p>Fırat Üniversitesi tarafından düzenlenen 2024 Geleceğin Teknolojileri Zirvesi, üniversite
                        camiamızı yapay zeka, sürdürülebilir enerji ve kuantum hesaplama alanlarındaki son
                        gelişmelerle buluşturuyor.</p>
                    <p>Bu yılki zirve, sektör liderlerini ve akademik vizyonerleri bir araya getirerek,
                        teknolojinin etik sınırlarını ve toplumsal etkilerini derinlemesine inceleyen interaktif
                        oturumlara ev sahipliği yapacaktır.</p>
                </div>
            </div>

            <div class="space-y-6 md:space-y-8">
                <h2 class="text-2xl md:text-3xl font-headline font-bold text-on-surface flex items-center">
                    <span class="w-1.5 md:w-2 h-8 md:h-10 bg-primary rounded-full mr-4 md:mr-6"></span>
                    Program Akışı
                </h2>
                <div
                    class="bg-surface-container rounded-2xl md:rounded-[2rem] overflow-hidden border border-black/5 shadow-sm">
                    <div
                        class="p-4 sm:p-6 md:p-8 flex flex-col sm:flex-row items-start sm:items-center border-b border-outline-variant hover:bg-white transition-colors duration-300 gap-2 sm:gap-0">
                        <div class="w-auto sm:w-20 md:w-24 font-bold text-primary text-lg md:text-xl tracking-tight">14:00</div>
                        <div class="flex-1">
                            <h4 class="text-base md:text-lg font-bold text-on-surface mb-1">Açılış ve Hoşgeldiniz Konuşması
                            </h4>
                            <p class="text-sm text-on-surface-variant font-medium">Rektörlük Sunumu - Ana Salon
                            </p>
                        </div>
                    </div>
                    <div
                        class="p-4 sm:p-6 md:p-8 flex flex-col sm:flex-row items-start sm:items-center border-b border-outline-variant hover:bg-white transition-colors duration-300 gap-2 sm:gap-0">
                        <div class="w-auto sm:w-20 md:w-24 font-bold text-primary text-lg md:text-xl tracking-tight">14:30</div>
                        <div class="flex-1">
                            <h4 class="text-base md:text-lg font-bold text-on-surface mb-1">Yapay Zeka ve Etik Paneli</h4>
                            <p class="text-sm text-on-surface-variant font-medium">Büyük Konferans Salonu</p>
                        </div>
                    </div>
                    <div
                        class="p-4 sm:p-6 md:p-8 flex flex-col sm:flex-row items-start sm:items-center border-b border-outline-variant hover:bg-white transition-colors duration-300 gap-2 sm:gap-0">
                        <div class="w-auto sm:w-20 md:w-24 font-bold text-primary text-lg md:text-xl tracking-tight">15:45</div>
                        <div class="flex-1">
                            <h4 class="text-base md:text-lg font-bold text-on-surface mb-1">Kahve Molası ve Network</h4>
                            <p class="text-sm text-on-surface-variant font-medium">Foyer Alanı & Bahçe</p>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6 md:p-8 flex flex-col sm:flex-row items-start sm:items-center hover:bg-white transition-colors duration-300 gap-2 sm:gap-0">
                        <div class="w-auto sm:w-20 md:w-24 font-bold text-primary text-lg md:text-xl tracking-tight">16:15</div>
                        <div class="flex-1">
                            <h4 class="text-base md:text-lg font-bold text-on-surface mb-1">Kuantum Bilişim Çalıştayı</h4>
                            <p class="text-sm text-on-surface-variant font-medium">Laboratuvar B-12</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6 md:space-y-8">
                <h2 class="text-2xl md:text-3xl font-headline font-bold text-on-surface flex items-center">
                    <span class="w-1.5 md:w-2 h-8 md:h-10 bg-primary rounded-full mr-4 md:mr-6"></span>
                    Konuşmacılar
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-8">
                    <div
                        class="flex items-center space-x-4 md:space-x-6 p-4 md:p-6 bg-surface-container rounded-2xl md:rounded-3xl border border-black/5 hover:shadow-lg transition-all duration-300">
                        <img alt="Dr. Ahmet Yılmaz"
                            class="w-14 h-14 md:w-20 md:h-20 rounded-full object-cover border-2 md:border-4 border-white shadow-sm shrink-0"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDzj0WvEDsLbv81N3cK1HXLBPOY4GP7KiFvPUmIJgcjpt6IjPSxIWMRNU_fQG6qvY53BR3jTm6wxjguqydLfaRmbWexb7TeFcARgIvt1mxuEcvYvQBfDJA-UdcmuUbvmnwsOFMB9qbcYJazw8tugLBpdZkc1SG5bCDaiHbuKZIaObXP27rgMa83lESx128BE_0rP8MbKErYWFzXI6-OQH_XMM0n6MPS-1FbihpDEXBrHNkSpch-NjxNVmPfI4ruV6It5XPrfmvgBL4" />
                        <div class="min-w-0">
                            <h4 class="text-lg md:text-xl font-bold text-on-surface mb-1 truncate">Dr. Ahmet Yılmaz</h4>
                            <p class="text-xs text-primary font-bold uppercase tracking-widest">AI Araştırmacı
                            </p>
                        </div>
                    </div>
                    <div
                        class="flex items-center space-x-4 md:space-x-6 p-4 md:p-6 bg-surface-container rounded-2xl md:rounded-3xl border border-black/5 hover:shadow-lg transition-all duration-300">
                        <img alt="Prof. Dr. Elif Kaya"
                            class="w-14 h-14 md:w-20 md:h-20 rounded-full object-cover border-2 md:border-4 border-white shadow-sm shrink-0"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBX5ID6rPWNz2RMFowsFgpZpFODX3RthqqGgXx6A8jDzV3aEWTZhwesx77y2K3i6ODsYg6qZiU6fvaYNQOO9nX0zyyBrvwk9xBMD5Ii25fEzJTZ3dI-sdXeNeb-ZvsADkjFksJdwq5VKIN0ajRK4d4jlvYJqrrPZclBWkFCW28jgT3ngNPKwtA6BBjbUnE-k7xig0VaHQtSnFDrCI6pnJGOJPU3JjP9d-2xW1CYjAH1S5mM8u5ruVwOREKG4W6DLuggT6_IKuPf5-4" />
                        <div class="min-w-0">
                            <h4 class="text-lg md:text-xl font-bold text-on-surface mb-1 truncate">Prof. Dr. Elif Kaya</h4>
                            <p class="text-xs text-primary font-bold uppercase tracking-widest">Kuantum Fizikçi
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column (Sidebar) -->
        <aside class="sticky top-24 space-y-6 md:space-y-8">
            <div
                class="bg-white p-6 sm:p-8 md:p-10 rounded-2xl md:rounded-[2.5rem] border border-black/5 shadow-2xl shadow-primary/5 relative overflow-hidden group">
                <h3 class="text-xl md:text-2xl font-headline font-bold text-on-surface mb-6 md:mb-8">Etkinlik Detayları</h3>
                <div class="space-y-5 md:space-y-8">
                    <div class="flex items-start space-x-4 md:space-x-5">
                        <div class="p-2.5 md:p-3 bg-primary/5 rounded-xl md:rounded-2xl text-primary shrink-0">
                            <span class="material-symbols-outlined text-lg md:text-xl">event</span>
                        </div>
                        <div>
                            <p
                                class="text-[10px] text-on-surface-variant font-extrabold uppercase tracking-[0.2em] mb-1">
                                Tarih</p>
                            <p class="text-on-surface font-extrabold text-base md:text-lg">15 Mayıs 2024</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4 md:space-x-5">
                        <div class="p-2.5 md:p-3 bg-primary/5 rounded-xl md:rounded-2xl text-primary shrink-0">
                            <span class="material-symbols-outlined text-lg md:text-xl">schedule</span>
                        </div>
                        <div>
                            <p
                                class="text-[10px] text-on-surface-variant font-extrabold uppercase tracking-[0.2em] mb-1">
                                Saat</p>
                            <p class="text-on-surface font-extrabold text-base md:text-lg">14:00 - 17:00</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4 md:space-x-5">
                        <div class="p-2.5 md:p-3 bg-primary/5 rounded-xl md:rounded-2xl text-primary shrink-0">
                            <span class="material-symbols-outlined text-lg md:text-xl">map</span>
                        </div>
                        <div>
                            <p
                                class="text-[10px] text-on-surface-variant font-extrabold uppercase tracking-[0.2em] mb-1">
                                Konum</p>
                            <p class="text-on-surface font-extrabold text-base md:text-lg leading-tight">Mühendislik
                                Fakültesi Konferans Salonu</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4 md:space-x-5">
                        <div class="p-2.5 md:p-3 bg-primary/5 rounded-xl md:rounded-2xl text-primary shrink-0">
                            <span class="material-symbols-outlined text-lg md:text-xl">category</span>
                        </div>
                        <div>
                            <p
                                class="text-[10px] text-on-surface-variant font-extrabold uppercase tracking-[0.2em] mb-1">
                                Kategori</p>
                            <p class="text-on-surface font-extrabold text-base md:text-lg">Akademik</p>
                        </div>
                    </div>
                </div>
                <button
                    class="w-full mt-8 md:mt-12 bg-primary hover:bg-primary-dark text-white py-4 md:py-5 rounded-xl md:rounded-2xl font-headline font-extrabold text-base md:text-lg shadow-xl shadow-primary/20 hover:shadow-primary/30 active:scale-95 transition-all">
                    Kayıt Ol
                </button>
                <!-- Small decoration -->
                <div
                    class="absolute -bottom-10 -right-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-all">
                </div>
            </div>

            <div class="bg-surface-container p-6 md:p-8 rounded-2xl md:rounded-[2rem] border border-black/5">
                <p class="text-sm text-on-surface-variant italic leading-relaxed text-center font-medium">
                    "Geleceği şekillendiren teknolojileri ilk elden deneyimlemek için yerinizi şimdiden
                    ayırtın."
                </p>
            </div>
        </aside>
    </div>
</section>

<!-- Similar Events Section -->
<section class="bg-surface-container py-16 md:py-32 border-t border-black/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-10 md:mb-20 gap-4">
            <h2 class="text-2xl md:text-4xl font-headline font-bold text-on-surface">Benzer <span
                    class="text-gradient">Etkinlikler</span></h2>
            <a class="text-primary font-bold hover:underline underline-offset-8 transition-all px-2 md:px-4 py-2 text-sm md:text-base"
                href="{{ route('etkinlikler') }}">Tümünü Gör</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-10">
            <!-- Event Card 1 -->
            <div
                class="group bg-white rounded-2xl md:rounded-[2.5rem] overflow-hidden border border-black/5 hover:border-primary/20 hover:shadow-2xl transition-all duration-500">
                <div class="relative h-48 md:h-60">
                    <img alt="Kariyer Günleri"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfEXhtkV6eNzUAftNFPgYDz8wtj1c6oTyNK8zd1I2AkuQw3r-aKPJSDqdHpZ5c2ARl28le1OieJNiVFxw8uvigx4r8Teed37mfkHMrHuFhCaM75qsq0dER-iEBUSZpm7CbO_ycJFtMulUIZm6wgW-zX_eQoKPxpKN27_vX6czSnNEWv5F88ocfHMWsN6gNtfbsLiK9fCQRUOATSKqB34lp3hh3eMXjACy3prNfR4Kc0wE52OdcLX6JuDJ6KSMW6kZc9b7Rnrst6CU" />
                    <div
                        class="absolute top-3 md:top-4 left-3 md:left-4 bg-primary text-white text-[10px] font-bold px-3 md:px-4 py-1 md:py-1.5 rounded-full uppercase tracking-widest shadow-lg shadow-primary/20">
                        Kariyer</div>
                </div>
                <div class="p-6 md:p-10">
                    <p class="text-xs text-primary font-extrabold mb-2 md:mb-3 uppercase tracking-widest">22 Mayıs 2024
                    </p>
                    <h4
                        class="text-xl md:text-2xl font-headline font-bold text-on-surface mb-4 md:mb-6 group-hover:text-primary transition-colors">
                        Kariyer Günleri '24</h4>
                    <p class="text-on-surface-variant text-sm md:text-base line-clamp-2 leading-relaxed">Mezunlarımızla
                        tanışın ve sektör lideri firmaların staj imkanlarını keşfedin.</p>
                </div>
            </div>
            <!-- Additional Cards could follow here -->
        </div>
    </div>
</section>
@endsection