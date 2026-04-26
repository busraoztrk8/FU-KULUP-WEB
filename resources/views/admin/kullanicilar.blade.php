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
<div class="flex flex-col md:flex-row md:items-center justify-end gap-3 mb-6">
    <div class="flex items-center gap-3">
        <select id="role-filter" class="bg-white border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:ring-2 focus:ring-primary/20 shadow-sm transition-all">
            <option value="">Tüm Roller</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->label }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex items-center gap-3">
        <button id="export-btn" onclick="exportUsers()" class="bg-slate-100 hover:bg-slate-200 text-slate-600 px-4 py-2.5 rounded-xl font-medium text-sm flex items-center gap-2 transition-all active:scale-95">
            <span class="material-symbols-outlined text-[18px]">download</span>Dışa Aktar (CSV)
        </button>
        <button onclick="showKullaniciEkle()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm shadow-primary/10 active:scale-95">
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
                {{-- Bilgi amaçlı göster, değiştirilemez --}}
                <div id="edit-kullanici-info-box" class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Hesap Bilgileri</p>
                    <div class="flex items-center gap-2 text-sm text-slate-700">
                        <span class="material-symbols-outlined text-[16px] text-slate-400">mail</span>
                        <span id="edit-kullanici-email-display" class="font-medium"></span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        <span class="material-symbols-outlined text-[16px] text-slate-400">lock</span>
                        <span class="italic text-xs">Şifre gizli — değiştirilemez</span>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Ad Soyad <span class="text-red-500">*</span></label>
                        <input id="edit-kullanici-adi" name="name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Rol <span class="text-red-500">*</span></label>
                        <select id="edit-kullanici-rol" name="role_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            @foreach($roles as $role)
                                @php $isDisabled = ($role->name === 'admin' && $adminExists); @endphp
                                <option value="{{ $role->id }}" {{ $isDisabled ? 'disabled' : '' }}>
                                    {{ $role->label }} {{ $isDisabled ? '(Devre Dışı - Sistemde Admin Mevcut)' : '' }}
                                </option>
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

{{-- Yeni Kullanıcı Ekle Modal --}}
<div id="kullanici-ekle-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideKullaniciEkle()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-lg font-bold font-headline text-slate-800">Yeni Kullanıcı Oluştur</h3>
            <button onclick="hideKullaniciEkle()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form action="{{ route('admin.kullanicilar.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Ad Soyad <span class="text-red-500">*</span></label>
                        <input name="name" type="text" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">E-Posta <span class="text-red-500">*</span></label>
                        <input name="email" type="email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Öğrenci Numarası</label>
                    <input name="student_number" type="text" maxlength="20" oninput="this.value=this.value.replace(/[^0-9]/g,'')" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" placeholder="Sadece rakam"/>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Rol <span class="text-red-500">*</span></label>
                        <select name="role_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            @foreach($roles as $role)
                                @php
                                    $isDisabled = ($role->name === 'admin' && $adminExists);
                                @endphp
                                <option value="{{ $role->id }}" 
                                    {{ $isDisabled ? 'disabled' : '' }}>
                                    {{ $role->label }} {{ $isDisabled ? '(Devre Dışı)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp</label>
                        <select name="club_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="">Yok</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Şifre <span class="text-red-500">*</span></label>
                    <input name="password" type="password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideKullaniciEkle()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">add</span>Kaydet
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
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-slate-600 font-medium'},
            {data: 'user_info', name: 'name'},
            {data: 'role_name', name: 'role.name'},
            {data: 'club_name', name: 'club.name', orderable: false},
            {data: 'date', name: 'created_at'},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        language: {
            emptyTable: "Tabloda veri bulunmuyor",
            info: "_TOTAL_ kayıttan _START_ - _END_ arasındaki kayıtlar gösteriliyor",
            infoEmpty: "Kayıt yok",
            infoFiltered: "(_MAX_ kayıt içerisinden bulunan)",
            lengthMenu: "Sayfada _MENU_ kayıt göster",
            loadingRecords: "Yükleniyor...",
            processing: "İşleniyor...",
            search: "Ara:",
            zeroRecords: "Eşleşen kayıt bulunamadı",
            paginate: {
                first: "İlk",
                last: "Son",
                next: "Sonraki",
                previous: "Önceki"
            }
        },
        dom: '<"grid"l f>rt<"grid"i p>',
    });

    // İstatistik animasyonu
    $('[data-count]').each(function () {
        $(this).prop('Counter', 0).animate({
            Counter: $(this).data('count')
        }, {
            duration: 1500,
            easing: 'swing',
            step: function (now) {
                $(this).text(Math.ceil(now));
            }
        });
    });

    $('#role-filter').change(function(){
        table.draw();
    });
});

function showKullaniciDuzenle(id, adi, email, rolId, kulupId) {
    document.getElementById('edit-kullanici-adi').value = adi;
    document.getElementById('edit-kullanici-email-display').textContent = email;
    
    // Admin seçeneğini kontrol et (Eğer düzenlediğimiz kişi adminse seçeneği aktif etmeliyiz)
    const rolSelect = document.getElementById('edit-kullanici-rol');
    const adminOption = Array.from(rolSelect.options).find(opt => opt.text.includes('Admin') || opt.text.includes('Yönetici'));
    
    if (rolId) {
        rolSelect.value = rolId;
        if (adminOption) {
            if (rolId == adminOption.value) adminOption.disabled = false;
        }
    }
    
    if (kulupId) document.getElementById('edit-kullanici-kulup').value = kulupId;
    else document.getElementById('edit-kullanici-kulup').value = '';
    
    document.getElementById('kullanici-duzenle-form').action = '{{ url("admin/kullanicilar") }}/' + id;
    document.getElementById('kullanici-duzenle-modal').classList.remove('hidden');
}
function hideKullaniciDuzenle() {
    document.getElementById('kullanici-duzenle-modal').classList.add('hidden');
}

function showKullaniciEkle() {
    document.getElementById('kullanici-ekle-modal').classList.remove('hidden');
}

function exportUsers() {
    const roleId = document.getElementById('role-filter')?.value || '';
    const url = '{{ route("admin.kullanicilar.export") }}' + (roleId ? '?role_id=' + roleId : '');
    window.location.href = url;
}
function hideKullaniciEkle() {
    document.getElementById('kullanici-ekle-modal').classList.add('hidden');
}

function showDeleteModal(id, baslik) {
    document.getElementById('delete-item-name').textContent = baslik;
    document.getElementById('delete-form').action = '{{ url("admin/kullanicilar") }}/' + id;
    document.getElementById('delete-modal').classList.remove('hidden');
}

function hideDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}
</script>
@endpush
