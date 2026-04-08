@extends('layouts.app')
@section('title', $event->title . ' - Fırat Üniversitesi')
@section('data-page', 'event-detail')

@section('content')

{{-- Hero --}}
<section class="relative h-[350px] sm:h-[450px] md:h-[550px] w-full overflow-hidden">
    @if($event->image)
        <img src="{{ str_starts_with($event->image, 'http') ? $event->image : asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover"/>
    @else
        <div class="w-full h-full bg-gradient-to-br from-primary to-primary-dark"></div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
    <div class="absolute bottom-0 w-full p-5 sm:p-8 md:p-14 max-w-7xl mx-auto left-1/2 -translate-x-1/2">
        @if($event->category)
            <span class="inline-block px-3 py-1 rounded-full bg-primary text-white font-bold text-[10px] uppercase tracking-widest mb-3 shadow-lg">
                {{ $event->category->name }}
            </span>
        @endif
        <h1 class="text-2xl sm:text-3xl md:text-5xl font-headline font-extrabold text-white tracking-tight mb-4 leading-tight">
            {{ $event->title }}
        </h1>
        <div class="flex flex-wrap items-center gap-4 md:gap-8 text-white/90 text-sm md:text-base pb-4 border-b border-white/10">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-lg">calendar_today</span>
                {{ $event->start_time->format('d M Y') }}
            </div>
            @if($event->location)
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-lg">location_on</span>
                {{ $event->location }}
            </div>
            @endif
            @if($event->start_time)
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-lg">schedule</span>
                {{ $event->start_time->format('H:i') }}
                @if($event->end_time) - {{ $event->end_time->format('H:i') }} @endif
            </div>
            @endif
        </div>
    </div>
</section>

