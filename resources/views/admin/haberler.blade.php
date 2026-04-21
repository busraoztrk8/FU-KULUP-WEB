@extends('layouts.admin')
@section('title', __('messages.title'))
@section('page_title', __('messages.title'))
@section('data-page', 'news')

@push('styles')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" rel="stylesheet">
    <style>
        #haberler-table { border-collapse: collapse; width: 100% !important; }
        #haberler-table thead th { background: #f8fafc; border-bottom: 2px solid #e2e8f0; padding: 12px 16px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; white-space: nowrap; }
        #haberler-table tbody td { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 14px; color: #334155; white-space: nowrap; }
        #haberler-table tbody td:nth-child(2) { max-width: 300px; overflow: hidden; text-overflow: ellipsis; }
        #haberler-table tbody tr:hover td { background: #f8fafc; }
        #haberler-table tbody tr:last-child td { border-bottom: none; }
        #haberler-table_wrapper .dataTables_info { font-size: 13px; color: #64748b; }
        #haberler-table_wrapper .dataTables_paginate { display: flex; gap: 4px; }
        #haberler-table_wrapper .dataTables_paginate .paginate_button { padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; border: 1px solid #e2e8f0; color: #475569 !important; background: #fff; }
        #haberler-table_wrapper .dataTables_paginate .paginate_button.current { background: var(--primary, #5d1021) !important; color: #fff !important; border-color: var(--primary, #5d1021); }
        #haberler-table_wrapper .dataTables_paginate .paginate_button:hover:not(.current) { background: #f1f5f9 !important; }
        #haberler-table_wrapper .dataTables_paginate .paginate_button.disabled { opacity: 0.4; cursor: not-allowed; }
        .dataTables_empty { padding: 40px 16px !important; text-align: center !important; color: #94a3b8; font-size: 14px; }
    </style>
@endpush

@section('content')

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-primary">feed</span>
            </div>
            <div>
                <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['total'] }}">0</p>
                <p class="text-sm text-slate-500 font-medium">Toplam Haber</p>
            </div>
        </div>
        <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-green-600">visibility</span>
            </div>
            <div>
                <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['published'] }}">0</p>
                <p class="text-sm text-slate-500 font-medium">Yayında</p>
            </div>
        </div>
        <div class="admin-card flex items-center gap-4 shadow-sm hover:shadow-md transition-all">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-600">edit_note</span>
            </div>
            <div>
                <p class="text-2xl font-bold font-headline text-slate-800" data-count="{{ $stats['draft'] }}">0</p>
                <p class="text-sm text-slate-500 font-medium">Taslak</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div
            class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium">
            <div class="flex items-center gap-2 mb-2"><span
                    class="material-symbols-outlined text-[18px]">error</span><strong>Hata oluştu:</strong></div>
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
                <input type="text" id="custom-search" placeholder="Haberlerde ara..." class="w-full bg-white border border-slate-200 rounded-xl text-sm pl-10 pr-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
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

            {{-- Status Select --}}
            <div class="relative min-w-[140px]">
                <select id="status-filter" class="w-full bg-white border border-slate-200 rounded-xl text-sm px-4 py-2.5 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                    <option value="all">Tüm Durumlar</option>
                    <option value="1">Aktif</option>
                    <option value="0">Pasif</option>
                </select>
            </div>

            {{-- Reset --}}
            <button id="reset-filters" class="flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-400 hover:text-primary hover:border-primary hover:bg-primary/5 transition-all shadow-sm" title="Filtreleri Temizle">
                <span class="material-symbols-outlined text-[20px]">filter_alt_off</span>
            </button>
        </div>

        <button onclick="showHaberModal()" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm active:scale-95 shrink-0">
            <span class="material-symbols-outlined text-[18px]">add</span>{{ __('messages.add_new') }}
        </button>
    </div>

    <div class="admin-card p-0 overflow-hidden shadow-sm">
        <div class="overflow-x-auto w-full">
            <table id="haberler-table" class="w-full">
                <thead>
                    <tr>
                        <th style="width:60px">ID</th>
                        <th>Haber Başlığı</th>
                        <th style="width:160px">Kulüp</th>
                        <th style="width:130px">Kategori</th>
                        <th style="width:110px">Tarih</th>
                        <th style="width:100px">Durum</th>
                        <th style="width:110px">İşlemler</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    {{-- Yeni/Düzenle Modal --}}
    <div id="haber-modal" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideHaberModal()"></div>
        <div class="relative bg-white rounded-2xl w-full max-w-2xl mx-4 max-h-[90vh] flex flex-col shadow-2xl">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 shrink-0">
                <h3 id="haber-modal-title" class="text-lg font-bold font-headline text-slate-800">Yeni Haber Ekle</h3>
                <button type="button" onclick="hideHaberModal()"
                    class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            <form id="haber-form" action="{{ route('admin.haberler.store') }}" method="POST" enctype="multipart/form-data"
                class="flex flex-col flex-1 overflow-hidden">
                @csrf
                <input type="hidden" name="_method" id="haber-method" value="POST">
                <div class="p-6 overflow-y-auto flex-1 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Başlık <span
                                class="text-red-500">*</span></label>
                        <input id="haber-baslik" name="title" type="text" required maxlength="100" placeholder="Haber başlığı..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all has-char-counter" />
                        <div class="flex justify-end mt-1"><span class="text-[10px] text-slate-400 char-counter">0/100</span></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Görsel (Opsiyonel)</label>
                            <input id="haber-gorsel" name="image" type="file" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-2 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20"/>
                        </div>
                    </div>

                    {{-- Görsel Önizleme --}}
                    <div class="mb-4 hidden" id="haber-preview-container">
                        <img id="haber-preview" src="" class="w-full h-40 object-cover rounded-xl border border-slate-100 shadow-sm">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="{{ auth()->user()->isEditor() ? 'hidden' : '' }}">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kulüp <span class="text-red-500">*</span></label>
                            <select id="haber-kulup" name="club_id" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="">Seçiniz...</option>
                                @foreach($clubs as $club)
                                    <option value="{{ $club->id }}">{{ $club->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Durum <span
                                    class="text-red-500">*</span></label>
                            <select id="haber-durum" name="is_published" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                <option value="1">Yayınla</option>
                                <option value="0">Taslak</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">İçerik <span
                                class="text-red-500">*</span></label>
                        <textarea id="haber-icerik" name="content" rows="6" required maxlength="5000" placeholder="Haber içeriği..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none has-char-counter"></textarea>
                        <div class="flex justify-end mt-1"><span class="text-[10px] text-slate-400 char-counter">0/5000</span></div>
                    </div>
                </div>
                <div
                    class="px-6 py-4 border-t border-slate-100 shrink-0 flex items-center justify-end gap-3 bg-slate-50 rounded-b-2xl">
                    <button type="button" onclick="hideHaberModal()"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-white transition-all active:scale-95">İptal</button>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary-dim text-white font-bold text-sm transition-all shadow-md flex items-center gap-2 active:scale-95">
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
            <h3 class="text-lg font-bold font-headline text-center text-slate-800 mb-2">Silmek istediğinize emin misiniz?
            </h3>
            <p class="text-sm text-slate-500 text-center mb-6">"<span id="delete-item-name"></span>" kalıcı olarak
                silinecektir.</p>
            <form id="delete-form" method="POST" class="flex gap-3">
                @csrf @method('DELETE')
                <button type="button" onclick="hideDeleteModal()"
                    class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
                <button type="submit"
                    class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-all active:scale-95">Sil</button>
            </form>
        </div>
    </div>

    {{-- Sayfa Hero Alanları --}}
    <div class="mt-12 bg-white rounded-2xl md:rounded-[2rem] p-6 md:p-10 border border-slate-100 shadow-sm">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-[24px]">newspaper</span>
            </div>
            <div>
                <h3 class="text-lg font-bold font-headline text-slate-800">Sayfa Hero Alanları</h3>
                <p class="text-xs text-slate-500">Haberler sayfasının başındaki banner alanını özelleştirin.</p>
            </div>
        </div>

        <form action="{{ route('admin.ayarlar.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Banner Başlığı</label>
                        <input type="text" name="news_hero_title" value="{{ \App\Models\SiteSetting::getVal('news_hero_title', 'Haberler') }}" 
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Banner Alt Yazısı</label>
                        <textarea name="news_hero_subtitle" rows="3"
                                  class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none">{{ \App\Models\SiteSetting::getVal('news_hero_subtitle', 'Üniversitemizden en son haberler...') }}</textarea>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Arka Plan Görseli</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-50 hover:border-primary/50 transition-colors group relative"
                         onclick="this.querySelector('input').click()">
                        @php
                            $heroImg = \App\Models\SiteSetting::getVal('news_hero_image');
                            $heroUrl = $heroImg ? (file_exists(public_path('uploads/' . $heroImg)) ? asset('uploads/' . $heroImg) : asset('storage/' . $heroImg)) : null;
                        @endphp
                        <div class="hero-preview-box {{ $heroUrl ? '' : 'hidden' }} absolute inset-0 w-full h-full p-2">
                             <img src="{{ $heroUrl }}" class="w-full h-full object-cover rounded-lg shadow-inner"/>
                        </div>
                        <div class="hero-placeholder {{ $heroUrl ? 'hidden' : '' }} flex flex-col items-center">
                            <span class="material-symbols-outlined text-slate-300 text-[48px] mb-2">add_photo_alternate</span>
                            <p class="text-xs font-semibold text-slate-500">Görsel seçmek için tıklayın</p>
                        </div>
                        <input type="file" name="news_hero_image" class="hidden" accept="image/*" onchange="previewHero(this)"/>
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-primary hover:bg-primary-dim text-white px-10 py-3 rounded-xl font-bold text-sm transition-all shadow-lg active:scale-95">
                    Ayarları Kaydet
                </button>
            </div>
        </form>
    </div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
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

            let table = $('#haberler-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.haberler') }}",
                    data: function (d) {
                        d.club_id = $('#filter-club').val();
                        d.category_id = $('#filter-category').val();
                        d.status = $('#status-filter').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center text-slate-600 font-medium' },
                    { data: 'news_info', name: 'news.title' },
                    { data: 'club_name', name: 'clubs.name' },
                    { data: 'category_name', name: 'categories.name' },
                    { data: 'date', name: 'news.created_at' },
                    { data: 'status', name: 'news.is_published', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                order: [[0, 'asc']],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json",
                    paginate: { previous: "Önceki", next: "Sonraki" }
                },
                dom: 'rt<"flex flex-col md:flex-row items-center justify-between gap-4 px-4 py-4 border-t border-slate-100"ip>',
                initComplete: function() {
                }
            });

            // Custom Search
            $('#custom-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Filter Change Events
            $('#filter-club, #filter-category, #status-filter').on('change', function() {
                table.draw();
            });

            $('#reset-filters').on('click', function() {
                $('#filter-club, #filter-category, #custom-search').val('');
                $('#status-filter').val('all');
                table.search('').draw();
            });
        });

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

