@extends('layouts.admin')

@section('title', 'Kategori Yönetimi')
@section('page_title', 'Kategori Yönetimi')
@section('data-page', 'categories')

@section('content')

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-3">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
            <input type="text" placeholder="Kategori ara..." class="bg-white border border-slate-200 rounded-xl text-sm pl-10 pr-4 py-2.5 w-64 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm"/>
        </div>
    </div>
    <button onclick="showToast('Yeni kategori ekleme formu...', 'info')" class="bg-primary hover:bg-primary-dim text-white px-6 py-2.5 rounded-xl font-bold text-sm flex items-center gap-2 transition-all shadow-sm shadow-primary/10 active:scale-95">
        <span class="material-symbols-outlined text-[18px]">add</span>Yeni Kategori
    </button>
</div>

<div class="admin-card p-0 overflow-hidden shadow-sm">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Kategori Adı</th>
                    <th>Renk / İkon</th>
                    <th>Bağlı Kulüp Sayısı</th>
                    <th>Durum</th>
                    <th class="text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td><span class="font-semibold text-slate-800">{{ $category->name }}</span></td>
                    <td>
                        <div class="flex items-center gap-2">
                            @if($category->color)
                                <span class="w-4 h-4 rounded-full shadow-sm" style="background-color: {{ $category->color }}"></span>
                            @endif
                            <span class="text-sm text-slate-500 font-medium">{{ $category->icon ?? '—' }}</span>
                        </div>
                    </td>
                    <td><span class="font-semibold text-slate-600">{{ $category->clubs_count }}</span></td>
                    <td><span class="badge badge-success shadow-sm">Aktif</span></td>
                    <td class="text-right">
                        <div class="flex items-center justify-end gap-1">
                            <button class="action-btn text-slate-400 hover:text-primary transition-colors"><span class="material-symbols-outlined text-[18px]">edit</span></button>
                            <form action="{{ route('admin.kategoriler.destroy', $category) }}" method="POST" onsubmit="return confirm('Bu kategoriyi silmek istediğinize emin misiniz?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="action-btn action-btn-danger text-slate-400"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-12 text-slate-400">
                        <span class="material-symbols-outlined text-[48px] block mb-2">category</span>
                        Henüz kategori bulunmuyor.
                    </td>
                </tr>
                @endforelse
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
        <h3 class="text-lg font-bold font-headline text-center text-slate-800 mb-2">Silmek istediğinize emin misiniz?</h3>
        <p class="text-sm text-slate-500 text-center mb-6">"<span id="delete-item-name"></span>" kategorisi silindiğinde bağlı kulüpler "Kategorisiz" olarak işaretlenecektir.</p>
        <div class="flex gap-3">
            <button onclick="hideDeleteModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all active:scale-95">İptal</button>
            <button onclick="showToast('Kategori silindi.', 'error'); hideDeleteModal()" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold text-sm transition-all active:scale-95">Sil</button>
        </div>
    </div>
</div>

@endsection