{{-- İçerik --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-14 items-start">

        {{-- Sol: Ana İçerik --}}
        <div class="lg:col-span-2 space-y-10">

            {{-- Açıklama --}}
            <div>
                <h2 class="text-2xl font-headline font-bold text-on-surface flex items-center mb-5">
                    <span class="w-1.5 h-8 bg-primary rounded-full mr-4"></span>
                    Etkinlik Hakkında
                </h2>
                <div class="text-on-surface-variant leading-relaxed text-base space-y-4">
                    {!! nl2br(e($event->description)) !!}
                </div>
            </div>

            {{-- Kulüp Bilgisi --}}
            @if($event->club)
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 flex flex-col sm:flex-row items-start sm:items-center gap-5">
                <div class="w-16 h-16 rounded-xl bg-primary flex items-center justify-center shrink-0">
                    @if($event->club->logo)
                        <img src="{{ str_starts_with($event->club->logo, 'http') ? $event->club->logo : asset('storage/' . $event->club->logo) }}" class="w-full h-full object-cover rounded-xl" alt="{{ $event->club->name }}"/>
                    @else
                        <span class="material-symbols-outlined text-white text-[28px]">groups</span>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Düzenleyen Kulüp</p>
                    <h3 class="text-lg font-bold text-slate-800 truncate">{{ $event->club->name }}</h3>
                    @if($event->club->short_description)
                        <p class="text-sm text-slate-500 mt-1 line-clamp-1">{{ $event->club->short_description }}</p>
                    @endif
                </div>
                <a href="{{ route('kulup.detay', $event->club->slug) }}"
                    class="shrink-0 px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-dark transition-all active:scale-95">
                    Kulübü Gör
                </a>
            </div>
            @endif

            {{-- Konum Haritası (varsa) --}}
            @if($event->location_url)
            <div>
                <h2 class="text-2xl font-headline font-bold text-on-surface flex items-center mb-5">
                    <span class="w-1.5 h-8 bg-primary rounded-full mr-4"></span>
                    Konum
                </h2>
                <div class="bg-slate-100 rounded-2xl overflow-hidden h-48 flex items-center justify-center">
                    <a href="{{ $event->location_url }}" target="_blank"
                        class="flex flex-col items-center gap-2 text-primary hover:text-primary-dark transition-colors">
                        <span class="material-symbols-outlined text-[48px]">map</span>
                        <span class="font-bold text-sm">Haritada Görüntüle</span>
                    </a>
                </div>
            </div>
            @endif

        </div>

        {{-- Sağ: Sidebar --}}
        <aside class="sticky top-24 space-y-6">

            {{-- Detay Kartı --}}
            <div class="bg-white p-6 md:p-8 rounded-2xl border border-black/5 shadow-xl shadow-primary/5 relative overflow-hidden">
                <h3 class="text-xl font-headline font-bold text-on-surface mb-6">Etkinlik Detayları</h3>
                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="p-2.5 bg-primary/5 rounded-xl text-primary shrink-0">
                            <span class="material-symbols-outlined">event</span>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Tarih</p>
                            <p class="font-bold text-slate-800">{{ $event->start_time->format('d M Y, l') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="p-2.5 bg-primary/5 rounded-xl text-primary shrink-0">
                            <span class="material-symbols-outlined">schedule</span>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Saat</p>
                            <p class="font-bold text-slate-800">
                                {{ $event->start_time->format('H:i') }}
                                @if($event->end_time) — {{ $event->end_time->format('H:i') }} @endif
                            </p>
                        </div>
                    </div>
                    @if($event->location)
                    <div class="flex items-start gap-4">
                        <div class="p-2.5 bg-primary/5 rounded-xl text-primary shrink-0">
                            <span class="material-symbols-outlined">map</span>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Konum</p>
                            <p class="font-bold text-slate-800 leading-snug">{{ $event->location }}</p>
                        </div>
                    </div>
                    @endif
                    @if($event->category)
                    <div class="flex items-start gap-4">
                        <div class="p-2.5 bg-primary/5 rounded-xl text-primary shrink-0">
                            <span class="material-symbols-outlined">category</span>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Kategori</p>
                            <p class="font-bold text-slate-800">{{ $event->category->name }}</p>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Durum badge --}}
                @php
                    $statusMap = ['published' => ['Aktif', 'bg-green-100 text-green-700'], 'draft' => ['Taslak', 'bg-slate-100 text-slate-600'], 'cancelled' => ['İptal Edildi', 'bg-red-100 text-red-600'], 'completed' => ['Tamamlandı', 'bg-blue-100 text-blue-700']];
                    [$statusLabel, $statusClass] = $statusMap[$event->status] ?? ['Bilinmiyor', 'bg-slate-100 text-slate-600'];
                @endphp
                <div class="mt-6 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Durum</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusClass }}">{{ $statusLabel }}</span>
                </div>

                @if($event->status === 'published')
                @auth
                @php
                    $registration = \App\Models\EventRegistration::where('event_id', $event->id)
                        ->where('user_id', auth()->id())
                        ->first();
                @endphp

                @if(session('success'))
                <div class="mb-3 p-3 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>{{ session('success') }}
                </div>
                @endif
                @if(session('info'))
                <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-700 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">info</span>{{ session('info') }}
                </div>
                @endif
                @if(session('error'))
                <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">error</span>{{ session('error') }}
                </div>
                @endif

                @if(!$registration || $registration->status === 'cancelled')
                    <button type="button" onclick="showRegistrationModal()"
                        class="w-full mt-6 bg-primary hover:bg-primary-dark text-white py-4 rounded-xl font-bold text-base shadow-xl shadow-primary/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">how_to_reg</span>
                        Kayıt Ol
                    </button>
                @elseif($registration->status === 'registered')
                    <div class="w-full mt-6 bg-green-50 border border-green-200 text-green-700 py-4 rounded-xl font-bold text-sm text-center flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                        Kayıtlısınız
                    </div>
                    <form action="{{ route('etkinlik.iptal', $event) }}" method="POST" class="mt-2">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Kaydınızı iptal etmek istediğinize emin misiniz?')"
                            class="w-full py-2.5 border border-red-200 text-red-500 rounded-xl text-sm font-medium hover:bg-red-50 transition-all">
                            Kaydı İptal Et
                        </button>
                    </form>
                @endif

                @else
                <a href="{{ route('login') }}"
                    class="w-full mt-6 bg-primary hover:bg-primary-dark text-white py-4 rounded-xl font-bold text-base shadow-xl shadow-primary/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">login</span>
                    Giriş Yap ve Kayıt Ol
                </a>
                @endauth
                @elseif($event->status === 'completed')
                <div class="w-full mt-6 bg-slate-100 text-slate-500 py-4 rounded-xl font-bold text-base text-center">
                    Etkinlik Tamamlandı
                </div>
                @elseif($event->status === 'cancelled')
                <div class="w-full mt-6 bg-red-50 text-red-500 py-4 rounded-xl font-bold text-base text-center">
                    Etkinlik İptal Edildi
                </div>
                @endif

                <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-primary/5 rounded-full blur-2xl"></div>
            </div>

            {{-- Paylaş --}}
            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 text-center">Paylaş</p>
                <div class="flex justify-center gap-3">
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($event->title) }}&url={{ urlencode(request()->url()) }}"
                        target="_blank"
                        class="w-10 h-10 rounded-xl bg-white flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">share</span>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($event->title . ' ' . request()->url()) }}"
                        target="_blank"
                        class="w-10 h-10 rounded-xl bg-white flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">chat</span>
                    </a>
                    <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); alert('Link kopyalandı!')"
                        class="w-10 h-10 rounded-xl bg-white flex items-center justify-center hover:bg-primary hover:text-white transition-all text-primary shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">link</span>
                    </button>
                </div>
            </div>

        </aside>
    </div>
