@extends('layouts.admin')
@section('title', 'Duyuru Yönetimi')
@section('page_title', 'Duyuru Yönetimi')
@section('data-page', 'announcements')

@section('content')

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<style>
.dataTables_wrapper .dataTables_paginate .paginate_button { padding: 0.25em 0.5em; border-radius: 6px; border:1px solid transparent; }
.dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #f8fafc; border-color: #e2e8f0; }
.dataTables_length, .dataTables_info { font-size: 0.875rem; color: #64748b; padding: 10px 24px; }
.dataTables_filter { display: none; /* Kendi arama kutumuzu kullanacağız */ }
.dataTables_paginate { padding: 10px 24px; font-size: 0.875rem; }
table.dataTable thead th, table.dataTable thead td { border-bottom: 1px solid #e2e8f0; }
table.dataTable.no-footer { border-bottom: 1px solid #e2e8f0; }
</style>
@endpush

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
            <input type="text" id="duyuru-ara" placeholder="Duyuru ara (DataTable)..." class="bg-white border border-slate-200 rounded-xl text-sm pl-10 pr-4 py-2.5 w-64 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
        </div>
    </div>
    <button onclick="showDuyuruModal()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add</span>Yeni Duyuru
    </button>
</div>

<div class="admin-card p-0 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="admin-table w-full" id="duyurular-table">
            <thead>
                <tr>
                    <th class="w-12 text-center">#</th>
                    <th>Başlık</th>
                    <th>Tarih</th>
                    <th>Durum</th>
                    <th class="text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables will fill this -->
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
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#duyurular-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.duyurular') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center'},
            {data: 'title', name: 'title'},
            {data: 'published_at', name: 'published_at'},
            {data: 'is_published', name: 'is_published'},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-right'},
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json"
        }
    });

    $('#duyuru-ara').keyup(function(){
        $('#duyurular-table').DataTable().search($(this).val()).draw();
    });
});

function showDuyuruModal() {
    document.getElementById('duyuru-modal-title').textContent = 'Yeni Duyuru';
    document.getElementById('duyuru-baslik').value = '';
    document.getElementById('duyuru-modal').classList.remove('hidden');
}
function showDuyuruDuzenle(id) {
    document.getElementById('duyuru-modal-title').textContent = 'Duyuruyu Düzenle';
    document.getElementById('duyuru-modal').classList.remove('hidden');
}
function hideDuyuruModal() {
    document.getElementById('duyuru-modal').classList.add('hidden');
}
function showDuyuruDetay(id) {
    showToast('Duyuru detayları', 'info');
}
function showDeleteModal(id, baslik) {
    document.getElementById('delete-item-name').textContent = baslik;
    document.getElementById('delete-modal').classList.remove('hidden');
}
function hideDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}
</script>
@endpush
