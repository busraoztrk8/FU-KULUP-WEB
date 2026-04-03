@extends('layouts.admin')
@section('title', 'Menü Yönetimi')
@section('page_title', 'Menü Yönetimi')
@section('data-page', 'menu')

@section('content')

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <p class="text-sm text-slate-500">Site navigasyon menüsündeki linkleri buradan yönetin.</p>
    <button onclick="showMenuModal()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add</span>Yeni Menü Öğesi
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Ana Menü --}}
    <div class="admin-card shadow-sm">
        <h3 class="font-bold font-headline text-slate-800 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">menu</span>
            Ana Navigasyon
        </h3>
        <div class="space-y-2">
            @foreach([
                ['label' => 'Ana Sayfa', 'url' => '/', 'active' => true],
                ['label' => 'Etkinlikler', 'url' => '/etkinlikler', 'active' => true],
                ['label' => 'Kulüpler', 'url' => '/kulupler', 'active' => true],
                ['label' => 'Hakkımızda', 'url' => '/hakkimizda', 'active' => false],
            ] as $item)
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 hover:border-primary/20 transition-all group">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-slate-300 text-[18px] cursor-grab">drag_indicator</span>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $item['label'] }}</p>
                        <p class="text-xs text-slate-400">{{ $item['url'] }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge {{ $item['active'] ? 'badge-success' : 'badge-warning' }} text-[10px]">
                        {{ $item['active'] ? 'Aktif' : 'Pasif' }}
                    </span>
                    <button onclick="showMenuDuzenle('{{ $item['label'] }}')" class="action-btn text-slate-400 hover:text-primary transition-colors opacity-0 group-hover:opacity-100" title="Düzenle">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>
                    <button onclick="showDeleteModal('{{ $item['label'] }}')" class="action-btn action-btn-danger text-slate-400 opacity-0 group-hover:opacity-100" title="Sil">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Footer Menü --}}
    <div class="admin-card shadow-sm">
        <h3 class="font-bold font-headline text-slate-800 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">bottom_navigation</span>
            Footer Navigasyon
        </h3>
        <div class="space-y-2">
            @foreach([
                ['label' => 'İletişim', 'url' => '/iletisim', 'active' => true],
                ['label' => 'KVKK', 'url' => '/kvkk', 'active' => true],
                ['label' => 'Gizlilik Politikası', 'url' => '/gizlilik', 'active' => true],
                ['label' => 'E-Devlet', 'url' => 'https://edevlet.gov.tr', 'active' => true],
            ] as $item)
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 hover:border-primary/20 transition-all group">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-slate-300 text-[18px] cursor-grab">drag_indicator</span>
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $item['label'] }}</p>
                        <p class="text-xs text-slate-400">{{ $item['url'] }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge badge-success text-[10px]">Aktif</span>
                    <button onclick="showMenuDuzenle('{{ $item['label'] }}')" class="action-btn text-slate-400 hover:text-primary transition-colors opacity-0 group-hover:opacity-100" title="Düzenle">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>
                    <button onclick="showDeleteModal('{{ $item['label'] }}')" class="action-btn action-btn-danger text-slate-400 opacity-0 group-hover:opacity-100" title="Sil">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Ekle/Düzenle Modal --}}
<div id="menu-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideMenuModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 id="menu-modal-title" class="text-lg font-bold font-headline text-slate-800">Yeni Menü Öğesi</h3>
            <button onclick="hideMenuModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Etiket <span class="text-red-500">*</span></label>
                <input id="menu-label" type="text" placeholder="Menüde görünecek isim..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">URL <span class="text-red-500">*</span></label>
                <input type="text" placeholder="/sayfa-adi veya https://..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Menü Konumu</label>
                    <select class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option>Ana Navigasyon</option>
                        <option>Footer Navigasyon</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Hedef</label>
                    <select class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        <option value="_self">Aynı Sekme</option>
                        <option value="_blank">Yeni Sekme</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" id="menu-aktif" checked class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer"/>
                <label for="menu-aktif" class="text-sm font-semibold text-slate-600 cursor-pointer">Aktif olarak göster</label>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
            <button onclick="hideMenuModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
            <button onclick="showToast('Menü öğesi kaydedildi!', 'success'); hideMenuModal()" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
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
        <p class="text-sm text-slate-500 text-center mb-6">"<span id="delete-item-name"></span>" menüden kaldırılacaktır.</p>
        <div class="flex gap-3">
            <button onclick="hideDeleteModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
            <button onclick="showToast('Menü öğesi silindi.', 'error'); hideDeleteModal()" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-all active:scale-95">Sil</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showMenuModal() {
    document.getElementById('menu-modal-title').textContent = 'Yeni Menü Öğesi';
    document.getElementById('menu-label').value = '';
    document.getElementById('menu-modal').classList.remove('hidden');
}
function showMenuDuzenle(label) {
    document.getElementById('menu-modal-title').textContent = 'Menü Öğesini Düzenle';
    document.getElementById('menu-label').value = label;
    document.getElementById('menu-modal').classList.remove('hidden');
}
function hideMenuModal() {
    document.getElementById('menu-modal').classList.add('hidden');
}
</script>
@endpush
