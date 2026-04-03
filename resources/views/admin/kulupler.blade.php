@extends('layouts.admin')

@section('title', 'Kulüp Yönetimi')
@section('page_title', 'Kulüp Yönetimi')
@section('data-page', 'clubs')

@section('content')

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium">
    <div class="flex items-center gap-2 mb-2"><span class="material-symbols-outlined text-[18px]">error</span><strong>Hata oluştu:</strong></div>
    <ul class="list-disc list-inside space-y-1">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-primary">diversity_3</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['total'] }}">0</p>
            <p class="text-sm text-slate-500">Toplam Kulüp</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-green-600">check_circle</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['active'] }}">0</p>
            <p class="text-sm text-slate-500">Aktif Kulüp</p>
        </div>
    </div>
    <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-shadow">
        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
            <span class="material-symbols-outlined text-amber-600">pending</span>
        </div>
        <div>
            <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['inactive'] }}">0</p>
            <p class="text-sm text-slate-500">Pasif Kulüp</p>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
            <input type="text" placeholder="Kulüp ara..." class="bg-white border border-slate-200 rounded-xl text-sm pl-10 pr-4 py-2.5 w-64 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
        </div>
        <select class="bg-white border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:ring-2 focus:ring-primary/20 shadow-sm transition-all">
            <option value="">Tüm Kategoriler</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <button onclick="showKulupEkle()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm shadow-primary/10 active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add</span>Yeni Kulüp
    </button>
</div>

<!-- Table -->
<div class="admin-card p-0 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th><input id="select-all" type="checkbox" class="rounded border-slate-300 text-primary focus:ring-primary/30"/></th>
                    <th>Kulüp</th>
                    <th>Kategori</th>
                    <th>Başkan</th>
                    <th>Üye Sayısı</th>
                    <th>Kuruluş</th>
                    <th>Durum</th>
                    <th class="text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clubs as $club)
                <tr>
                    <td><input type="checkbox" class="row-checkbox rounded border-slate-300 text-primary focus:ring-primary/30"/></td>
                    <td>
                        <div class="flex items-center gap-3">
                            @if($club->logo)
                                <img src="{{ asset('storage/' . $club->logo) }}" class="w-10 h-10 rounded-lg object-cover shrink-0 shadow-sm" alt=""/>
                            @else
                                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center shrink-0 shadow-sm">
                                    <span class="material-symbols-outlined text-white text-[18px]">groups</span>
                                </div>
                            @endif
                            <span class="font-semibold text-slate-800">{{ $club->name }}</span>
                        </div>
                    </td>
                    <td>
                        @if($club->category)
                            <span class="badge badge-primary">{{ $club->category->name }}</span>
                        @else
                            <span class="text-slate-400 text-sm">—</span>
                        @endif
                    </td>
                    <td class="text-slate-600">{{ $club->president?->name ?? '—' }}</td>
                    <td><span class="font-semibold">{{ number_format($club->member_count) }}</span></td>
                    <td class="text-slate-500">{{ $club->created_at->format('Y') }}</td>
                    <td>
                        @if($club->is_active)
                            <span class="badge badge-success shadow-sm">Aktif</span>
                        @else
                            <span class="badge badge-warning shadow-sm">Pasif</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1">
                            <button onclick="showKulupDetay({{ $club->id }}, '{{ addslashes($club->name) }}')" class="action-btn text-slate-400 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[18px]">visibility</span></button>
                            <button onclick="showKulupDuzenle({{ $club->id }}, '{{ addslashes($club->name) }}', '{{ addslashes($club->description ?? '') }}', {{ $club->category_id ?? 'null' }}, {{ $club->is_active ? 'true' : 'false' }})" class="action-btn text-slate-400 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                            <form action="{{ route('admin.kulupler.destroy', $club) }}" method="POST" onsubmit="return confirm('Bu kulübü silmek istediğinize emin misiniz?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn action-btn-danger text-slate-400"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-12 text-slate-400">
                        <span class="material-symbols-outlined text-[48px] block mb-2">groups</span>
                        Henüz kulüp bulunmuyor.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/50">
        <p class="text-sm text-slate-500">Toplam <span class="font-semibold text-slate-700">{{ $clubs->total() }}</span> kulüp</p>
        <div>{{ $clubs->links() }}</div>
    </div>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="modal-overlay absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideDeleteModal()"></div>
    <div class="relative bg-white rounded-2xl p-8 max-w-sm w-full mx-4 shadow-2xl">
        <div class="w-14 h-14 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-red-500 text-[28px]">warning</span>
        </div>
        <h3 class="text-lg font-bold font-headline text-center text-slate-800 mb-2">Silmek istediğinize emin misiniz?</h3>
        <p class="text-sm text-slate-500 text-center mb-6">"<span id="delete-item-name"></span>" kalıcı olarak silinecektir.</p>
        <div class="flex gap-3">
            <button onclick="hideDeleteModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
            <button onclick="showToast('Silme işlemi tamamlandı.', 'error'); hideDeleteModal()" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-all active:scale-95">Sil</button>
        </div>
    </div>
</div>

{{-- Kulüp Detay Modal --}}
<div id="kulup-detay-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideKulupDetay()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-lg font-bold font-headline text-slate-800">Kulüp Detayı</h3>
            <button onclick="hideKulupDetay()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-primary rounded-xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-white text-[28px]">groups</span>
                </div>
                <div>
                    <h4 id="detay-kulup-adi" class="text-xl font-bold text-slate-800"></h4>
                    <p id="detay-kulup-kategori" class="text-sm text-slate-500"></p>
                </div>
            </div>
            <p id="detay-kulup-aciklama" class="text-sm text-slate-600 leading-relaxed bg-slate-50 rounded-xl p-4"></p>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Durum</p>
                    <p id="detay-kulup-durum" class="text-sm font-bold text-slate-800"></p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Üye Sayısı</p>
                    <p id="detay-kulup-uye" class="text-sm font-bold text-slate-800"></p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Kulüp Düzenle Modal --}}
