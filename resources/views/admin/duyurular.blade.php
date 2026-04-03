@extends('layouts.admin')
@section('title', 'Duyuru Yönetimi')
@section('page_title', 'Duyuru Yönetimi')
@section('data-page', 'announcements')

@section('content')

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
            <input type="text" placeholder="Duyuru ara..." class="bg-white border border-slate-200 rounded-xl text-sm pl-10 pr-4 py-2.5 w-64 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
        </div>
    </div>
    <button onclick="showDuyuruModal()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add</span>Yeni Duyuru
    </button>
</div>

<div class="admin-card p-0 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th><input type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary/30"/></th>
                    <th>Başlık</th>
                    <th>Hedef Kitle</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th class="text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox" class="row-checkbox rounded border-slate-300 text-primary focus:ring-primary/30"/></td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-amber-500 text-[18px]">campaign</span>
                            </div>
                            <span class="font-semibold text-slate-800">Öğrenci Seçim Sonuçları Açıklandı</span>
                        </div>
                    </td>
                    <td class="text-slate-500">Tüm Öğrenciler</td>
                    <td class="text-slate-500">21.02.2026</td>
                    <td><span class="badge badge-success shadow-sm">Aktif</span></td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1">
                            <button onclick="showDuyuruDetay('Öğrenci Seçim Sonuçları')" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Görüntüle"><span class="material-symbols-outlined text-[18px]">visibility</span></button>
                            <button onclick="showDuyuruDuzenle('Öğrenci Seçim Sonuçları Açıklandı')" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Düzenle"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                            <button onclick="showDeleteModal('Öğrenci Seçim Sonuçları')" class="action-btn action-btn-danger text-slate-400" title="Sil"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="row-checkbox rounded border-slate-300 text-primary focus:ring-primary/30"/></td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-amber-500 text-[18px]">campaign</span>
                            </div>
                            <span class="font-semibold text-slate-800">Bahar Dönemi Kayıt Tarihleri</span>
                        </div>
                    </td>
                    <td class="text-slate-500">Tüm Öğrenciler</td>
                    <td class="text-slate-500">15.01.2026</td>
                    <td><span class="badge badge-warning shadow-sm">Taslak</span></td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1">
                            <button onclick="showDuyuruDetay('Bahar Dönemi Kayıt')" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Görüntüle"><span class="material-symbols-outlined text-[18px]">visibility</span></button>
                            <button onclick="showDuyuruDuzenle('Bahar Dönemi Kayıt Tarihleri')" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Düzenle"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                            <button onclick="showDeleteModal('Bahar Dönemi Kayıt')" class="action-btn action-btn-danger text-slate-400" title="Sil"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/50">
        <p class="text-sm text-slate-500">Toplam <span class="font-semibold text-slate-700">2</span> duyuru</p>
    </div>
</div>

{{-- Ekle/Düzenle Modal --}}
<div id="duyuru-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideDuyuruModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 id="duyuru-modal-title" class="text-lg font-bold font-headline text-slate-800">Yeni Duyuru</h3>
            <button onclick="hideDuyuruModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Başlık <span class="text-red-500">*</span></label>
                <input id="duyuru-baslik" type="text" placeholder="Duyuru başlığı..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Hedef Kitle</label>
                    <select class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option>Tüm Öğrenciler</option>
                        <option>Kulüp Üyeleri</option>
                        <option>Akademisyenler</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Durum</label>
                    <select class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="active">Aktif</option>
                        <option value="draft">Taslak</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">İçerik <span class="text-red-500">*</span></label>
                <textarea rows="4" placeholder="Duyuru içeriği..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
            <button onclick="hideDuyuruModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
            <button onclick="showToast('Duyuru kaydedildi!', 'success'); hideDuyuruModal()" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
                <span class="material-symbols-outlined text-[18px]">done</span>Kaydet
            </button>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div id="delete-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideDeleteModal()"></div>
    <div class="relative bg-white rounded-2xl p-8 max-w-sm w-full mx-4 shadow-2xl">
        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-red-500 text-[28px]">warning</span>
        </div>
        <h3 class="text-lg font-bold font-headline text-center text-slate-800 mb-2">Silmek istediğinize emin misiniz?</h3>
        <p class="text-sm text-slate-500 text-center mb-6">"<span id="delete-item-name"></span>" kalıcı olarak silinecektir.</p>
        <div class="flex gap-3">
            <button onclick="hideDeleteModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
            <button onclick="showToast('Silindi.', 'error'); hideDeleteModal()" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-all active:scale-95">Sil</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showDuyuruModal() {
    document.getElementById('duyuru-modal-title').textContent = 'Yeni Duyuru';
    document.getElementById('duyuru-baslik').value = '';
    document.getElementById('duyuru-modal').classList.remove('hidden');
}
function showDuyuruDuzenle(baslik) {
    document.getElementById('duyuru-modal-title').textContent = 'Duyuruyu Düzenle';
    document.getElementById('duyuru-baslik').value = baslik;
    document.getElementById('duyuru-modal').classList.remove('hidden');
}
function hideDuyuruModal() {
    document.getElementById('duyuru-modal').classList.add('hidden');
}
function showDuyuruDetay(baslik) {
    showToast(baslik + ' detayları görüntüleniyor', 'info');
}
</script>
@endpush
