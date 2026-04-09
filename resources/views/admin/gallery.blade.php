@extends('layouts.admin')

@section('title', 'Galeri Yönetimi')
@section('page_title', 'Kampüs Yaşamından Kareler')
@section('data-page', 'gallery')

@section('content')

@if(session('success'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium flex items-center gap-2">
    <span class="material-symbols-outlined text-[18px]">error</span>{{ session('error') }}
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

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <p class="text-sm text-slate-500">Ana sayfadaki "Kampüs Yaşamından Kareler" bölümündeki görselleri buradan yönetin.</p>
    <button onclick="showGalleryAddModal()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add_a_photo</span>Yeni Resim Ekle
    </button>
</div>

{{-- Galeri Listesi --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @forelse($images as $image)
    <div class="admin-card p-0 overflow-hidden group border border-slate-100 flex flex-col">
        <div class="relative h-48 bg-slate-100 overflow-hidden">
            <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $image->title }}"/>
            <div class="absolute top-3 right-3 flex gap-2">
                @if($image->is_active)
                    <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-sm">Aktif</span>
                @else
                    <span class="bg-slate-500 text-white text-[10px] font-bold px-2 py-1 rounded-full shadow-sm">Pasif</span>
                @endif
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/60 to-transparent">
                <p class="text-white font-bold text-xs truncate">{{ $image->title ?? 'Başlıksız' }}</p>
            </div>
        </div>
        <div class="p-3 flex items-center justify-between bg-white mt-auto">
            <div class="flex items-center gap-1">
                <button onclick="showGalleryEditModal({{ $image->id }}, '{{ e(addslashes($image->title)) }}', {{ $image->order }}, {{ $image->is_active ? 'true' : 'false' }})" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Düzenle">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                </button>
                <form action="{{ route('admin.gallery.destroy', $image->id) }}" method="POST" onsubmit="return confirm('Bu resmi galeriden silmek istediğinize emin misiniz?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn action-btn-danger text-slate-400" title="Sil">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </form>
            </div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sıra: {{ $image->order }}</span>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
        <span class="material-symbols-outlined text-slate-300 text-[64px] mb-4">image_not_supported</span>
        <p class="text-slate-500 font-medium">Henüz galeriye resim eklenmemiş.</p>
        <button onclick="showGalleryAddModal()" class="mt-4 text-primary font-bold text-sm hover:underline">İlk resmi ekle</button>
    </div>
    @endforelse
</div>

{{-- Ekle Modal --}}
<div id="gallery-add-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideGalleryAddModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-lg font-bold font-headline text-slate-800">Yeni Resim Ekle</h3>
            <button onclick="hideGalleryAddModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" id="add-gallery-form">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Görsel <span class="text-red-500">*</span></label>
                    <div id="drop-area" class="border-2 border-dashed border-slate-200 rounded-xl p-8 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors group" onclick="document.getElementById('image-input').click()">
                        <div id="preview-container" class="hidden mb-4 w-full h-40 overflow-hidden rounded-lg bg-slate-100 border border-slate-200 relative">
                            <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover">
                            <button type="button" onclick="event.stopPropagation(); removeSelectedImage();" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center shadow-lg hover:bg-red-600">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </button>
                        </div>
                        <div id="upload-placeholder" class="flex flex-col items-center">
                            <span class="material-symbols-outlined text-primary text-[32px] mb-2 group-hover:scale-110 transition-transform">cloud_upload</span>
                            <p class="text-sm font-semibold text-slate-700">Seçmek için tıklayın</p>
                            <p class="text-xs text-slate-400 mt-1">PNG, JPG — Max 5MB</p>
                        </div>
                        <p id="filename-display" class="hidden mt-2 text-xs font-bold text-primary italic truncate max-w-xs"></p>
                        <input id="image-input" name="image" type="file" class="hidden" accept="image/*" required onchange="handleFileSelect(this)"/>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Başlık / Alt Metin</label>
                    <input name="title" type="text" value="{{ old('title') }}" placeholder="Resim açıklaması..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Görüntüleme Sırası</label>
                    <input name="order" type="number" value="{{ old('order', 0) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideGalleryAddModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">upload</span>Yükle
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Düzenle Modal --}}
<div id="gallery-edit-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideGalleryEditModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-lg font-bold font-headline text-slate-800">Resmi Düzenle</h3>
            <button onclick="hideGalleryEditModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="edit-gallery-form" method="POST">
            @csrf @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Başlık / Alt Metin</label>
                    <input id="edit-title" name="title" type="text" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Görüntüleme Sırası</label>
                    <input id="edit-order" name="order" type="number" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="edit-active" name="is_active" value="1" class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer"/>
                    <label for="edit-active" class="text-sm font-semibold text-slate-600 cursor-pointer">Aktif olarak göster</label>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideGalleryEditModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
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
// Resim seçildiğinde önizleme ve dosya adı göster
function handleFileSelect(input) {
    const placeholder = document.getElementById('upload-placeholder');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('image-preview');
    const filenameDisplay = document.getElementById('filename-display');
    const dropArea = document.getElementById('drop-area');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Boyut kontrolü (Max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Dosya boyutu 5MB\'dan küçük olmalıdır.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            placeholder.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            filenameDisplay.textContent = file.name;
            filenameDisplay.classList.remove('hidden');
            dropArea.classList.remove('p-8');
            dropArea.classList.add('p-2');
        }
        reader.readAsDataURL(file);
    }
}

// Seçilen resmi kaldır
function removeSelectedImage() {
    const input = document.getElementById('image-input');
    const placeholder = document.getElementById('upload-placeholder');
    const previewContainer = document.getElementById('preview-container');
    const filenameDisplay = document.getElementById('filename-display');
    const dropArea = document.getElementById('drop-area');

    input.value = '';
    previewContainer.classList.add('hidden');
    placeholder.classList.remove('hidden');
    filenameDisplay.classList.add('hidden');
    dropArea.classList.remove('p-2');
    dropArea.classList.add('p-8');
}

function showGalleryAddModal() {
    document.getElementById('gallery-add-modal').classList.remove('hidden');
}
function hideGalleryAddModal() {
    document.getElementById('gallery-add-modal').classList.add('hidden');
    removeSelectedImage();
}
function showGalleryEditModal(id, title, order, active) {
    document.getElementById('edit-gallery-form').action = '/admin/galeri/' + id;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-order').value = order;
    document.getElementById('edit-active').checked = active;
    document.getElementById('gallery-edit-modal').classList.remove('hidden');
}
function hideGalleryEditModal() {
    document.getElementById('gallery-edit-modal').classList.add('hidden');
}

// Sayfa yüklendiğinde hata varsa modalı otomatik aç
document.addEventListener('DOMContentLoaded', function() {
    @if($errors->any())
        // Eğer hata varsa ve 'old' değerleri 'store' işlemine aitse (image zorunluysa bu store'dur)
        @if(old('title') !== null || old('order') !== null)
            showGalleryAddModal();
        @endif
    @endif
});
</script>
@endpush
