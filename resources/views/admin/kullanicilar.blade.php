@extends('layouts.admin')

@section('title', 'Kullanıcı Yönetimi')
@section('page_title', 'Kullanıcı Yönetimi')
@section('data-page', 'users')

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
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
            <input type="text" placeholder="İsim veya e-posta ara..." class="bg-white border border-slate-200 rounded-xl text-sm pl-10 pr-4 py-2.5 w-72 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
        </div>
        <select class="bg-white border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:ring-2 focus:ring-primary/20 shadow-sm">
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
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th><input id="select-all" type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary/30"/></th>
                    <th>Kullanıcı</th>
                    <th>E-Posta</th>
                    <th>Rol</th>
                    <th>Kulüp</th>
                    <th>Kayıt Tarihi</th>
                    <th>Durum</th>
                    <th class="text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td><input type="checkbox" class="row-checkbox rounded border-slate-300 text-primary focus:ring-primary/30"/></td>
                    <td>
                        <div class="flex items-center gap-3">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-9 h-9 rounded-full object-cover shrink-0 shadow-sm" alt=""/>
                            @else
                                <div class="w-9 h-9 bg-primary rounded-full flex items-center justify-center shrink-0 text-white text-xs font-bold shadow-sm">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                            <span class="font-semibold text-slate-800">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="text-slate-500">{{ $user->email }}</td>
                    <td>
                        @if($user->role)
                            <span class="badge badge-primary">{{ $user->role->label }}</span>
                        @else
                            <span class="text-slate-400 text-sm">—</span>
                        @endif
                    </td>
                    <td class="text-slate-600">{{ $user->club?->name ?? '—' }}</td>
                    <td class="text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        @if($user->email_verified_at)
                            <span class="badge badge-success shadow-sm">Aktif</span>
                        @else
                            <span class="badge badge-warning shadow-sm">Doğrulanmadı</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1">
                            <button onclick="showKullaniciDuzenle({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', {{ $user->role_id ?? 'null' }}, {{ $user->club_id ?? 'null' }})" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Düzenle"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                            <form action="{{ route('admin.kullanicilar.destroy', $user) }}" method="POST" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn action-btn-danger text-slate-400" title="Sil"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-12 text-slate-400">
                        <span class="material-symbols-outlined text-[48px] block mb-2">person_off</span>
                        Henüz kullanıcı bulunmuyor.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="flex flex-col sm:flex-row items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/50 gap-4">
        <p class="text-sm text-slate-500">Toplam <span class="font-semibold text-slate-700">{{ $users->total() }}</span> kullanıcı</p>
        <div>{{ $users->links() }}</div>
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
        <div class="flex gap-3">
            <button onclick="hideDeleteModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
            <button onclick="showToast('Kullanıcı engellendi.', 'error'); hideDeleteModal()" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-all active:scale-95">Engelle</button>
        </div>
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
<script>
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
</script>
@endpush
