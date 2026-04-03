@extends('layouts.app')

@section('title', 'Robotik ve Yapay Zeka Topluluğu - Fırat Üniversitesi')
@section('data-page', 'club-detail')

@section('content')
<div class="pb-20 px-4 md:px-8 max-w-7xl mx-auto">
    <!-- Hero Header Section -->
    <section class="relative mb-12 rounded-[2.5rem] overflow-hidden h-[450px] shadow-2xl">
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCr_v0x33Q4-usZs7QY_9Xlrt619u5x0TqVVQ2Y7k6aIKDMKVduDlrgu-LXVuLMHHWVla1NCIOGXSPzKEZx2Y8KpSm1aDPc4fFV3X20Noj6J9mzORmP0oWSigsOu2Q61W9P1Tw4iey6uIsj7yp4k3SWFDXLt-ntAjHqwOVsxOeCP210Ss2tczWku5-SuLAlUGOOqNLJaQCELQx2pLqx3-a_ZHnnBr62OTcWcpLYJ1gGdDjjzHrm9XDnhgL8OE34kBIuAPMsmsxKwkM')">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
        <div
            class="absolute bottom-0 left-0 p-8 md:p-16 w-full flex flex-col md:flex-row items-end justify-between gap-6">
            <div class="flex items-center gap-8">
                <div
                    class="w-28 h-28 md:w-36 md:h-36 rounded-3xl bg-white p-3 shadow-2xl transform hover:scale-105 transition-transform">
                    <div class="w-full h-full bg-primary rounded-2xl flex items-center justify-center text-white">
                        <span class="material-symbols-outlined text-5xl"
                            style="font-variation-settings: 'FILL' 1;">precision_manufacturing</span>
                    </div>
                </div>
                <div>
                    <h1
                        class="font-headline text-4xl md:text-6xl font-extrabold text-white tracking-tight leading-tight mb-4">
                        Robotik ve Yapay Zeka Topluluğu</h1>
                    <div class="flex gap-4">
                        <span
                            class="px-4 py-1.5 bg-primary text-white rounded-full text-xs font-bold uppercase tracking-widest">Teknoloji</span>
                        <span
                            class="px-4 py-1.5 bg-white/20 backdrop-blur text-white rounded-full text-xs font-bold">Aktif
                            Üye: 142</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Left Column: Primary Content -->
        <div class="lg:col-span-8 space-y-16">
            <!-- Hakkımızda Section -->
            <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100">
                <h2 class="font-headline text-3xl font-bold mb-8 flex items-center gap-4 text-on-surface">
                    <span class="w-2.5 h-10 bg-primary rounded-full"></span>
                    Hakkımızda
                </h2>
                <p class="text-on-surface-variant leading-relaxed text-lg mb-10">
                    Robotik ve Yapay Zeka Topluluğu olarak, üniversitemizin teknoloji vizyonunu geleceğe taşıyoruz.
                    Öğrencilerimize robotik sistemler, makine öğrenmesi ve veri bilimi alanlarında pratik deneyim
                    kazandırırken, ulusal ve uluslararası yarışmalarda üniversitemizi temsil ediyoruz.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-md transition-all">
                        <span class="material-symbols-outlined text-primary text-3xl mb-4">rocket_launch</span>
                        <h4 class="font-bold text-xl mb-2 text-on-surface">Misyonumuz</h4>
                        <p class="text-sm text-on-surface-variant leading-relaxed">Karmaşık problemleri teknoloji
                            ile çözen vizyoner mühendisler ve araştırmacılar yetiştirmek.</p>
                    </div>
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-md transition-all">
                        <span class="material-symbols-outlined text-primary text-3xl mb-4">visibility</span>
                        <h4 class="font-bold text-xl mb-2 text-on-surface">Vizyonumuz</h4>
                        <p class="text-sm text-on-surface-variant leading-relaxed">Yapay zeka devriminde Türkiye'nin
                            öncü akademik topluluğu ve inovasyon merkezi olmak.</p>
                    </div>
                </div>
            </div>

            <!-- Kulüp Faaliyetleri Section -->
            <div>
                <h2 class="font-headline text-3xl font-bold mb-10 text-on-surface">Kulüp Faaliyetleri</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div
                        class="group p-8 rounded-[2rem] bg-white border border-slate-100 hover:border-primary/20 hover:bg-slate-50 transition-all duration-300">
                        <div
                            class="w-14 h-14 rounded-2xl bg-primary/5 flex items-center justify-center mb-6 text-primary group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-3xl">code</span>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-on-surface">Python ve ML Workshopları</h3>
                        <p class="text-sm text-on-surface-variant leading-relaxed">Her hafta başlangıç ve orta
                            seviye yapay zeka eğitimleri düzenliyoruz.</p>
                    </div>
                    <div
                        class="group p-8 rounded-[2rem] bg-white border border-slate-100 hover:border-primary/20 hover:bg-slate-50 transition-all duration-300">
                        <div
                            class="w-14 h-14 rounded-2xl bg-primary/5 flex items-center justify-center mb-6 text-primary group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined">memory</span>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-on-surface">Drone Tasarımı</h3>
                        <p class="text-sm text-on-surface-variant leading-relaxed">Otonom uçuş algoritmaları ve gömülü
                            sistemler üzerine projeler geliştiriyoruz.</p>
                    </div>
                </div>
            </div>

            <!-- Yaklaşan Kulüp Etkinlikleri Section -->
            <div>
                <div class="flex items-center justify-between mb-10">
                    <h2 class="font-headline text-3xl font-bold text-on-surface">Yaklaşan Kulüp Etkinlikleri</h2>
                    <a href="{{ route('etkinlikler') }}" class="text-primary text-sm font-bold hover:underline">Tümünü Gör</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Event Card 1 -->
                    <div class="relative group rounded-[2.5rem] overflow-hidden aspect-video shadow-lg">
                        <img class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuA7pMXsUkd_9BCbYUHE-N1yxeFtKy_knfXnfWt5EUyf7ARcOp7G1gHmtQCflM4DvdJ45yW0EOkEzmHodceoJ-h05lGgWya0nAwYByPr77NNVQqVB6ESihuueku31lZF9VNjlxycnySg8C5M3F9LvfH8uZXRAQlpVy35vLCF49YpA06ncNG9lM93LMHwfyIaYHHCjuhWadXWvXAbAO9FAw-zy0Vqlt6lLseyLV8WX2i8u5JQc8jQ4gBEhwzACtJpZ7ntUoRGZNhGkg4" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent">
                        </div>
                        <div class="absolute bottom-0 left-0 p-8">
                            <div
                                class="bg-primary text-white text-[10px] font-extrabold px-3 py-1 rounded shadow-lg mb-4 inline-block uppercase tracking-widest">
                                14 EKİM</div>
                            <h3 class="text-2xl font-bold text-white mb-2">Yapay Zeka Zirvesi '24</h3>
                            <div class="flex items-center gap-3 text-white/80 text-sm">
                                <span class="flex items-center gap-1"><span
                                        class="material-symbols-outlined text-sm">location_on</span> Konferans
                                    Salonu</span>
                            </div>
                        </div>
                    </div>
                    <!-- Event Card 2 -->
                    <div class="relative group rounded-[2.5rem] overflow-hidden aspect-video shadow-lg">
                        <img class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBeFv3ErbOmyfsiQfow5HJMlIO9_bUuaL-wdHkDDq89W7ipzjmX-3iu7-Ep7gwzV77CL5ylEWGEoWBuR4Yax-QD2Uo6um_7PV3xhNc296wIq0bN2NCOc-4IdHHRP5kMd4LP4o92qDXCZWGTEKvo_5y5qE2Rqqyd2CxjadGpN8Y47gnK8-fWtjG81UStKI42VQv4nfM_8lKxgVlC55ZnCnqS90a5jlBRMllDQZK5XcGveQerQBtvxZJD_O0Pscd8rHU5LWWNtX5GC80" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent">
                        </div>
                        <div class="absolute bottom-0 left-0 p-8">
                            <div
                                class="bg-primary text-white text-[10px] font-extrabold px-3 py-1 rounded shadow-lg mb-4 inline-block uppercase tracking-widest">
                                22 EKİM</div>
                            <h3 class="text-2xl font-bold text-white mb-2">Arduino Lab Günleri</h3>
                            <div class="flex items-center gap-3 text-white/80 text-sm">
                                <span class="flex items-center gap-1"><span
                                        class="material-symbols-outlined text-sm">location_on</span> Mühendislik
                                    Laboratuvarı</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar -->
        <aside class="lg:col-span-4 space-y-10">
            <!-- Kulüp Bilgileri Card -->
            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm">
                <h3 class="font-headline text-2xl font-bold mb-8 text-on-surface">Kulüp Bilgileri</h3>
                <div class="space-y-6 mb-10">
                    <div class="flex items-center justify-between py-3 border-b border-slate-50">
                        <span class="text-on-surface-variant flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">person_search</span>
                            Kurucu
                        </span>
                        <span class="font-bold text-on-surface">Dr. Ahmet Tekin</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-50">
                        <span class="text-on-surface-variant flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">group</span>
                            Üye Sayısı
                        </span>
                        <span class="font-bold text-on-surface">142</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-50">
                        <span class="text-on-surface-variant flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">category</span>
                            Kategori
                        </span>
                        <span class="font-bold text-on-surface">Teknoloji</span>
                    </div>
                    <div class="flex items-center justify-between py-3 border-b border-slate-50">
                        <span class="text-on-surface-variant flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary text-xl">calendar_today</span>
                            Kuruluş
                        </span>
                        <span class="font-bold text-on-surface">2018</span>
                    </div>
                </div>
                <button
                    class="w-full py-5 bg-primary text-white rounded-2xl font-bold text-lg hover:bg-primary-dark active:scale-95 transition-all shadow-xl shadow-primary/20 flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined">person_add</span>
                    Kulübe Katıl
                </button>
            </div>

            <!-- Aktif Üyeler Card -->
            <div class="bg-white rounded-[2.5rem] p-10 border border-slate-100 shadow-sm">
                <h3 class="font-headline text-2xl font-bold mb-8 text-on-surface">Aktif Üyeler</h3>
                <div class="space-y-6">
                    <div class="flex items-center gap-5 group cursor-pointer">
                        <div
                            class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary/10 group-hover:border-primary transition-all">
                            <img class="w-full h-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDjvn0RDqRifiwy8HPUFLMBFH9L73z3AIqhZ3HuPL8zAdqLMgSV1XPKV0_AqMKQZD6KzlK6pSTKKpHFDf6ET2JY_--ilxX-ziINDYaFl4xF89KtdiMGDyBb2REFISSZPo3dUKYKWDTjgGeDYy_--KnacF-S8w_vdQSaug0KUA6T5CH2vXAXl-x1aEVnVxCKzJ5SqDtxbTqhPqzz2t_dX8GmehNcAW5Gzq4yms69drxIBFNkD94eSOzIzoKdQ3hoPEXjjn883P88Ulg" />
                        </div>
                        <div>
                            <p class="font-bold text-on-surface group-hover:text-primary transition-colors">Emir
                                Yılmaz</p>
                            <p class="text-xs text-on-surface-variant font-medium">Yönetim Kurulu Başkanı</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-5 group cursor-pointer">
                        <div
                            class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary/10 group-hover:border-primary transition-all">
                            <img class="w-full h-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBfTblEGjR31K6acuN8DqPt8eVIRCd_QrL5mtbNruvKaNsur2--QTL2ZyOGhHnXa4_j7WXZg39gW8v0UVkboBJRNszTAKM-BJy15SuhyJNTU9_Ghwo0Y9En0XrA3JtnAkT4ktwwrimKzp07Evxis08KpjhnhbxY7LzWWK6bGhKCvk_ubxM2zLqIkkVeeaTT-oevAfNK9gKifw29syJAS6ONA-qcv895S7KjjEumL0nOeYWWD5E2yTfNKSvOsjBK7emv3LZxQi-akY0" />
                        </div>
                        <div>
                            <p class="font-bold text-on-surface group-hover:text-primary transition-colors">Selin
                                Kaya</p>
                            <p class="text-xs text-on-surface-variant font-medium">Yapay Zeka Ekip Lideri</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-5 group cursor-pointer">
                        <div
                            class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary/10 group-hover:border-primary transition-all">
                            <img class="w-full h-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCwWVwJJTkpioE2tC78JmoJr2N96r1FrJuETdi4i4T3dWv8Gkb0qGxUQUMaS96M_Az6MkhMJWGLn_oD3pjUqVJpQZ9EXl6zg8vfJf_4lAyiGOnTi1PxIiVLJubZ1tyTGPjWkxhOgNA-5csnsy2hBpW-le5ZONY7kFSvKXfNTFUJI6H1f-vL1UWsLxPF2OIrNbz4RU7k1wPOu1qkQn5N-RuDnBApatd77hbzWVtM2NJ2AFjwaxZsXoJaS6mqr9iQCCJ6EjjcXK83h0I" />
                        </div>
                        <div>
                            <p class="font-bold text-on-surface group-hover:text-primary transition-colors">Can
                                Demir</p>
                            <p class="text-xs text-on-surface-variant font-medium">Robotik Ekip Lideri</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-5 group cursor-pointer">
                        <div
                            class="w-12 h-12 rounded-full overflow-hidden border-2 border-primary/10 group-hover:border-primary transition-all">
                            <img class="w-full h-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCt7VvQU6XD5LZLVRnYwz8PGLR9LWaEIg970FZLqpFPL_KznyJ8ZufC3rGAgjh8ZEbAdZY7dtzYp1J1Byci-Jk5O1zjlbocNfoUHKeykSTQsDSqWcjo7q4iC5unVoid5BIygk_tk6HOCBn5G_p0q7cfwRSuBbLRqZ73ScMx0UdKBRbvXI7iOrOY5xa4-B8SPOnRi4DSndz325kQMs6LtApUgdJlND1vCciAxsXCW3ij5bhg6ngTMhYKmMvvyBE99VmlnpFmwNKXWW0" />
                        </div>
                        <div>
                            <p class="font-bold text-on-surface group-hover:text-primary transition-colors">Melis Aksoy</p>
                            <p class="text-xs text-on-surface-variant font-medium">Etkinlik Koordinatörü</p>
                        </div>
                    </div>
                </div>
                <button
                    class="w-full mt-10 py-4 rounded-2xl border border-slate-100 text-on-surface-variant text-sm font-bold hover:bg-slate-50 transition-colors">
                    Tüm Üyeleri Görüntüle
                </button>
            </div>

            <!-- Social Card -->
            <div class="bg-surface-container rounded-[2rem] p-8">
                <h4 class="text-sm font-bold mb-6 text-on-surface-variant uppercase tracking-widest text-center">
                    Bizi Takip Edin</h4>
                <div class="flex justify-center gap-6">
                    <a class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary shadow-sm"
                        href="#">
                        <span class="material-symbols-outlined">language</span>
                    </a>
                    <a class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary shadow-sm"
                        href="#">
                        <span class="material-symbols-outlined">share</span>
                    </a>
                    <a class="w-12 h-12 rounded-2xl bg-white flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary shadow-sm"
                        href="#">
                        <span class="material-symbols-outlined">alternate_email</span>
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection