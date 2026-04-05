@extends('layouts.admin')
@section('title', 'Haber Yönetimi')
@section('page_title', 'Haber Yönetimi')
@section('data-page', 'news')

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

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ session('success') }}
</div>
@endif

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
            <input type="text" id="haber-ara" placeholder="Haber ara (DataTable)..." class="bg-white border border-slate-200 rounded-xl text-sm pl-10 pr-4 py-2.5 w-64 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
        </div>
        <select class="bg-white border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:ring-2 focus:ring-primary/20 shadow-sm">
            <option>Tüm Durumlar</option>
            <option>Yayında</option>
            <option>Taslak</option>
        </select>
    </div>
    <button onclick="showHaberModal()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add</span>Yeni Haber
    </button>
</div>

<div class="admin-card p-0 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="admin-table w-full" id="haberler-table">
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
        <p class="text-sm text-slate-500">Toplam <span class="font-semibold text-slate-700">2</span> haber</p>
    </div>
</div>

{{-- Yeni/Düzenle Modal --}}
<div id="haber-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideHaberModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
            <h3 id="haber-modal-title" class="text-lg font-bold font-headline text-slate-800">Yeni Haber Ekle</h3>
            <button onclick="hideHaberModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1 space-y-5">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Başlık <span class="text-red-500">*</span></label>
                <input id="haber-baslik" type="text" placeholder="Haber başlığı..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                    <select class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option>Akademik</option><option>Etkinlik</option><option>Duyuru</option><option>Genel</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Durum</label>
                    <select class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="published">Yayınla</option>
                        <option value="draft">Taslak</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Görsel</label>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors">
                    <span class="material-symbols-outlined text-primary text-[32px] mb-2">cloud_upload</span>
                    <p class="text-sm font-semibold text-slate-700">Görsel yüklemek için tıklayın</p>
                    <p class="text-xs text-slate-400 mt-1">PNG, JPG (Maks. 5MB)</p>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">İçerik <span class="text-red-500">*</span></label>
                <textarea rows="5" placeholder="Haber içeriği..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 shrink-0 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
            <button onclick="hideHaberModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
            <button onclick="showToast('Haber kaydedildi!', 'success'); hideHaberModal()" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
                <span class="material-symbols-outlined text-[18px]">done</span>Kaydet
            </button>
        </div>
    </div>
</div>

{{-- Detay Modal --}}
<div id="haber-detay-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideHaberDetay()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-lg font-bold font-headline text-slate-800">Haber Detayı</h3>
            <button onclick="hideHaberDetay()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-6">
            <div class="w-full h-40 bg-slate-100 rounded-xl mb-4 flex items-center justify-center">
                <span class="material-symbols-outlined text-slate-300 text-[48px]">image</span>
            </div>
            <h4 id="detay-baslik" class="text-xl font-bold text-slate-800 mb-2"></h4>
            <div class="flex items-center gap-3 mb-4">
                <span class="badge badge-primary">Akademik</span>
                <span class="badge badge-success">Yayında</span>
                <span class="text-xs text-slate-400">21.02.2026</span>
            </div>
            <p class="text-sm text-slate-600 leading-relaxed">Bu haber içeriği örnek olarak gösterilmektedir. Gerçek içerik veritabanından gelecektir.</p>
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
    $('#haberler-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.haberler') }}",
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

    // Arama kutusunu bağla
    $('#haber-ara').keyup(function(){
        $('#haberler-table').DataTable().search($(this).val()).draw();
    });
});

function showHaberModal() {
    document.getElementById('haber-modal-title').textContent = 'Yeni Haber Ekle';
    document.getElementById('haber-baslik').value = '';
    document.getElementById('haber-modal').classList.remove('hidden');
}
function showHaberDuzenle(id) {
    document.getElementById('haber-modal-title').textContent = 'Haberi Düzenle';
    document.getElementById('haber-modal').classList.remove('hidden');
}
function hideHaberModal() {
    document.getElementById('haber-modal').classList.add('hidden');
}
function showHaberDetay(id) {
    document.getElementById('haber-detay-modal').classList.remove('hidden');
}
function hideHaberDetay() {
    document.getElementById('haber-detay-modal').classList.add('hidden');
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
