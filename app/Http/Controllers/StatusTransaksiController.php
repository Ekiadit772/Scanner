<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusTransaksi;
use Illuminate\Support\Facades\DB;

class StatusTransaksiController extends Controller
{

    public function getByJenis(Request $request)
    {
        // Validasi parameter wajib
        $request->validate([
            'jenis' => 'required|string'
        ]);

        $jenis = $request->jenis;

        $statusIds = $this->getRangeId($jenis);

        if (!$statusIds) {
            return response()->json([
                'status' => false,
                'message' => 'Jenis transaksi tidak ditemukan'
            ], 404);
        }

        $data = DB::table('status_transaksi')
            ->whereIn('id', $statusIds)
            ->where('is_aktif', 1)
            ->select('id', DB::raw('nama_status as text'))
            ->get();

        return response()->json($data);
    }

    private function getRangeId($jenis)
    {
        // Mapping sesuai tabel Anda
        $mapping = [
            'manajemen_permintaan' => [1, 2, 3, 4, 5],
            'katalog_layanan'      => [6, 7, 8],
            'perubahan_layanan'    => [9, 10, 11, 12, 13, 14, 21], // tambahkan 21
            'insiden_layanan'      => [15, 16, 17, 18, 19, 20],
        ];

        return $mapping[$jenis] ?? null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(StatusTransaksi $statusTransaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatusTransaksi $statusTransaksi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StatusTransaksi $statusTransaksi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StatusTransaksi $statusTransaksi)
    {
        //
    }
}