<div id="kulup-duzenle-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideKulupDuzenle()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-bold font-headline text-slate-800">Kulübü Düzenle</h3>
            <button onclick="hideKulupDuzenle()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="kulup-duzenle-form" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf @method('PUT')
            <div class="p-6 overflow-y-auto flex-1 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp Adı <span class="text-red-500">*</span></label>
                        <input id="edit-kulup-adi" name="name" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                        <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="">Seçiniz...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Açıklama</label>
                    <textarea id="edit-kulup-aciklama" name="description" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Logo</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 flex items-center gap-4 cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors">
                        <span class="material-symbols-outlined text-primary text-[24px]">cloud_upload</span>
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Logo yükle</p>
                            <p class="text-xs text-slate-400">PNG, JPG (Maks. 2MB)</p>
                        </div>
                        <input type="file" name="logo" class="hidden" accept="image/*"/>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="edit-kulup-aktif" name="is_active" value="1" class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer"/>
                    <label for="edit-kulup-aktif" class="text-sm font-semibold text-slate-600 cursor-pointer">Aktif kulüp</label>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 shrink-0 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideKulupDuzenle()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">done</span>Güncelle
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

{{-- Kulüp Ekle Modal --}}
<div id="kulup-ekle-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideKulupEkle()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
            <h3 class="text-lg font-bold font-headline text-slate-800">Yeni Kulüp Ekle</h3>
            <button onclick="hideKulupEkle()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form action="{{ route('admin.kulupler.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="p-6 overflow-y-auto flex-1 space-y-5">

                {{-- Logo yükleme --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Logo</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors group"
                         onclick="document.getElementById('logo-input').click()">
                        <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-primary text-[24px]">cloud_upload</span>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Logo yüklemek için tıklayın</p>
                        <p class="text-xs text-slate-400 mt-1">PNG, JPG (Maks. 2MB)</p>
                        <input id="logo-input" type="file" name="logo" class="hidden" accept="image/*"/>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp Adı <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required placeholder="Örn: Robotik Kulübü"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
                        <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                            <option value="">Seçiniz...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp Başkanı</label>
                        <select name="president_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                            <option value="">Seçiniz...</option>
                            @foreach(\App\Models\User::whereHas('role', fn($q) => $q->whereIn('name', ['editor','admin']))->get() as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Durum</label>
                        <select name="is_active" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm">
                            <option value="1">Aktif</option>
                            <option value="0">Pasif</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Kısa Açıklama</label>
                    <input type="text" name="short_description" placeholder="Kulüp hakkında kısa bir cümle..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Açıklama</label>
                    <textarea name="description" rows="4" placeholder="Kulüp hakkında detaylı bilgi..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm resize-none"></textarea>
                </div>

            </div>
            <div class="px-6 py-4 border-t border-slate-100 shrink-0 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideKulupEkle()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md shadow-primary/20 flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">done</span>Kulübü Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function showKulupEkle() {
    document.getElementById('kulup-ekle-modal').classList.remove('hidden');
}
function hideKulupEkle() {
    document.getElementById('kulup-ekle-modal').classList.add('hidden');
}
// Hata varsa modalı otomatik aç
@if($errors->any())
document.addEventListener('DOMContentLoaded', function() { showKulupEkle(); });
@endif
function showKulupDetay(id, adi) {
    document.getElementById('detay-kulup-adi').textContent = adi;
    document.getElementById('detay-kulup-aciklama').textContent = 'Kulüp detayları yükleniyor...';
    document.getElementById('detay-kulup-durum').textContent = 'Aktif';
    document.getElementById('detay-kulup-uye').textContent = '-';
    document.getElementById('kulup-detay-modal').classList.remove('hidden');
}
function hideKulupDetay() {
    document.getElementById('kulup-detay-modal').classList.add('hidden');
}
function showKulupDuzenle(id, adi, aciklama, kategoriId, aktif) {
    document.getElementById('edit-kulup-adi').value = adi;
    document.getElementById('edit-kulup-aciklama').value = aciklama;
    document.getElementById('edit-kulup-aktif').checked = aktif;
    document.getElementById('kulup-duzenle-form').action = '/admin/kulupler/' + id;
    document.getElementById('kulup-duzenle-modal').classList.remove('hidden');
}
function hideKulupDuzenle() {
    document.getElementById('kulup-duzenle-modal').classList.add('hidden');
}
</script>
@endpush
