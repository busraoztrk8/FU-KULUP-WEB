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
    @forelse($sliders as $slider)
    <div class="admin-card p-0 overflow-hidden group">
        <div class="relative h-44 bg-slate-100">
            @php
                $imagePath = $slider->image_path;
                $imageUrl = (str_starts_with($imagePath, 'http')) ? $imagePath : (file_exists(public_path('uploads/' . $imagePath)) ? asset('uploads/' . $imagePath) : asset('storage/' . $imagePath));
            @endphp
            <img src="{{ $imageUrl }}" class="absolute inset-0 w-full h-full object-cover" alt="{{ $slider->title }}">
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute top-3 right-3">
                <span class="text-[10px] font-bold px-2 py-1 rounded-full {{ $slider->is_active ? 'bg-green-500 text-white' : 'bg-slate-400 text-white' }}">
                    {{ $slider->is_active ? 'Aktif' : 'Pasif' }}
                </span>
            </div>
            <div class="absolute bottom-3 left-3 right-3">
                @if($slider->title)
                <p class="text-white font-bold text-sm truncate">{{ $slider->title }}</p>
                @endif
                <p class="text-white/70 text-xs">Sıra: {{ $slider->order }}</p>
            </div>
        </div>
        <div class="p-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <button onclick="showSliderDuzenle({{ $slider->id }}, '{{ e(addslashes($slider->title ?? '')) }}', '{{ e(addslashes($slider->subtitle ?? '')) }}', '{{ e(addslashes($slider->button_text ?? '')) }}', '{{ e(addslashes($slider->button_url ?? '')) }}', {{ $slider->order }}, {{ $slider->is_active ? 'true' : 'false' }}, '{{ $slider->image_path }}')"
                    class="action-btn text-slate-400 hover:text-primary transition-colors" title="Düzenle">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                </button>
                <form action="{{ route('admin.slider.destroy', $slider) }}" method="POST"
                    onsubmit="return confirm('Bu slide\'ı silmek istediğinize emin misiniz?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-btn action-btn-danger text-slate-400" title="Sil">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </form>
            </div>
            <div class="flex items-center gap-1 text-xs text-slate-400">
                <span class="material-symbols-outlined text-[16px]">drag_indicator</span>
                Sürükle
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-3 text-center py-16 text-slate-400">
        <span class="material-symbols-outlined text-[48px] block mb-2">image</span>
        Henüz slide eklenmemiş.
    </div>
    @endforelse

    {{-- Yeni Ekle Kartı --}}
    <div onclick="showSliderModal()"
        class="admin-card border-2 border-dashed border-slate-200 hover:border-primary/50 flex flex-col items-center justify-center h-[220px] cursor-pointer hover:bg-slate-50 transition-all group">
        <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-primary text-[28px]">add_photo_alternate</span>
        </div>
        <p class="text-sm font-bold text-slate-600 group-hover:text-primary transition-colors">Yeni Slide Ekle</p>
        <p class="text-xs text-slate-400 mt-1">Görsel yükle ve ayarla</p>
    </div>
</div>

{{-- Ekle Modal --}}
<div id="slider-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideSliderModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 id="slider-modal-title" class="text-lg font-bold font-headline text-slate-800">Yeni Slide Ekle</h3>
            <button onclick="hideSliderModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="slider-form" action="{{ route('admin.slider.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="slider-method" value="POST"/>
            <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Görsel <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors"
                         onclick="document.getElementById('slider-image-input').click()">
                        <span class="material-symbols-outlined text-primary text-[32px] mb-2">cloud_upload</span>
                        <p class="text-sm font-semibold text-slate-700">Görsel yüklemek için tıklayın</p>
                        <p class="text-xs text-slate-400 mt-1">PNG, JPG — Önerilen: 1920x600px</p>
                        <input id="slider-image-input" type="file" name="image" class="hidden" accept="image/*"
                               onchange="previewSliderImage(this)"/>
                    </div>
                    <img id="slider-preview" src="" alt="" class="hidden mt-2 w-full h-32 object-cover rounded-xl"/>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Başlık</label>
                        <input id="slider-baslik" type="text" name="title" placeholder="Slide başlığı..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Sıra</label>
                        <input id="slider-sira" type="number" name="order" placeholder="1" min="1"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Alt Başlık</label>
                    <input id="slider-altyazi" type="text" name="subtitle" placeholder="Kısa açıklama..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Buton Metni</label>
                        <input id="slider-btn-text" type="text" name="button_text" placeholder="Daha Fazla..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Buton URL</label>
                        <input id="slider-btn-url" type="text" name="button_url" placeholder="/etkinlikler"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="slider-aktif" name="is_active" value="1" checked
                        class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer"/>
                    <label for="slider-aktif" class="text-sm font-semibold text-slate-600 cursor-pointer">Aktif olarak yayınla</label>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideSliderModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">done</span>Kaydet
                </button>
            </div>
        </form>
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
<script>
/**
 * Resim yolunu dinamik olarak çözer (uploads veya storage)
 */
function resolveImageUrl(path) {
    if (!path) return '';
    if (path.startsWith('http')) return path;
    
    // Slider resimleri genellikle ana dizinde veya özel klasörde olabilir
    // Eğer yol '/' içeriyorsa ve bilinen storage klasörleri değilse uploads'tur
    if (path.includes('/') && !path.startsWith('logos/') && !path.startsWith('covers/') && !path.startsWith('gallery/') && !path.startsWith('profiles/')) {
        return '/uploads/' + path;
    }
    return '/storage/' + path;
}

function showSliderModal() {
    document.getElementById('slider-modal-title').textContent = 'Yeni Slide Ekle';
    document.getElementById('slider-form').action = '{{ route('admin.slider.store') }}';
    document.getElementById('slider-method').value = 'POST';
    document.getElementById('slider-baslik').value = '';
    document.getElementById('slider-altyazi').value = '';
    document.getElementById('slider-btn-text').value = '';
    document.getElementById('slider-btn-url').value = '';
    document.getElementById('slider-sira').value = '';
    document.getElementById('slider-aktif').checked = true;
    document.getElementById('slider-preview').classList.add('hidden');
    document.getElementById('slider-modal').classList.remove('hidden');
}
function showSliderDuzenle(id, baslik, altyazi, btnText, btnUrl, sira, aktif, imagePath) {
    document.getElementById('slider-modal-title').textContent = 'Slide Düzenle';
    document.getElementById('slider-form').action = '/admin/slider/' + id;
    document.getElementById('slider-method').value = 'PUT';
    document.getElementById('slider-baslik').value = baslik;
    document.getElementById('slider-altyazi').value = altyazi;
    document.getElementById('slider-btn-text').value = btnText;
    document.getElementById('slider-btn-url').value = btnUrl;
    document.getElementById('slider-sira').value = sira;
    document.getElementById('slider-aktif').checked = aktif;

    if (imagePath) {
        const preview = document.getElementById('slider-preview');
        preview.src = resolveImageUrl(imagePath);
        preview.classList.remove('hidden');
    } else {
        document.getElementById('slider-preview').classList.add('hidden');
    }

    document.getElementById('slider-modal').classList.remove('hidden');
}
function hideSliderModal() {
    document.getElementById('slider-modal').classList.add('hidden');
}
function previewSliderImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('slider-preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
