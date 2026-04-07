@extends('layouts.app')
@section('title', '403 - Yetkisiz Erişim')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-red-500 text-[40px]">lock</span>
        </div>
        <h1 class="text-6xl font-black font-headline text-slate-800 mb-3">403</h1>
        <h2 class="text-xl font-bold text-slate-700 mb-3">Yetkisiz Erişim</h2>
        <p class="text-slate-500 text-sm mb-8 leading-relaxed">
            Bu sayfaya erişim yetkiniz bulunmamaktadır.<br>
            Farklı bir hesapla giriş yapmayı deneyin.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}"
                class="px-6 py-3 bg-primary text-white rounded-xl font-bold text-sm hover:bg-primary-dark transition-all active:scale-95 shadow-lg shadow-primary/20">
                Ana Sayfaya Dön
            </a>
            <a href="{{ route('login') }}"
                class="px-6 py-3 border border-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all active:scale-95">
                Farklı Hesapla Giriş Yap
            </a>
        </div>
    </div>
</div>
@endsection
