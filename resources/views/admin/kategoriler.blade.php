@extends('layouts.admin')

@section('title', 'Kategori Yönetimi')
@section('page_title', 'Kategori Yönetimi')
@section('data-page', 'categories')

@section('content')

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet">
<style>
    #categories-table { border-collapse: collapse; width: 100% !important; }
    #categories-table thead th { background: #f8fafc; border-bottom: 2px solid #e2e8f0; padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; white-space: nowrap; }
    #categories-table tbody td { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 14px; color: #334155; }
    #categories-table tbody tr:hover td { background: #f8fafc; }
    #categories-table_wrapper .dataTables_info { font-size: 13px; color: #64748b; }
    #categories-table_wrapper .dataTables_paginate { display: flex; gap: 4px; }
    #categories-table_wrapper .dataTables_paginate .paginate_button { padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: 1px solid #e2e8f0; color: #475569 !important; background: #fff; }
    #categories-table_wrapper .dataTables_paginate .paginate_button.current { background: var(--primary, #5d1021) !important; color: #fff !important; border-color: var(--primary, #5d1021); }
</style>
@endpush

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
            <input type="text" id="custom-search" placeholder="Kategori ara..." class="bg-white border border-slate-200 rounded-xl text-sm pl-10 pr-4 py-2.5 w-64 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm outline-none"/>
        </div>
    </div>
    <button onclick="showCategoryModal()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm shadow-primary/10 active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add</span>Yeni Kategori
    </button>
</div>

<div class="admin-card p-0 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table id="categories-table" class="admin-table w-full">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>Kategori Adı</th>
                    <th>Renk / İkon</th>
                    <th>Bağlı Kulüp Sayısı</th>
                    <th>Durum</th>
                    <th class="text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

{{-- Kategori Ekle/Düzenle Modal --}}
<div id="category-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideCategoryModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 id="category-modal-title" class="text-lg font-bold font-headline text-slate-800">Yeni Kategori Ekle</h3>
            <button onclick="hideCategoryModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="category-form" method="POST">
            @csrf
            <input type="hidden" name="_method" id="category-method" value="POST">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kategori Adı <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="category-name" required placeholder="Örn: Teknoloji"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">İkon (Material Symbol)</label>
                        <input type="text" name="icon" id="category-icon" placeholder="Örn: hub"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Renk</label>
                        <div class="flex gap-2">
                            <input type="color" name="color" id="category-color" value="#3b82f6"
                                class="h-11 w-11 bg-slate-50 border border-slate-200 rounded-xl p-1 cursor-pointer"/>
                            <input type="text" id="category-color-text" placeholder="#000000"
                                class="flex-1 bg-slate-50 border border-slate-200 rounded-xl text-sm px-3 py-3 focus:bg-white transition-all shadow-sm"
                                oninput="document.getElementById('category-color').value = this.value"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideCategoryModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md shadow-primary/20 flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">done</span>Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    let table = $('#categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.kategoriler') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name_info', name: 'name'},
            {data: 'icon_color', name: 'icon'},
            {data: 'clubs_count', name: 'clubs_count', searchable: false},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-right'}
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/tr.json"
        },
        dom: 'rt<"flex flex-col md:flex-row items-center justify-between gap-4 px-4 py-4 border-t border-slate-100"ip>',
    });

    $('#custom-search').on('keyup', function() {
        table.search(this.value).draw();
    });
});

function showCategoryModal() {
    document.getElementById('category-modal-title').textContent = 'Yeni Kategori Ekle';
    document.getElementById('category-form').action = "{{ route('admin.kategoriler.store') }}";
    document.getElementById('category-method').value = "POST";
    document.getElementById('category-name').value = "";
    document.getElementById('category-icon').value = "";
    document.getElementById('category-color').value = "#3b82f6";
    document.getElementById('category-color-text').value = "#3b82f6";
    document.getElementById('category-modal').classList.remove('hidden');
}

function showEditCategoryModal(id, name, icon, color) {
    document.getElementById('category-modal-title').textContent = 'Kategoriyi Düzenle';
    document.getElementById('category-form').action = "{{ url('admin/kategoriler') }}/" + id;
    document.getElementById('category-method').value = "PUT";
    document.getElementById('category-name').value = name;
    document.getElementById('category-icon').value = icon || "";
    document.getElementById('category-color').value = color || "#3b82f6";
    document.getElementById('category-color-text').value = color || "#3b82f6";
    document.getElementById('category-modal').classList.remove('hidden');
}

function hideCategoryModal() {
    document.getElementById('category-modal').classList.add('hidden');
}

// Renk seçici senkronizasyonu
document.getElementById('category-color').addEventListener('input', function() {
    document.getElementById('category-color-text').value = this.value;
});
</script>
@endpush

@endsection
