@extends('layouts.admin')

@section('title', 'Kullanıcı Yönetimi')
@section('page_title', 'Kullanıcı Yönetimi')
@section('data-page', 'users')

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet">
@endpush

@section('content')

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-4 gap-6 mb-8">
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-primary">groups</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['total'] }}">0</p>
            <p class="text-sm text-slate-500 font-medium">Toplam</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-green-600">verified</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['active'] }}">0</p>
            <p class="text-sm text-slate-500 font-medium">Aktif</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-tertiary/10 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-tertiary">admin_panel_settings</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['admins'] }}">0</p>
            <p class="text-sm text-slate-500 font-medium">Admin</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-amber-600">person_off</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['passive'] }}">0</p>
            <p class="text-sm text-slate-500 font-medium">Pasif</p>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <select id="role-filter" class="bg-white border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:ring-2 focus:ring-primary/20 shadow-sm transition-all">
            <option value="">Tüm Roller</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->label }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex items-center gap-3">
        <button onclick="showToast('Rapor oluşturuluyor...', 'info')" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl font-medium text-sm flex items-center gap-2 transition-all active:scale-95">
            <span class="material-symbols-outlined text-[18px]">download</span>Dışa Aktar
        </button>
        <button onclick="showToast('Yeni kullanıcı formu hazırlanıyor...', 'success')" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm shadow-primary/10 active:scale-95">
            <span class="material-symbols-outlined text-[18px]">person_add</span>Yeni Kullanıcı
        </button>
    </div>
</div>

<!-- Table -->
<div class="admin-card p-0 overflow-hidden shadow-sm">
    <div class="overflow-x-auto w-full pt-4">
        <table class="w-full" id="kullanicilar-table">
            <thead>
                <tr>
                    <th class="w-12 text-center text-slate-500 font-bold uppercase text-xs tracking-wider">ID</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">KULLANICI</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">ROL</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">KULÜP</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">KAYIT TARİHİ</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">DURUM</th>
                    <th class="text-slate-500 font-bold uppercase text-xs tracking-wider">İŞLEMLER</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables will fill this -->
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="modal-overlay absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideDeleteModal()"></div>
    <div class="relative bg-white rounded-2xl p-8 max-w-sm w-full mx-4 shadow-2xl">
        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-red-500 text-[28px]">warning</span>
        </div>
        <h3 class="text-lg font-bold font-headline text-center text-slate-800 mb-2">Kullanıcıyı engellemek istediğinize emin misiniz?</h3>
        <p class="text-sm text-slate-500 text-center mb-6">"<span id="delete-item-name"></span>" hesabı devre dışı bırakılacaktır.</p>
        <form id="delete-form" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="hideDeleteModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
                <button type="submit" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-all active:scale-95">Sil</button>
            </div>
        </form>
    </div>
</div>

{{-- Kullanıcı Düzenle Modal --}}
<div id="kullanici-duzenle-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideKullaniciDuzenle()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-lg font-bold font-headline text-slate-800">Kullanıcıyı Düzenle</h3>
            <button onclick="hideKullaniciDuzenle()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="kullanici-duzenle-form" method="POST">
            @csrf @method('PUT')
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Ad Soyad <span class="text-red-500">*</span></label>
                        <input id="edit-kullanici-adi" name="name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">E-Posta <span class="text-red-500">*</span></label>
                        <input id="edit-kullanici-email" name="email" type="email" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Rol <span class="text-red-500">*</span></label>
                        <select id="edit-kullanici-rol" name="role_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp</label>
                        <select id="edit-kullanici-kulup" name="club_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="">Yok</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Yeni Şifre <span class="text-slate-400 font-normal">(boş bırakılırsa değişmez)</span></label>
                    <input name="password" type="password" placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideKullaniciDuzenle()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">done</span>Güncelle
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    let table = $('#kullanicilar-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.kullanicilar') }}",
            data: function (d) {
                d.role_id = $('#role-filter').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-slate-600 font-medium w-12'},
            {data: 'user_info', name: 'name'},
            {data: 'role_name', name: 'role.name'},
            {data: 'club_name', name: 'club.name', orderable: false},
            {data: 'date', name: 'created_at'},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json",
            paginate: { previous: "Önceki", next: "Sonraki" }
        },
        dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-4"l f>rt<"flex flex-col md:flex-row justify-between items-center gap-4 mt-4"i p>',
    });

    $('#role-filter').change(function(){
        table.draw();
    });
});

function showKullaniciDuzenle(id, adi, email, rolId, kulupId) {
    document.getElementById('edit-kullanici-adi').value = adi;
    document.getElementById('edit-kullanici-email').value = email;
    if (rolId) document.getElementById('edit-kullanici-rol').value = rolId;
    if (kulupId) document.getElementById('edit-kullanici-kulup').value = kulupId;
    document.getElementById('kullanici-duzenle-form').action = '/admin/kullanicilar/' + id;
    document.getElementById('kullanici-duzenle-modal').classList.remove('hidden');
}
function hideKullaniciDuzenle() {
    document.getElementById('kullanici-duzenle-modal').classList.add('hidden');
}

function showDeleteModal(id, baslik) {
    document.getElementById('delete-item-name').textContent = baslik;
    document.getElementById('delete-form').action = "/admin/kullanicilar/" + id;
    document.getElementById('delete-modal').classList.remove('hidden');
}

function hideDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}
</script>
@endpush
