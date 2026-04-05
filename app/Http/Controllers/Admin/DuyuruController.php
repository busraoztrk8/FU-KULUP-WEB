<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DuyuruController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = \App\Models\Announcement::latest()->get();
                return \Yajra\DataTables\Facades\DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('is_published', function($row) {
                        return $row->is_published ? '<span class="badge badge-success shadow-sm">Yayında</span>' : '<span class="badge badge-warning shadow-sm">Taslak</span>';
                    })
                    ->editColumn('published_at', function($row) {
                        if ($row->published_at) {
                            return \Carbon\Carbon::parse($row->published_at)->format('d.m.Y H:i');
                        }
                        return $row->created_at ? $row->created_at->format('d.m.Y') : '-';
                    })
                    ->addColumn('action', function($row) {
                        $btn = '<div class="flex items-center justify-end gap-1">';
                        $btn .= '<button onclick="showDuyuruDetay('.$row->id.')" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Görüntüle"><span class="material-symbols-outlined text-[18px]">visibility</span></button>';
                        $btn .= '<button onclick="showDuyuruDuzenle('.$row->id.')" class="action-btn text-slate-400 hover:text-primary transition-colors" title="Düzenle"><span class="material-symbols-outlined text-[18px]">edit</span></button>';
                        $btn .= '<button onclick="showDeleteModal('.$row->id.', \''.addslashes($row->title).'\')" class="action-btn action-btn-danger text-slate-400" title="Sil"><span class="material-symbols-outlined text-[18px]">delete</span></button>';
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->rawColumns(['is_published', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                \Log::error("DataTables Error: " . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return view('admin.duyurular');
    }
}
