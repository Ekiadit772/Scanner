<?php

namespace App\Http\Controllers;

use App\Models\PerubahanLayanan;
use App\Models\PerubahanLayananPelaporan;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class PerubahanLayananPelaporanController extends Controller
{
    public function updateStatusPelaporan(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $messages = [
            'status.required' => 'Status permohonan wajib dipilih.',
            'status.in'       => 'Status tidak valid.',
        ];

        $validator = Validator::make($request->all(), [

            // Status
            'status'     => 'required|string|in:Dalam antrian,Disetujui,Pelaporan,Dilaksanakan,Penelusuran,Selesai,Ditolak',
            'keterangan' => 'nullable|string',

            'perubahan_layanan_id' => 'required',

            // Pelaporan
            'tim_pelaksana'           => 'required|string',
            'tanggal_rencana'         => 'required|string|in:Sesuai Rencana,Tidak Sesuai Rencana',
            'tanggal_mulai'           => 'required|date',
            'tanggal_selesai'         => 'required|date|after_or_equal:tanggal_mulai',

            'anggaran'                => 'required|string|in:Memadai,Tidak Memadai',
            'anggaran_catatan'        => 'nullable|string',

            'sumber_daya_lain'        => 'required|string|in:Tersedia,Tidak Tersedia',
            'sumber_daya_lain_catatan' => 'nullable|string',

            'komunikasi_perubahan'        => 'required|string|in:Dilaksanakan,Tidak Dilaksanakan',
            'komunikasi_perubahan_catatan' => 'nullable|string',

            'lainnya'                    => 'required|string|in:Dilaksanakan,Tidak Dilaksanakan',
            'lainnya_catatan'            => 'nullable|string',

            'langkah_implementasi'       => 'required|string',

            'status_pelaksanaan'         => 'required|string|in:Selesai,Parsial,Tertunda,Gagal',

            'catatan_khusus'             => 'nullable|string',

            'bukti_implementasi'         => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        return DB::transaction(function () use ($request, $validated, $id) {

            $perubahan = PerubahanLayanan::findOrFail($id);

            $statusIdMap = [
                'Dalam antrian' => 9,
                'Disetujui'     => 10,
                'Pelaporan'     => 21,
                'Dilaksanakan'  => 11,
                'Penelusuran'   => 12,
                'Selesai'       => 13,
                'Ditolak'       => 14,
            ];

            $newStatusId = $statusIdMap[$request->status];

            if ($perubahan->status_id != $newStatusId) {

                if ($request->status === 'Disetujui') {
                    $perubahan->no_permohonan = $request->no_permohonan;
                }

                $perubahan->status_id = $newStatusId;
                $perubahan->save();


                $keterangan = null;
                if ($newStatusId == 10) {
                    $keterangan = "Permohonan disetujui oleh " . auth()->user()->name;
                } elseif ($newStatusId == 11) {
                    $keterangan = "Permohonan sedang dilaksanakan oleh " . auth()->user()->name;
                } elseif ($newStatusId == 12) {
                    $keterangan = "Permohonan dalam penelusuran oleh " . auth()->user()->name;
                } elseif ($newStatusId == 21) {
                    $keterangan = "Pelaporan permohonan oleh " . auth()->user()->name;
                } elseif ($newStatusId == 14) {
                    $keterangan = $request->keterangan;
                }

                if ($newStatusId == 21) {
                    $path = null;
                    if ($request->hasFile('bukti_implementasi')) {
                        $path = $request->file('bukti_implementasi')->store('bukti-implementasi', 'public');
                    }

                    PerubahanLayananPelaporan::create([
                        'perubahan_layanan_id' => $perubahan->id,
                        'tim_pelaksana' => $validated['tim_pelaksana'],
                        'tanggal_mulai' => $validated['tanggal_mulai'],
                        'tanggal_selesai' => $validated['tanggal_selesai'],
                        'tanggal_rencana' => $validated['tanggal_rencana'],
                        'anggaran' => $validated['anggaran'],
                        'anggaran_catatan' => $validated['anggaran_catatan'],
                        'sumber_daya_lain' => $validated['sumber_daya_lain'],
                        'sumber_daya_lain_catatan' => $validated['sumber_daya_lain_catatan'],
                        'komunikasi_perubahan' => $validated['komunikasi_perubahan'],
                        'komunikasi_perubahan_catatan' => $validated['komunikasi_perubahan_catatan'],
                        'lainnya' => $validated['lainnya'],
                        'lainnya_catatan' => $validated['lainnya_catatan'],
                        'status_pelaksanaan' => $validated['status_pelaksanaan'],
                        'langkah_implementasi' => $validated['langkah_implementasi'],
                        'catatan_khusus' => $validated['catatan_khusus'],
                        'bukti_implementasi' => $path,
                        'created_by'            => auth()->user()->name,
                        'updated_by'            => auth()->user()->name,
                        'created_at'            => now(),
                        'updated_at'            => now(),
                    ]);
                }

                DB::table('perubahan_layanan_status')->insert([
                    'perubahan_layanan_id' => $perubahan->id,
                    'status_id'             => $newStatusId,
                    'keterangan'            => $keterangan,
                    'created_by'            => auth()->user()->name,
                    'updated_by'            => auth()->user()->name,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Status berhasil diubah menjadi {$request->status}"
            ]);
        });
    }
}
