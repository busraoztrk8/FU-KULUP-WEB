@extends('layouts.admin')

@section('title', 'Profil Ayarları')
@section('page_title', 'Profil Ayarları')

@section('content')
<div class="max-w-4xl space-y-6">
    @if(auth()->user()->hasRole('student'))
    <!-- Student Information View (Read-Only for CAS Integration) -->
    <div class="p-6 sm:p-10 bg-white border border-slate-100 shadow-sm sm:rounded-3xl">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-3xl">account_circle</span>
            </div>
            <div>
                <h2 class="text-2xl font-headline font-bold text-slate-800">Profil Bilgileri</h2>
                <p class="text-sm text-slate-500">Hesap bilgileriniz üniversite sisteminden otomatik olarak alınmaktadır.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Ad Soyad</label>
                <p class="text-lg font-medium text-slate-700">{{ auth()->user()->name }}</p>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">E-posta Adresi</label>
                <p class="text-lg font-medium text-slate-700">{{ auth()->user()->email }}</p>
            </div>
             <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kayıtlı Kulüp</label>
                <p class="text-lg font-medium text-slate-700">{{ auth()->user()->club ? auth()->user()->club->name : 'Herhangi bir kulübe üye değilsiniz.' }}</p>
            </div>
            <div class="space-y-1">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Hesap Türü</label>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full uppercase tracking-tighter">
                        {{ auth()->user()->role ? auth()->user()->role->label : 'Öğrenci' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-10 p-4 bg-slate-50 rounded-xl border border-dotted border-slate-200">
            <p class="text-xs text-slate-500 leading-relaxed flex items-start gap-2">
                <span class="material-symbols-outlined text-sm mt-0.5">info</span>
                Bilgilerinizde eksiklik veya hata olduğunu düşünüyorsanız lütfen üniversite öğrenci işleri birimi ile iletişime geçiniz. Bilgileriniz CAS merkezi kimlik doğrulama sistemi üzerinden güncellenmektedir.
            </p>
        </div>
    </div>
    @else
    <!-- Admin/Editor Form View -->
    <div class="p-4 sm:p-8 bg-white border border-slate-100 shadow-sm sm:rounded-2xl">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="p-4 sm:p-8 bg-white border border-slate-100 shadow-sm sm:rounded-2xl">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="p-4 sm:p-8 bg-white border border-slate-100 shadow-sm sm:rounded-2xl">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
    @endif
</div>
@endsection
