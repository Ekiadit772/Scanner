<?php

namespace App\Http\Controllers;

use App\Models\KatalogLayanan;
use App\Models\SyaratKak;
use Illuminate\Http\Request;

class RealisasiBelanjaTikController extends Controller
{
    public function index()
    {
        return view('realisasi_belanja_tik.index');
    }

    public function getList(Request $request)
    {
        try {
            $query = SyaratKak::with([
                'permintaanLayananSyarat.permintaanLayanan.perangkatDaerah',
                'insidenLayananSyarat.insidenLayanan.perangkatDaerah'
            ]);

            if ($request->search) {
                $query->where('judul', 'like', "%{$request->search}%");
            }

            if ($request->perangkat_daerah_id) {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('permintaanLayananSyarat.permintaanLayanan', function ($x) use ($request) {
                        $x->where('perangkat_daerah_pemohon_id', $request->perangkat_daerah_id);
                    });
                    $q->orWhereHas('insidenLayananSyarat.insidenLayanan', function ($x) use ($request) {
                        $x->where('perangkat_daerah_pemohon_id', $request->perangkat_daerah_id);
                    });
                });
            }

            if ($request->tahun) {
                $query->where('tahun', $request->tahun);
            }

            $data = $query->paginate($request->per_page ?? 10);

            $result = $data->map(function ($item, $index) {
                return [
                    // 'id' => $item->id,  yJs
                      'no' => $index + 1,
                    'perangkat_daerah' => $item->perangkatDaerah->nama ?? '-',
                    'judul' => $item->judul,
                    'tahun' => $item->tahun,
                    'jumlah_anggaran' => $item->jumlah_anggaran,
                    'sumber_anggaran' => $item->sumber_anggaran,
                    'nama_ppk' => $item->nama_ppk
                ];
            });

            return response()->json([
                'data' => $result,
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }

    public function summary()
    {
        return response()->json([
            'total_anggaran' => SyaratKak::sum('jumlah_anggaran'),
            //yJs 'total_layanan' => KatalogLayanan::count()
            'total_layanan' => SyaratKak::count()
        ]);
    }
}
