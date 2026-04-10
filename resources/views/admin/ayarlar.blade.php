@extends('layouts.admin')

@section('title', 'Sistem Ayarları')
@section('page_title', 'Sistem Ayarları')
@section('data-page', 'settings')

@section('content')

<div class="max-w-4xl">
    <!-- Tabs -->
    <div class="flex border-b border-slate-200 mb-8 space-x-6 overflow-x-auto custom-scrollbar">
        <button class="pb-3 border-b-2 border-primary text-primary font-bold text-sm whitespace-nowrap">Genel Ayarlar</button>
        <button onclick="showToast('Görünüm ayarları yakında...', 'info')" class="pb-3 border-b-2 border-transparent text-slate-500 font-medium text-sm hover:text-slate-800 transition-colors whitespace-nowrap">Görünüm</button>
        <button onclick="showToast('Güvenlik ayarları yakında...', 'info')" class="pb-3 border-b-2 border-transparent text-slate-500 font-medium text-sm hover:text-slate-800 transition-colors whitespace-nowrap">Güvenlik</button>
        <button onclick="showToast('SMTP ayarları yakında...', 'info')" class="pb-3 border-b-2 border-transparent text-slate-500 font-medium text-sm hover:text-slate-800 transition-colors whitespace-nowrap">E-Posta (SMTP)</button>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            <span class="text-sm font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('admin.ayarlar.update') }}" method="POST">
        @csrf
        <div class="space-y-8">
            <!-- Site Bilgileri -->
            <div class="admin-card shadow-sm">
                <h3 class="font-headline font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">info</span>
                    Site Bilgileri
                </h3>
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5 pl-1">Site Adı</label>
                        <input type="text" name="site_name" value="{{ \App\Models\SiteSetting::getVal('site_name', 'Fırat Üniversitesi') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5 pl-1">Açıklama (SEO Meta)</label>
                        <textarea name="site_description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm resize-none">{{ \App\Models\SiteSetting::getVal('site_description', 'Fırat Üniversitesi Akıllı Kulüp ve Etkinlik Platformu') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- İletişim & Sosyal Medya -->
            <div class="admin-card shadow-sm">
                <h3 class="font-headline font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[20px]">share</span>
                    İletişim & Sosyal Medya
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5 pl-1">Destek E-Postası</label>
                        <input type="email" name="contact_email" value="{{ \App\Models\SiteSetting::getVal('contact_email', 'destek@firat.edu.tr') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5 pl-1">Telefon</label>
                        <input type="text" name="contact_phone" value="{{ \App\Models\SiteSetting::getVal('contact_phone', '+90 (424) 237 00 00') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5 pl-1">Instagram (Kullanıcı Adı)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium text-xs">@</span>
                            <input type="text" name="social_instagram" value="{{ \App\Models\SiteSetting::getVal('social_instagram', 'firatuniv') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5 pl-1">Twitter/X (Kullanıcı Adı)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium text-xs">@</span>
                            <input type="text" name="social_twitter" value="{{ \App\Models\SiteSetting::getVal('social_twitter', 'firatuniversite') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5 pl-1">GitHub (Kullanıcı Adı)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium text-xs">@</span>
                            <input type="text" name="social_github" value="{{ \App\Models\SiteSetting::getVal('social_github', '') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5 pl-1">Facebook (Link)</label>
                        <input type="text" name="social_facebook" value="{{ \App\Models\SiteSetting::getVal('social_facebook', '') }}" placeholder="facebook.com/kullaniciadi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4">
                <button type="button" onclick="window.location.reload()" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95 shadow-sm">İptal</button>
                <button type="submit" class="bg-primary hover:bg-primary-dim text-white px-8 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20 active:scale-95">Değişiklikleri Kaydet</button>
            </div>
        </div>
    </form>
</div>

@endsection
