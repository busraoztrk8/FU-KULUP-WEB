@extends('layouts.app')

@section('title', 'Profilim - Fırat Üniversitesi')

@section('content')
<div class="min-h-[60vh] bg-surface-bright py-12 md:py-20 px-4 sm:px-6">
    <div class="max-w-3xl mx-auto">
        <!-- Breadcrumb style info -->
        <div class="flex items-center gap-2 text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-6 opacity-60">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Ana Sayfa</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span>Profilim</span>
        </div>

        <!-- Personal Info Card -->
        <div class="bg-white rounded-[2rem] p-8 md:p-12 border border-black/5 shadow-sm overflow-hidden relative group">
            <!-- Decorative background element -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-[4rem] group-hover:scale-110 transition-transform duration-700"></div>
            
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row items-center gap-6 md:gap-10 mb-10">
                    <div class="w-24 h-24 md:w-32 md:h-32 bg-gradient-to-br from-primary to-[#8b1d35] rounded-[2rem] flex items-center justify-center text-white shadow-xl shadow-primary/20 rotate-3 group-hover:rotate-0 transition-transform duration-500">
                        <span class="material-symbols-outlined text-4xl md:text-5xl">person</span>
                    </div>
                    <div class="text-center md:text-left">
                        <div class="inline-block px-3 py-1 bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-tighter rounded-full mb-3">
                            {{ auth()->user()->role ? auth()->user()->role->label : 'Öğrenci' }}
                        </div>
                        <h1 class="text-3xl md:text-4xl font-headline font-extrabold text-on-surface mb-1 tracking-tight">{{ $user->name }}</h1>
                        <p class="text-on-surface-variant font-medium opacity-70">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 pt-10 border-t border-black/5">
                    <!-- Dynamic Clubs List -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-primary mb-2">
                            <span class="material-symbols-outlined text-[18px]">groups</span>
                            <span class="text-xs font-bold uppercase tracking-widest">Üye Olduğum Kulüpler</span>
                        </div>
                        
                        @forelse($user->clubMemberships as $membership)
                            <div class="flex items-center justify-between p-4 bg-surface-bright rounded-2xl border border-black/5 group/item hover:border-primary/20 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary border border-black/5">
                                        <span class="material-symbols-outlined text-[20px]">hub</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface">{{ $membership->club->name }}</p>
                                        <p class="text-[10px] text-on-surface-variant opacity-60">Başvuru: {{ $membership->created_at->format('d.m.Y') }}</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 {{ $membership->status === 'approved' ? 'bg-green-500/10 text-green-600' : 'bg-amber-500/10 text-amber-600' }} text-[10px] font-bold rounded-lg uppercase tracking-tight">
                                    {{ $membership->status === 'approved' ? 'Üye' : 'Onay Bekliyor' }}
                                </span>
                            </div>
                        @empty
                            <div class="p-6 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                <p class="text-xs text-slate-500 mb-3">Henüz hiçbir kulübe üye değilsiniz.</p>
                                <a href="{{ route('home') }}#clubs" class="inline-flex items-center gap-2 text-primary text-xs font-bold hover:underline">
                                    Kulüpleri Keşfet <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Dynamic Events List -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 text-primary mb-2">
                            <span class="material-symbols-outlined text-[18px]">event_available</span>
                            <span class="text-xs font-bold uppercase tracking-widest">Kayıtlı Etkinliklerim</span>
                        </div>

                        @forelse($user->eventRegistrations as $registration)
                            <div class="flex items-center justify-between p-4 bg-surface-bright rounded-2xl border border-black/5 group/item hover:border-primary/20 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary border border-black/5">
                                        <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-on-surface truncate max-w-[150px]">{{ $registration->event->title }}</p>
                                        <p class="text-[10px] text-on-surface-variant opacity-60">{{ $registration->event->start_time->format('d.m.Y H:i') }}</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 bg-primary/10 text-primary text-[10px] font-bold rounded-lg uppercase tracking-tight">
                                    Kayıtlı
                                </span>
                            </div>
                        @empty
                            <div class="p-6 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                <p class="text-xs text-slate-500 mb-3">Kayıtlı olduğunuz bir etkinlik bulunmuyor.</p>
                                <a href="{{ route('home') }}#events" class="inline-flex items-center gap-2 text-primary text-xs font-bold hover:underline">
                                    Etkinlikleri Keşfet <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Info Message -->
                <div class="mt-12 flex gap-4 p-5 bg-primary/5 rounded-2xl border border-primary/10">
                    <span class="material-symbols-outlined text-primary text-[20px] shrink-0 mt-0.5">info</span>
                    <p class="text-xs md:text-sm text-on-surface-variant leading-relaxed font-medium">
                        Profil bilgileriniz üniversite <strong class="text-primary font-bold">CAS (Merkezi Kimlik Doğrulama)</strong> sisteminden otomatik olarak senkronize edilmektedir. Bilgilerinizde bir yanlışlık olduğunu düşünüyorsanız lütfen öğrenci işleri dairesi ile iletişime geçiniz.
                    </p>
                </div>

                <!-- Logout Button -->
                <div class="mt-8 flex justify-center md:justify-start">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-red-500 font-bold hover:text-red-700 transition-colors text-sm px-4 py-2 hover:bg-red-50 rounded-xl">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                            Sistemden Güvenli Çıkış
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
