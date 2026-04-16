@extends('layouts.admin')
@section('title', 'Duyuru Yönetimi')
@section('page_title', 'Duyuru Yönetimi')
@section('data-page', 'announcements')

@section('content')

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet">
@endpush

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">campaign</span>
            </div>
            <div>
                <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['total'] }}">0</p>
                <p class="text-sm text-slate-500 font-medium">Toplam Duyuru</p>
            </div>
        </div>
        <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-green-600">publish</span>
            </div>
            <div>
                <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['published'] }}">0</p>
                <p class="text-sm text-slate-500 font-medium">Yayında</p>
            </div>
        </div>
        <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-600">article</span>
            </div>
            <div>
                <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['draft'] }}">0</p>
                <p class="text-sm text-slate-500 font-medium">Taslak</p>
            </div>
        </div>
    </div>

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

    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
        <div class="flex flex-wrap items-center gap-2">
            {{-- Search Bar --}}
            <div class="relative min-w-[200px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input type="text" id="custom-search" placeholder="Duyurularda ara..." class="w-full bg-white border border-slate-200 rounded-xl text-sm pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
            </div>

            <div class="h-8 w-[1px] bg-slate-200 mx-1 hidden md:block"></div>

            {{-- Club Select --}}
            <div class="relative min-w-[160px]">
                <select id="filter-club" class="w-full bg-white border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                    <option value="">Tüm Kulüpler</option>
                    @foreach($clubs as $club)
                        <option value="{{ $club->id }}">{{ $club->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Category Select --}}
            <div class="relative min-w-[160px]">
                <select id="filter-category" class="w-full bg-white border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                    <option value="">Tüm Kategoriler</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Reset --}}
            <button id="reset-filters" class="flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-400 hover:text-primary hover:border-primary hover:bg-primary/5 transition-all shadow-sm" title="Filtreleri Temizle">
                <span class="material-symbols-outlined text-[20px]">filter_alt_off</span>
            </button>
        </div>

        <button onclick="showDuyuruModal()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm active:scale-95 shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span>Yeni Duyuru
        </button>
    </div>

    <div class="admin-card p-0 overflow-hidden shadow-sm">
        <div class="overflow-x-auto pt-4 w-full min-h-[500px]">
            <table class="w-full table-fixed" id="duyurular-table">
                <thead>
                    <tr>
                        <th class="w-16 text-center text-slate-500 font-bold uppercase text-xs tracking-wider">ID</th>
                        <th class="w-[45%] text-slate-500 font-bold uppercase text-xs tracking-wider">Duyuru Bilgisi</th>
                        <th class="w-[20%] text-slate-500 font-bold uppercase text-xs tracking-wider">Kulüp</th>
                        <th class="w-[12%] text-center text-slate-500 font-bold uppercase text-xs tracking-wider">Durum</th>
                        <th class="w-[120px] text-center text-slate-500 font-bold uppercase text-xs tracking-wider">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <!-- DataTables will fill this -->
                </tbody>
            </table>
        </div>
    </div>

