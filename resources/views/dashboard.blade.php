@extends('layouts.app')
@section('title', 'Dashboard - Fırat Üniversitesi')
@section('data-page', 'dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-16">
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-10">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-[28px]">dashboard</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold font-headline text-slate-900">Hoş geldin, {{ auth()->user()->name }}</h1>
                <p class="text-slate-500 text-sm mt-1">Fırat Üniversitesi Kulüp & Etkinlik Platformu</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('etkinlikler') }}" class="group p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary/30 hover:bg-primary/5 transition-all">
                <span class="material-symbols-outlined text-primary text-[32px] mb-3 block">event</span>
                <h3 class="font-bold text-slate-800 group-hover:text-primary transition-colors">Etkinlikler</h3>
                <p class="text-sm text-slate-500 mt-1">Yaklaşan etkinlikleri keşfet</p>
            </a>
            <a href="{{ route('kulupler') }}" class="group p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary/30 hover:bg-primary/5 transition-all">
                <span class="material-symbols-outlined text-primary text-[32px] mb-3 block">groups</span>
                <h3 class="font-bold text-slate-800 group-hover:text-primary transition-colors">Kulüpler</h3>
                <p class="text-sm text-slate-500 mt-1">Kulüplere göz at ve katıl</p>
            </a>
            <a href="{{ route('profile.edit') }}" class="group p-6 rounded-2xl bg-slate-50 border border-slate-100 hover:border-primary/30 hover:bg-primary/5 transition-all">
                <span class="material-symbols-outlined text-primary text-[32px] mb-3 block">manage_accounts</span>
                <h3 class="font-bold text-slate-800 group-hover:text-primary transition-colors">Profilim</h3>
                <p class="text-sm text-slate-500 mt-1">Hesap bilgilerini düzenle</p>
            </a>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-red-600 transition-colors">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                Çıkış Yap
            </button>
        </form>
    </div>
</div>
@endsection