function showHaberModal() {
            document.getElementById('haber-modal-title').textContent = 'Yeni Haber Ekle';
            document.getElementById('haber-form').action = "{{ route('admin.haberler.store') }}";
            document.getElementById('haber-method').value = 'POST';
            document.getElementById('haber-baslik').value = '';
            document.getElementById('haber-icerik').value = '';
            document.getElementById('haber-kulup').value = "{{ auth()->user()->isEditor() ? auth()->user()->club_id : '' }}";
            document.getElementById('haber-durum').value = '1';
            document.getElementById('haber-preview-container').classList.add('hidden');
            document.getElementById('haber-modal').classList.remove('hidden');
        }

function showHaberDuzenle(id) {
    // Show loading state or just clear the modal
    document.getElementById('haber-baslik').value = 'Yükleniyor...';
    document.getElementById('haber-icerik').value = '';
    
    // Fetch real data via AJAX
    $.get('/admin/haberler/' + id, function(data) {
        document.getElementById('haber-modal-title').textContent = 'Haberi Düzenle';
        document.getElementById('haber-form').action = "/admin/haberler/" + id;
        document.getElementById('haber-method').value = 'PUT';
        
        document.getElementById('haber-baslik').value = data.title;
        document.getElementById('haber-icerik').value = data.content;
        const clubSelect = document.getElementById('haber-kulup');
        clubSelect.value = data.club_id || '';
        clubSelect.dispatchEvent(new Event('change'));
        document.getElementById('haber-durum').value = data.is_published ? '1' : '0';
        
        // Sayaçları güncelle
        setTimeout(() => {
            document.querySelectorAll('#haber-modal .has-char-counter').forEach(el => {
                const counter = el.parentElement.querySelector('.char-counter');
                if (counter) {
                    const max = el.getAttribute('maxlength');
                    counter.textContent = `${el.value.length}/${max}`;
                }
            });
        }, 100);
        
        document.getElementById('haber-modal').classList.remove('hidden');
    }).fail(function() {
        alert('Haber verileri yüklenirken bir hata oluştu.');
    });
}

        function hideHaberModal() {
            document.getElementById('haber-modal').classList.add('hidden');
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
            document.getElementById('delete-form').action = "/admin/haberler/" + id;
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function hideDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

function previewHero(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const container = input.closest('div');
        const previewImg = container.querySelector('.hero-preview-box img');
        const previewBox = container.querySelector('.hero-preview-box');
        const placeholder = container.querySelector('.hero-placeholder');
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewBox.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush