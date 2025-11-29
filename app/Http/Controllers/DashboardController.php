<?php

namespace App\Http\Controllers;

use App\Models\InsidenLayanan;
use App\Models\KatalogLayanan;
use App\Models\KelompokLayanan;
use App\Models\Layanan;
use App\Models\PenyediaLayanan;
use App\Models\PermintaanLayanan;
use App\Models\PerubahanLayanan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        return view('dashboard.index', compact('data'));
    }

    public function cardsData()
    {
        return response()->json([
            'layanan_tersedia' => KatalogLayanan::count(),
            'pengajuan' => PermintaanLayanan::distinct('permintaan_layanan.id')->count('id'),

            'dalam_antrian' => PermintaanLayanan::whereHas('status', function ($q) {
                $q->where('nama_status', 'Dalam Antrian');
            })
                ->distinct('permintaan_layanan.id')
                ->count('id'),

            'verifikasi' => PermintaanLayanan::whereHas('status', function ($q) {
                $q->where('nama_status', 'verifikasi');
            })
                ->distinct('permintaan_layanan.id')
                ->count('id'),

            'proses' => PermintaanLayanan::whereHas('status', function ($q) {
                $q->where('nama_status', 'proses');
            })
                ->distinct('permintaan_layanan.id')
                ->count('id'),

            'ditolak' => PermintaanLayanan::whereHas('status', function ($q) {
                $q->where('nama_status', 'ditolak');
            })
                ->distinct('permintaan_layanan.id')
                ->count('id'),

            'selesai' => PermintaanLayanan::whereHas('status', function ($q) {
                $q->where('nama_status', 'selesai');
            })
                ->distinct('permintaan_layanan.id')
                ->count('id'),
        ]);
    }

    public function chartData()
    {
        $data = KelompokLayanan::withCount('layanan') // hitung relasi layanan
            ->get(['id', 'nama']);

        // Format sesuai frontend
        $result = $data->map(function ($item) {
            return [
                'nama' => $item->nama,
                'total' => $item->layanan_count, // jumlah layanan di kelompok ini
            ];
        });

        return response()->json($result);
    }

    public function layananByKelompok($kelompok)
    {
        $layanan = KatalogLayanan::with(['penyediaLayanan', 'kelompokLayanan'])
            ->whereHas('kelompokLayanan', function ($q) use ($kelompok) {
                $q->where('nama', $kelompok);
            })
            ->select('id', 'nama', 'penyedia_layanan_id', 'kelompok_layanan_id')
            ->get();

        $result = $layanan->map(function ($item) {
            return [
                'nama_layanan' => $item->nama,
                'penyedia' => $item->penyediaLayanan->perangkatDaerah->nama ?? '-',
            ];
        });

        return response()->json($result);
    }

    public function statusChartData()
    {
        $data = PermintaanLayanan::with('status')
            ->selectRaw('status_id, COUNT(DISTINCT(permintaan_layanan.id)) as total')
            ->groupBy('status_id')
            ->get();

        $result = $data->map(function ($item) {
            return [
                'status' => $item->status->nama_status ?? 'Tidak Diketahui',
                'total' => $item->total,
            ];
        });

        return response()->json($result);
    }

    public function permintaanByStatus($status)
    {
        $data = PermintaanLayanan::with(['layanan', 'penyediaLayanan'])
            ->whereHas('status', function ($q) use ($status) {
                $q->where('nama_status', $status);
            })
            ->selectRaw('layanan_id, penyedia_layanan_id, COUNT(*) as total')
            ->groupBy('layanan_id', 'penyedia_layanan_id')
            ->get();

        return response()->json(
            $data->map(fn($item) => [
                'nama_layanan' => $item->layanan->nama ?? '-',
                'penyedia' => $item->penyediaLayanan->perangkatDaerah->nama ?? '-',
                'jumlah' => $item->total,
            ])
        );
    }
    
    public function perubahanChartData()
    {
        $data = PerubahanLayanan::with('status')
            ->selectRaw('status_id, COUNT(DISTINCT(perubahan_layanan.id)) as total')
            ->groupBy('status_id')
            ->get();

        $result = $data->map(function ($item) {
            return [
                'status' => $item->status->nama_status ?? 'Tidak Diketahui',
                'total' => $item->total,
            ];
        });

        return response()->json($result);
    }

    public function perubahanByStatus($status)
    {
        $data = PerubahanLayanan::with(['layanan', 'penyediaLayanan'])
            ->whereHas('status', function ($q) use ($status) {
                $q->where('nama_status', $status);
            })
            ->selectRaw('layanan_id, penyedia_layanan_id, COUNT(*) as total')
            ->groupBy('layanan_id', 'penyedia_layanan_id')
            ->get();

        return response()->json(
            $data->map(fn($item) => [
                'nama_layanan' => $item->layanan->nama ?? '-',
                'penyedia' => $item->penyediaLayanan->perangkatDaerah->nama ?? '-',
                'jumlah' => $item->total,
            ])
        );
    }
    
    public function insidenChartData()
    {
        $data = InsidenLayanan::with('status')
            ->selectRaw('status_id, COUNT(DISTINCT(insiden_layanan.id)) as total')
            ->groupBy('status_id')
            ->get();

        $result = $data->map(function ($item) {
            return [
                'status' => $item->status->nama_status ?? 'Tidak Diketahui',
                'total' => $item->total,
            ];
        });

        return response()->json($result);
    }

    public function insidenByStatus($status)
    {
        $data = InsidenLayanan::with(['layanan', 'penyediaLayanan'])
            ->whereHas('status', function ($q) use ($status) {
                $q->where('nama_status', $status);
            })
            ->selectRaw('layanan_id, penyedia_layanan_id, COUNT(*) as total')
            ->groupBy('layanan_id', 'penyedia_layanan_id')
            ->get();

        return response()->json(
            $data->map(fn($item) => [
                'nama_layanan' => $item->layanan->nama ?? '-',
                'penyedia' => $item->penyediaLayanan->perangkatDaerah->nama ?? '-',
                'jumlah' => $item->total,
            ])
        );
    }

    
}
