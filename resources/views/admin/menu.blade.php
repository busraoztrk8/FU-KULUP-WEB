@extends('layouts.admin')
@section('title', 'Menü Yönetimi')
@section('page_title', 'Menü Yönetimi')
@section('data-page', 'menu')

@section('content')

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ session('success') }}
</div>
@endif

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
            @forelse($mainMenus as $item)
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 hover:border-primary/20 transition-all group">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="material-symbols-outlined text-slate-300 text-[18px] cursor-grab shrink-0">drag_indicator</span>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ $item->label }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $item->url }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0 ml-2">
                    <span class="badge {{ $item->is_active ? 'badge-success' : 'badge-warning' }} text-[10px]">
                        {{ $item->is_active ? 'Aktif' : 'Pasif' }}
                    </span>
                    <button onclick="showMenuDuzenle({{ $item->id }}, '{{ addslashes($item->label) }}', '{{ addslashes($item->url) }}', '{{ $item->location }}', '{{ $item->target }}', {{ $item->order }}, {{ $item->is_active ? 'true' : 'false' }})"
                        class="action-btn text-slate-400 hover:text-primary transition-colors opacity-0 group-hover:opacity-100" title="Düzenle">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>
                    <form action="{{ route('admin.menu.destroy', $item) }}" method="POST"
                        onsubmit="return confirm('Bu menü öğesini silmek istediğinize emin misiniz?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn action-btn-danger text-slate-400 opacity-0 group-hover:opacity-100" title="Sil">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-sm text-slate-400 text-center py-6">Henüz menü öğesi yok.</p>
            @endforelse
        </div>
    </div>

    {{-- Footer Menü --}}
    <div class="admin-card shadow-sm">
        <h3 class="font-bold font-headline text-slate-800 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-[20px]">bottom_navigation</span>
            Footer Navigasyon
        </h3>
        <div class="space-y-2">
            @forelse($footerMenus as $item)
            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100 hover:border-primary/20 transition-all group">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="material-symbols-outlined text-slate-300 text-[18px] cursor-grab shrink-0">drag_indicator</span>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-800 truncate">{{ $item->label }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $item->url }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0 ml-2">
                    <span class="badge {{ $item->is_active ? 'badge-success' : 'badge-warning' }} text-[10px]">
                        {{ $item->is_active ? 'Aktif' : 'Pasif' }}
                    </span>
                    <button onclick="showMenuDuzenle({{ $item->id }}, '{{ addslashes($item->label) }}', '{{ addslashes($item->url) }}', '{{ $item->location }}', '{{ $item->target }}', {{ $item->order }}, {{ $item->is_active ? 'true' : 'false' }})"
                        class="action-btn text-slate-400 hover:text-primary transition-colors opacity-0 group-hover:opacity-100" title="Düzenle">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>
                    <form action="{{ route('admin.menu.destroy', $item) }}" method="POST"
                        onsubmit="return confirm('Bu menü öğesini silmek istediğinize emin misiniz?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn action-btn-danger text-slate-400 opacity-0 group-hover:opacity-100" title="Sil">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <p class="text-sm text-slate-400 text-center py-6">Henüz menü öğesi yok.</p>
            @endforelse
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
        <form id="menu-form" action="{{ route('admin.menu.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="menu-method" value="POST"/>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Etiket <span class="text-red-500">*</span></label>
                    <input id="menu-label" type="text" name="label" placeholder="Menüde görünecek isim..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">URL <span class="text-red-500">*</span></label>
                    <input id="menu-url" type="text" name="url" placeholder="/sayfa-adi veya https://..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Konum</label>
                        <select id="menu-location" name="location"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="main">Ana Navigasyon</option>
                            <option value="footer">Footer Navigasyon</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Hedef</label>
                        <select id="menu-target" name="target"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="_self">Aynı Sekme</option>
                            <option value="_blank">Yeni Sekme</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Sıra</label>
                    <input id="menu-order" type="number" name="order" placeholder="0" min="0"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="menu-aktif" name="is_active" value="1" checked
                        class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer"/>
                    <label for="menu-aktif" class="text-sm font-semibold text-slate-600 cursor-pointer">Aktif olarak göster</label>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideMenuModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">done</span>Kaydet
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showMenuModal() {
    document.getElementById('menu-modal-title').textContent = 'Yeni Menü Öğesi';
    document.getElementById('menu-form').action = '{{ route('admin.menu.store') }}';
    document.getElementById('menu-method').value = 'POST';
    document.getElementById('menu-label').value = '';
    document.getElementById('menu-url').value = '';
    document.getElementById('menu-location').value = 'main';
    document.getElementById('menu-target').value = '_self';
    document.getElementById('menu-order').value = '';
    document.getElementById('menu-aktif').checked = true;
    document.getElementById('menu-modal').classList.remove('hidden');
}
function showMenuDuzenle(id, label, url, location, target, order, aktif) {
    document.getElementById('menu-modal-title').textContent = 'Menü Öğesini Düzenle';
    document.getElementById('menu-form').action = '/admin/menu/' + id;
    document.getElementById('menu-method').value = 'PUT';
    document.getElementById('menu-label').value = label;
    document.getElementById('menu-url').value = url;
    document.getElementById('menu-location').value = location;
    document.getElementById('menu-target').value = target;
    document.getElementById('menu-order').value = order;
    document.getElementById('menu-aktif').checked = aktif;
    document.getElementById('menu-modal').classList.remove('hidden');
}
function hideMenuModal() {
    document.getElementById('menu-modal').classList.add('hidden');
}
</script>
@endpush
