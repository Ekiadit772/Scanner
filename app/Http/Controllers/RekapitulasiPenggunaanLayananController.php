<?php

namespace App\Http\Controllers;

use App\Models\KatalogLayanan;
use App\Models\KelompokLayanan;
use App\Models\Layanan;
use App\Models\PenyediaLayanan;
use App\Models\PerangkatDaerah;
use App\Models\PermintaanLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapitulasiPenggunaanLayananController extends Controller
{
    public function index()
    {
        $kelLayanans = KelompokLayanan::all();
        return view('rekapitulasi_penggunaan_layanan.index', compact('kelLayanans'));
    }

    public function getLayanan(Request $request)
    {
        $query = KatalogLayanan::withCount([
            'permintaanLayanan as draft_count' => function ($query) {
                $query->where('status_id', 0);
            },
            'permintaanLayanan as approved_count' => function ($query) {
                $query->where('status_id', 1);
            },
        ])->with(['penyediaLayanan.perangkatDaerah', 'kelompokLayanan']);

        // Filter berdasarkan kelompok layanan (jika ada)
        if ($request->filled('kelompok_id')) {
            $query->where('kelompok_layanan_id', $request->kelompok_id);
        }

        // Filter berdasarkan penyedia layanan (OPD penyedia)
        if ($request->filled('opd_penyedia_id')) {
            $query->whereHas('penyediaLayanan.perangkatDaerah', function ($q) use ($request) {
                $q->where('id', $request->opd_penyedia_id);
            });
        }

        // Filter berdasarkan OPD pengusul
        if ($request->filled('opd_pengusul_id')) {
            $query->whereHas('manajemenPermintaan.perangkatDaerah', function ($q) use ($request) {
                $q->where('id', $request->opd_pengusul_id);
            });
        }

        // Search berdasarkan nama layanan
        if ($request->filled('search')) {
            $query->where('nama', 'LIKE', "%{$request->search}%");
        }

        $layanan = $query->get();

        $data = $layanan->map(function ($l, $index) {
            return [
                'no' => $index + 1,
                'kelompok_layanan' => $l->kelompokLayanan->nama ?? '-',
                'nama_layanan' => $l->nama ?? '-',
                'penyedia_layanan' => $l->penyediaLayanan->perangkatDaerah->nama ?? '-',
                'total_pengajuan' => "{$l->draft_count} Draft <br> {$l->approved_count} Approved",
                'id' => $l->id,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function getPengajuanByLayanan(Request $request, $layanan_id)
    {
        $perPage = $request->get('per_page', 5); // default 5 per halaman
        $permintaan = PermintaanLayanan::with('perangkatDaerah')
            ->where('layanan_id', $layanan_id)
            ->orderBy('tanggal', 'asc')
            ->paginate($perPage);

        $data = $permintaan->getCollection()->map(function ($p, $index) use ($permintaan) {
            $no_permohonan = $p->no_permohonan ?? '-';
            $tanggal = $p->tanggal ? date('d M Y', strtotime($p->tanggal)) : '-';
            $nama_pemohon = $p->nama_pemohon ?? '-';
            $nip = $p->nip ?? '-';
            $unit_kerja = $p->unit_kerja ?? '-';
            $perangkat_daerah = $p->perangkatDaerah->nama ?? '-';
            $deskripsi = $p->deskripsi_spek ?? '-';

            return [
                'no' => $permintaan->firstItem() + $index,
                'nomor_permohonan' => "{$no_permohonan}<br>({$tanggal})",
                'nama_pemohon' => "{$nama_pemohon}<br>{$perangkat_daerah}<br>{$nip}<br>Unit Kerja: {$unit_kerja}",
                'deskripsi_spek' => $deskripsi,
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                'total' => $permintaan->total(),
                'current_page' => $permintaan->currentPage(),
                'last_page' => $permintaan->lastPage(),
                'per_page' => $permintaan->perPage(),
            ]
        ]);
    }

    public function getPerangkatDaerah(Request $request)
    {
        $query = PerangkatDaerah::query();

        if ($request->search) {
            $query->where('nama', 'LIKE', "%{$request->search}%");
        }

        $data = $query->limit(20)->get();

        return response()->json(
            $data->map(fn($row) => [
                'id' => $row->id,
                'text' => $row->nama
            ])
        );
    }

    public function getPenyediaLayanan(Request $request)
    {
        $query = PerangkatDaerah::query();

        if ($request->search) {
            $query->where('nama', 'LIKE', "%{$request->search}%");
        }

        $data = $query->limit(20)->get();

        return response()->json(
            $data->map(fn($row) => [
                'id' => $row->id,
                'text' => $row->nama
            ])
        );
    }
}