</section>

{{-- Benzer Etkinlikler --}}
@if($similar->count() > 0)
<section class="bg-slate-50 py-14 md:py-20 border-t border-black/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
        <div class="flex justify-between items-end mb-8">
            <h2 class="text-2xl md:text-3xl font-headline font-bold text-on-surface">
                Benzer <span class="text-gradient">Etkinlikler</span>
            </h2>
            <a href="{{ route('etkinlikler') }}" class="text-primary font-bold text-sm hover:underline">Tümünü Gör</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach($similar as $s)
            <a href="{{ route('etkinlik.detay', $s->slug) }}"
                class="group bg-white rounded-2xl overflow-hidden border border-black/5 hover:border-primary/20 hover:shadow-xl transition-all duration-300">
                <div class="relative h-44 overflow-hidden">
                    @if($s->image)
                        <img src="{{ asset('storage/' . $s->image) }}" alt="{{ $s->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"/>
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-primary/20 to-primary/40 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-[48px]">event</span>
                        </div>
                    @endif
                    @if($s->category)
                    <div class="absolute top-3 left-3 bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                        {{ $s->category->name }}
                    </div>
                    @endif
                </div>
                <div class="p-5">
                    <p class="text-xs text-primary font-bold uppercase tracking-wider mb-2">
                        {{ $s->start_time->format('d M Y') }}
                    </p>
                    <h4 class="font-bold text-slate-800 group-hover:text-primary transition-colors line-clamp-2 leading-snug">
                        {{ $s->title }}
                    </h4>
                    @if($s->location)
                    <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">location_on</span>
                        {{ $s->location }}
                    </p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
    function showRegistrationModal() {
        document.getElementById('registration-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function hideRegistrationModal() {
        document.getElementById('registration-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>
@endpush

{{-- Registration Modal --}}
<div id="registration-modal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="hideRegistrationModal()"></div>
    <div class="relative bg-white rounded-3xl w-full max-w-lg mx-4 shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <div class="bg-primary p-8 text-white relative">
            <h3 class="text-2xl font-headline font-bold mb-2">Etkinliğe Kayıt Ol</h3>
            <p class="text-white/80 text-sm leading-relaxed">
                Bu etkinliğe kayıt olarak aynı zamanda <strong>{{ $event->club->name }}</strong> kulübüne üyelik başvurusunda bulunmuş olacaksınız.
            </p>
            <button onclick="hideRegistrationModal()" class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors">
                <span class="material-symbols-outlined font-bold">close</span>
            </button>
            <div class="absolute -bottom-12 -right-12 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
        </div>
        
        <form action="{{ route('etkinlik.kayit', $event) }}" method="POST" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Başvuru Notu (İsteğe Bağlı)</label>
                <textarea name="note" rows="4" 
                    placeholder="Kulüp yönetimine veya etkinlik düzenleyicisine iletmek istediğiniz bir not varsa buraya yazabilirsiniz..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl text-sm px-4 py-3 focus:bg-white focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all resize-none shadow-inner"></textarea>
            </div>
            
            <div class="flex flex-col gap-3 pt-2">
                <button type="submit" 
                    class="w-full bg-primary hover:bg-primary-dark text-white py-4 rounded-2xl font-bold text-base shadow-xl shadow-primary/20 active:scale-95 transition-all flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined">check_circle</span>
                    Kaydımı Tamamla
                </button>
                <button type="button" onclick="hideRegistrationModal()"
                    class="w-full py-3 text-slate-500 font-bold text-sm hover:bg-slate-50 rounded-2xl transition-all">
                    Vazgeç
                </button>
            </div>
        </form>
    </div>
</div>

@section('scripts_extra')

