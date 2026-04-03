@extends('layouts.admin')

@section('title', 'Raporlar ve Analizler')
@section('page_title', 'Raporlar ve Analizler')
@section('data-page', 'reports')

@section('content')

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-bold font-headline text-slate-800">Sistem Analizleri</h2>
        <p class="text-slate-500 text-sm mt-1">Platformun genel kullanım verilerini ve istatistiklerini buradan takip edebilirsiniz.</p>
    </div>
    <div class="flex items-center gap-3">
        <select class="bg-white border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:ring-2 focus:ring-primary/20 shadow-sm transition-all">
            <option>Son 30 Gün</option>
            <option>Bu Ay</option>
            <option>Geçen Ay</option>
            <option>Tüm Zamanlar</option>
        </select>
        <button onclick="showToast('Rapor PDF olarak hazırlanıyor...', 'info')" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-md active:scale-95">
            <span class="material-symbols-outlined text-[18px]">download</span>PDF Olarak İndir
        </button>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="admin-card shadow-sm">
        <h3 class="font-headline font-bold text-slate-800 mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">bar_chart</span>
            Aylık Aktif Kullanıcılar
        </h3>
        <div class="h-64 flex items-end gap-2" style="background: repeating-linear-gradient(0deg, transparent, transparent 19px, #f1f5f9 20px);">
            <div class="w-full bg-primary/20 hover:bg-primary/40 rounded-t-lg transition-all" style="height: 30%;"></div>
            <div class="w-full bg-primary/30 hover:bg-primary/50 rounded-t-lg transition-all" style="height: 45%;"></div>
            <div class="w-full bg-primary/40 hover:bg-primary/60 rounded-t-lg transition-all" style="height: 35%;"></div>
            <div class="w-full bg-primary/60 hover:bg-primary/80 rounded-t-lg transition-all" style="height: 70%;"></div>
            <div class="w-full bg-primary/80 hover:bg-primary/90 rounded-t-lg transition-all" style="height: 60%;"></div>
            <div class="w-full bg-primary rounded-t-lg transition-all shadow-sm" style="height: 90%;"></div>
        </div>
        <div class="flex justify-between items-center mt-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
            <span>Oca</span><span>Şub</span><span>Mar</span><span>Nis</span><span>May</span><span>Haz</span>
        </div>
    </div>

    <div class="admin-card shadow-sm">
        <h3 class="font-headline font-bold text-slate-800 mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">pie_chart</span>
            Kategorilere Göre Kulüpler
        </h3>
        <div class="flex h-64 items-center justify-center">
            <div class="relative w-48 h-48 rounded-full border-[24px] border-primary shadow-sm" style="border-right-color: #f59e0b; border-bottom-color: #db2777; border-left-color: #3b82f6;">
                <div class="absolute inset-0 flex items-center justify-center flex-col">
                    <span class="text-3xl font-bold font-headline text-slate-800" data-count="52">0</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Kulüp</span>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap justify-center gap-4 mt-6">
            <div class="flex items-center gap-2 px-2 py-1 bg-slate-50 rounded-lg"><span class="w-3 h-3 rounded-full bg-primary shadow-sm"></span><span class="text-[12px] font-bold text-slate-600">Teknoloji</span></div>
            <div class="flex items-center gap-2 px-2 py-1 bg-slate-50 rounded-lg"><span class="w-3 h-3 rounded-full bg-[#f59e0b] shadow-sm"></span><span class="text-[12px] font-bold text-slate-600">Girişimcilik</span></div>
            <div class="flex items-center gap-2 px-2 py-1 bg-slate-50 rounded-lg"><span class="w-3 h-3 rounded-full bg-[#db2777] shadow-sm"></span><span class="text-[12px] font-bold text-slate-600">Sanat</span></div>
            <div class="flex items-center gap-2 px-2 py-1 bg-slate-50 rounded-lg"><span class="w-3 h-3 rounded-full bg-[#3b82f6] shadow-sm"></span><span class="text-[12px] font-bold text-slate-600">Spor</span></div>
        </div>
    </div>
</div>

<!-- Top Events Table -->
<div class="admin-card p-0 overflow-hidden shadow-sm">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
        <h3 class="font-headline font-bold text-slate-800">En Çok Katılım Gösterilen Etkinlikler</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Etkinlik</th>
                    <th>Kategori</th>
                    <th>Katılımcı Sayısı</th>
                    <th>Memnuniyet Oranı</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="font-semibold text-slate-800">Geleceğin Teknolojileri Zirvesi</span></td>
                    <td><span class="badge badge-primary">Akademik</span></td>
                    <td><span class="font-bold text-primary">850</span></td>
                    <td>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-green-600 w-10">98%</span>
                            <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                <div class="w-[98%] h-full bg-green-500 rounded-full shadow-sm"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span class="font-semibold text-slate-800">Kariyer Günleri '23</span></td>
                    <td><span class="badge badge-info">Kariyer</span></td>
                    <td><span class="font-bold text-primary">620</span></td>
                    <td>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-green-600 w-10">95%</span>
                            <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                <div class="w-[95%] h-full bg-green-500 rounded-full shadow-sm"></div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><span class="font-semibold text-slate-800">Bahar Şenliği Konserleri</span></td>
                    <td><span class="badge badge-success">Sosyal</span></td>
                    <td><span class="font-bold text-primary">1,200</span></td>
                    <td>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-green-600 w-10">92%</span>
                            <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                                <div class="w-[92%] h-full bg-green-500 rounded-full shadow-sm"></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection
