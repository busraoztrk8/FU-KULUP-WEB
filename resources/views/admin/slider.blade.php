@extends('layouts.admin')
@section('title', 'Slider Yönetimi')
@section('page_title', 'Slider Yönetimi')
@section('data-page', 'slider')

@section('content')

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ session('success') }}
</div>
@endif

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <p class="text-sm text-slate-500">Ana sayfada gösterilecek slider görsellerini buradan yönetin.</p>
    <button onclick="showSliderModal()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add</span>Yeni Slide Ekle
    </button>
</div>

{{-- Slider Listesi --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    {{-- Slide 1 --}}
    <div class="admin-card p-0 overflow-hidden group">
        <div class="relative h-44 bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center">
            <span class="material-symbols-outlined text-white/30 text-[64px]">image</span>
            <div class="absolute top-3 right-3 flex gap-2">
                <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded-full">Aktif</span>
            </div>
            <div class="absolute bottom-3 left-3 right-3">
                <p class="text-white font-bold text-sm truncate">Geleceğin Teknolojileri Zirvesi</p>
                <p class="text-white/70 text-xs">Sıra: 1</p>
            </div>
        </div>
        <div class="p-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <button onclick="showSliderDuzenle('Geleceğin Teknolojileri Zirvesi')" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Düzenle">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                </button>
                <button onclick="showDeleteModal('Slide 1')" class="action-btn action-btn-danger text-slate-400" title="Sil">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="showToast('Sıra değiştirildi', 'info')" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Yukarı">
                    <span class="material-symbols-outlined text-[18px]">arrow_upward</span>
                </button>
                <button onclick="showToast('Sıra değiştirildi', 'info')" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Aşağı">
                    <span class="material-symbols-outlined text-[18px]">arrow_downward</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Slide 2 --}}
    <div class="admin-card p-0 overflow-hidden group">
        <div class="relative h-44 bg-gradient-to-br from-tertiary to-[#0494c7] flex items-center justify-center">
            <span class="material-symbols-outlined text-white/30 text-[64px]">image</span>
            <div class="absolute top-3 right-3 flex gap-2">
                <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded-full">Aktif</span>
            </div>
            <div class="absolute bottom-3 left-3 right-3">
                <p class="text-white font-bold text-sm truncate">Kariyer Günleri 2026</p>
                <p class="text-white/70 text-xs">Sıra: 2</p>
            </div>
        </div>
        <div class="p-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <button onclick="showSliderDuzenle('Kariyer Günleri 2026')" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Düzenle">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                </button>
                <button onclick="showDeleteModal('Slide 2')" class="action-btn action-btn-danger text-slate-400" title="Sil">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                </button>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="showToast('Sıra değiştirildi', 'info')" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Yukarı">
                    <span class="material-symbols-outlined text-[18px]">arrow_upward</span>
                </button>
                <button onclick="showToast('Sıra değiştirildi', 'info')" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Aşağı">
                    <span class="material-symbols-outlined text-[18px]">arrow_downward</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Yeni Ekle Kartı --}}
    <div onclick="showSliderModal()" class="admin-card border-2 border-dashed border-slate-200 hover:border-primary/50 flex flex-col items-center justify-center h-[220px] cursor-pointer hover:bg-slate-50 transition-all group">
        <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-primary text-[28px]">add_photo_alternate</span>
        </div>
        <p class="text-sm font-bold text-slate-600 group-hover:text-primary transition-colors">Yeni Slide Ekle</p>
        <p class="text-xs text-slate-400 mt-1">Görsel yükle ve ayarla</p>
    </div>
</div>

{{-- Ekle/Düzenle Modal --}}
<div id="slider-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideSliderModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 id="slider-modal-title" class="text-lg font-bold font-headline text-slate-800">Yeni Slide Ekle</h3>
            <button onclick="hideSliderModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Görsel <span class="text-red-500">*</span></label>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors">
                    <span class="material-symbols-outlined text-primary text-[32px] mb-2">cloud_upload</span>
                    <p class="text-sm font-semibold text-slate-700">Görsel yüklemek için tıklayın</p>
                    <p class="text-xs text-slate-400 mt-1">PNG, JPG — Önerilen: 1920x600px</p>
                    <input type="file" class="hidden" accept="image/*"/>
                </div>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Başlık</label>
                <input id="slider-baslik" type="text" placeholder="Slide başlığı..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Alt Başlık</label>
                <input type="text" placeholder="Kısa açıklama..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Link (URL)</label>
                    <input type="url" placeholder="https://..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Sıra</label>
                    <input type="number" placeholder="1" min="1" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <input type="checkbox" id="slider-aktif" checked class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer"/>
                <label for="slider-aktif" class="text-sm font-semibold text-slate-600 cursor-pointer">Aktif olarak yayınla</label>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
            <button onclick="hideSliderModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
            <button onclick="showToast('Slide kaydedildi!', 'success'); hideSliderModal()" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
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
            <button onclick="showToast('Slide silindi.', 'error'); hideDeleteModal()" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-all active:scale-95">Sil</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function showSliderModal() {
    document.getElementById('slider-modal-title').textContent = 'Yeni Slide Ekle';
    document.getElementById('slider-baslik').value = '';
    document.getElementById('slider-modal').classList.remove('hidden');
}
function showSliderDuzenle(baslik) {
    document.getElementById('slider-modal-title').textContent = 'Slide Düzenle';
    document.getElementById('slider-baslik').value = baslik;
    document.getElementById('slider-modal').classList.remove('hidden');
}
function hideSliderModal() {
    document.getElementById('slider-modal').classList.add('hidden');
}
</script>
@endpush