{{-- Ekle/Düzenle Modal --}}
<div id="duyuru-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideDuyuruModal()"></div>
    <div class="relative bg-white rounded-2xl w-full max-w-2xl mx-4 shadow-2xl">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 id="duyuru-modal-title" class="text-lg font-bold font-headline text-slate-800">Yeni Duyuru</h3>
            <button type="button" onclick="hideDuyuruModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form id="duyuru-form" action="{{ route('admin.duyurular.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <input type="hidden" name="_method" id="duyuru-method" value="POST">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Başlık <span class="text-red-500">*</span></label>
                    <input id="duyuru-baslik" name="title" type="text" required maxlength="100" placeholder="Duyuru başlığı..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all has-char-counter"/>
                    <div class="flex justify-end mt-1"><span class="text-[10px] text-slate-400 char-counter">0/100</span></div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                         <label class="block text-sm font-bold text-slate-700 mb-2">Görsel (Opsiyonel)</label>
                     </div>
                    <div class="{{ auth()->user()->isEditor() ? 'hidden' : '' }}">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp <span class="text-red-500">*</span></label>
                        <select id="duyuru-kulup" name="club_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="">Seçiniz...</option>
                            @foreach($clubs as $club)
                                <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Durum <span class="text-red-500">*</span></label>
                        <select id="duyuru-durum" name="is_published" required class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                            <option value="1">Aktif</option>
                            <option value="0">Taslak</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">İçerik <span class="text-red-500">*</span></label>
                    <textarea id="duyuru-icerik" name="content" rows="4" required maxlength="5000" placeholder="Duyuru içeriği..." class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none has-char-counter"></textarea>
                    <div class="flex justify-end mt-1"><span class="text-[10px] text-slate-400 char-counter">0/5000</span></div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                <button type="button" onclick="hideDuyuruModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
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
        <form id="delete-form" method="POST" class="flex gap-3">
            @csrf @method('DELETE')
            <button type="button" onclick="hideDeleteModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
            <button type="submit" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-all active:scale-95">Sil</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
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

    let table = $('#duyurular-table').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        ajax: {
            url: "{{ route('admin.duyurular') }}",
            data: function (d) {
                d.club_id = $('#filter-club').val();
                d.category_id = $('#filter-category').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-slate-600 font-medium'},
            {data: 'announcement_info', name: 'announcements.title'},
            {data: 'club_name', name: 'clubs.name'},
            {data: 'status', name: 'announcements.is_published', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        order: [[1, 'asc']],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json",
            paginate: { previous: "Önceki", next: "Sonraki" }
        },
        dom: '<"grid"rt><"flex flex-col md:flex-row items-center justify-between gap-4 p-6 border-t border-slate-100"i p>',
        initComplete: function() {
            // Stability guaranteed via CSS table-fixed
        }
    });

    // Custom Search
    $('#custom-search').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Filter Change Events
    $('#filter-club, #filter-category').on('change', function() {
        table.draw();
    });

    $('#reset-filters').on('click', function() {
        $('#filter-club, #filter-category, #custom-search').val('');
        table.search('').draw();
    });
});

function showDuyuruModal() {
    document.getElementById('duyuru-modal-title').textContent = 'Yeni Duyuru';
    document.getElementById('duyuru-form').action = "{{ route('admin.duyurular.store') }}";
    document.getElementById('duyuru-method').value = 'POST';
    document.getElementById('duyuru-baslik').value = '';
    document.getElementById('duyuru-icerik').value = '';
    document.getElementById('duyuru-kulup').value = "{{ auth()->user()->isEditor() ? auth()->user()->club_id : '' }}";
    document.getElementById('duyuru-durum').value = '1';
    document.getElementById('duyuru-preview-container').classList.add('hidden');
    document.getElementById('duyuru-modal').classList.remove('hidden');
}

/**
 * Resim yolunu dinamik olarak çözer (uploads veya storage)
 */
function resolveImageUrl(path) {
    if (!path) return '/images/logo_orj.png';
    if (path.startsWith('http')) return path;
    
    // Yükleme klasörü kontrolü
    if (path.includes('/') && !path.startsWith('logos/') && !path.startsWith('covers/') && !path.startsWith('gallery/') && !path.startsWith('profiles/')) {
        return '/uploads/' + path;
    }
    return '/storage/' + path;
}

function showDuyuruDuzenle(id) {
    // Show loading state
    document.getElementById('duyuru-baslik').value = 'Yükleniyor...';
    document.getElementById('duyuru-icerik').value = '';
    
    // Fetch real data via AJAX
    $.get('/admin/duyurular/' + id, function(data) {
        document.getElementById('duyuru-modal-title').textContent = 'Duyuruyu Düzenle';
        document.getElementById('duyuru-form').action = "/admin/duyurular/" + id;
        document.getElementById('duyuru-method').value = 'PUT';
        
        document.getElementById('duyuru-baslik').value = data.title;
        document.getElementById('duyuru-icerik').value = data.content;
        const clubSelect = document.getElementById('duyuru-kulup');
        clubSelect.value = data.club_id || '';
        clubSelect.dispatchEvent(new Event('change'));
        document.getElementById('duyuru-durum').value = data.is_published ? '1' : '0';
        
        // Sayaçları güncelle
        setTimeout(() => {
            document.querySelectorAll('#duyuru-modal .has-char-counter').forEach(el => {
                const counter = el.parentElement.querySelector('.char-counter');
                if (counter) {
                    const max = el.getAttribute('maxlength');
                    counter.textContent = `${el.value.length}/${max}`;
                }
            });
        }, 100);
        
        if (data.image_path) {
            const preview = document.getElementById('duyuru-preview');
            preview.src = resolveImageUrl(data.image_path);
            document.getElementById('duyuru-preview-container').classList.remove('hidden');
        } else {
            document.getElementById('duyuru-preview-container').classList.add('hidden');
        }

        document.getElementById('duyuru-modal').classList.remove('hidden');
    }).fail(function() {
        alert('Duyuru verileri yüklenirken bir hata oluştu.');
    });
}
function hideDuyuruModal() {
    document.getElementById('duyuru-modal').classList.add('hidden');
}

$(document).on('input', '.has-char-counter', function() {
    const counter = $(this).parent().find('.char-counter');
    const max = $(this).attr('maxlength');
    const len = $(this).val().length;
    counter.text(len + '/' + max);
    if (len >= max) {
        counter.addClass('text-red-500').removeClass('text-slate-400');
    } else {
        counter.addClass('text-slate-400').removeClass('text-red-500');
    }
});
function showDeleteModal(id, baslik) {
    document.getElementById('delete-item-name').textContent = baslik;
    document.getElementById('delete-form').action = "/admin/duyurular/" + id;
    document.getElementById('delete-modal').classList.remove('hidden');
}
function hideDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
}
</script>
@endpush
