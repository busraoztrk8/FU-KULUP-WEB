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
                    <input type="text" value="Fırat Üniversitesi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5 pl-1">Açıklama (SEO Meta)</label>
                    <textarea rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm resize-none">Fırat Üniversitesi Akıllı Kulüp ve Etkinlik Platformu</textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5 pl-1">Logo URL</label>
                    <div class="flex gap-3">
                        <input type="text" value="/assets/images/logo.png" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                        <button onclick="showToast('Dosya yöneticisi açılıyor...', 'info')" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 rounded-xl transition-colors active:scale-95">
                            <span class="material-symbols-outlined text-[20px]">upload_file</span>
                        </button>
                    </div>
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
                    <input type="email" value="destek@firat.edu.tr" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5 pl-1">Telefon</label>
                    <input type="text" value="+90 (424) 237 00 00" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5 pl-1">Instagram</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium text-xs">@</span>
                        <input type="text" placeholder="firatuniv" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5 pl-1">Twitter (X)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-medium text-xs">@</span>
                        <input type="text" placeholder="firatuniversite" class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4">
            <button onclick="window.location.reload()" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95 shadow-sm">İptal</button>
            <button onclick="showToast('Ayarlar başarıyla kaydedildi!', 'success')" class="bg-primary hover:bg-primary-dim text-white px-8 py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-primary/20 active:scale-95">Değişiklikleri Kaydet</button>
        </div>
    </div>
</div>

@endsection
