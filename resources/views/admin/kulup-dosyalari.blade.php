@extends('layouts.admin')

@section('title', e($club->name) . ' - Dosyalar')
@section('page_title', isset($club) ? e($club->name) . ' - Dosyalar' : 'Kulüp Dosyaları')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 font-headline">Kulüp Dosyaları</h2>
        <p class="text-slate-500 text-sm mt-1">Kulübünüze ait evrak, PDF ve diğer dosyaları yönetin.</p>
    </div>
    
    @if(isset($club))
    <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="px-5 py-2.5 bg-primary text-white rounded-xl font-bold flex items-center gap-2 transition-all hover:bg-primary-dim active:scale-95 shadow-lg shadow-primary/20">
        <span class="material-symbols-outlined text-[20px]">upload_file</span>
        Yeni Dosya Yükle
    </button>
    @endif
</div>

@if(!isset($club))
    <!-- Admin view: Select a club -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($clubs as $c)
        <a href="{{ route('admin.kulupler.dosyalar', ['club_id' => $c->id]) }}" class="admin-card hover:border-primary/30 transition-all group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center group-hover:bg-primary/10 transition-colors">
                    <span class="material-symbols-outlined text-slate-400 group-hover:text-primary">folder</span>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800">{{ $c->name }}</h4>
                    <p class="text-xs text-slate-400">{{ $c->documents()->count() }} Dosya</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
@else
    <!-- List of documents for a specific club -->
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Dosya Adı</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Tür</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Boyut</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Yüklenme Tarihi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($documents as $doc)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                                    @php
                                        $icon = 'description';
                                        if(str_contains($doc->file_type, 'image')) $icon = 'image';
                                        elseif(str_contains($doc->file_type, 'pdf')) $icon = 'picture_as_pdf';
                                        elseif(str_contains($doc->file_type, 'word')) $icon = 'news';
                                        elseif(str_contains($doc->file_type, 'sheet')) $icon = 'table_chart';
                                    @endphp
                                    <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                                </div>
                                <span class="font-semibold text-slate-700 truncate max-w-[200px]" title="{{ $doc->file_name }}">{{ $doc->file_name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $doc->file_type }}</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ number_format($doc->file_size / 1024 / 1024, 2) }} MB</td>
                        <td class="px-6 py-4 text-sm text-slate-500">{{ $doc->created_at->format('d.m.Y H:i') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-100 transition-colors" title="Görüntüle">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </a>
                                <form action="{{ route('admin.kulupler.dosyalar.destroy', $doc) }}" method="POST" onsubmit="return confirm('Bu dosyayı kalıcı olarak silmek istediğinize emin misiniz?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition-colors" title="Sil">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-[48px] mb-2 opacity-20">inventory_2</span>
                            <p>Henüz dosya yüklenmedi.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($documents->hasPages())
        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
            {{ $documents->links() }}
        </div>
        @endif
    </div>
@endif

<!-- Upload Modal -->
@if(isset($club))
<div id="uploadModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">upload</span>
                Dosya Yükle
            </h3>
            <button onclick="document.getElementById('uploadModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.kulupler.dosyalar.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <input type="hidden" name="club_id" value="{{ $club->id }}">
            
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-2">Dosya Seçin</label>
                <div class="relative group">
                    <input type="file" name="file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center transition-all group-hover:border-primary/50 group-hover:bg-primary/[0.02]">
                        <span class="material-symbols-outlined text-[32px] text-slate-300 group-hover:text-primary mb-2 transition-colors">cloud_upload</span>
                        <p class="text-xs text-slate-500 font-medium" id="fileNameDisp">Dosyayı buraya bırakın veya tıklayın</p>
                        <p class="text-[10px] text-slate-400 mt-1 italic">Maksimum 20MB (PDF, PNG, JPG, DOCX vb.)</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="flex-1 px-4 py-2.5 bg-slate-50 text-slate-600 rounded-xl font-bold hover:bg-slate-100 transition-all">İptal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-primary text-white rounded-xl font-bold hover:bg-primary-dim transition-all shadow-lg shadow-primary/20">Yüklemeyi Başlat</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.querySelector('input[name="file"]').addEventListener('change', function(e) {
        const fileName = e.target.files[0] ? e.target.files[0].name : "Dosya seçilmedi";
        document.getElementById('fileNameDisp').textContent = fileName;
        document.getElementById('fileNameDisp').classList.add('text-primary', 'font-bold');
    });
</script>
@endif

@endsection
