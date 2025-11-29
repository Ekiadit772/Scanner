<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerubahanLayanan;
use App\Models\PerubahanLayananPenutupan;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class PerubahanLayananPenutupanController extends Controller
{
    public function updateStatusPenutupan(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $messages = [
            'status.required' => 'Status permohonan wajib dipilih.',
            'status.in'       => 'Status tidak valid.',

            'tanggal_penyelesaian.required' => 'Tanggal penyelesaian wajib diisi.',

            'kesesuaian_hasil.required' => 'Kesesuaian hasil wajib dipilih.',
            'kesesuaian_penjelasan.required_if' => 'Penjelasan wajib diisi jika hasil tidak sesuai.',

            'dampak_spbe.required' => 'Dampak SPBE wajib dipilih.',
            'dampak_spbe_penjelasan.required_if' => 'Penjelasan wajib diisi jika dampak negatif.',

            'persetujuan_penyelesaian.required' => 'Persetujuan penyelesaian wajib dipilih.',
            'persetujuan_catatan.required_if' => 'Catatan wajib diisi jika tidak berhasil.',

            'kordinator_spbe.required' => 'Nama Koordinator SPBE wajib diisi.',
            'kordinator_jabatan.required' => 'Jabatan Koordinator SPBE wajib diisi.',
        ];


        $validator = Validator::make($request->all(), [

            'status' => 'required|string|in:Selesai,Ditolak',
            'perubahan_layanan_id' => 'required',
            'tanggal_penyelesaian'      => 'required|date',
            'kesesuaian_hasil'          => 'required|string|in:Sesuai,Tidak Sesuai',
            'kesesuaian_penjelasan'     => 'nullable|string|required_if:kesesuaian_hasil,Tidak Sesuai',
            'dampak_spbe'               => 'required|string|in:Positif,Netral,Negatif',
            'dampak_spbe_penjelasan'    => 'nullable|string|required_if:dampak_spbe,Negatif',
            'persetujuan_penyelesaian'  => 'required|string|in:Telah Selesai,Selesai Sebagian,Tidak Berhasil',
            'persetujuan_catatan'       => 'nullable|string|required_if:persetujuan_penyelesaian,Tidak Berhasil',
            'kordinator_spbe'           => 'required|string',
            'kordinator_jabatan'        => 'required|string',
            'keterangan'                => 'nullable|string',

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
                } elseif ($newStatusId == 13) {
                    $keterangan = "Permohonan sudah sampai tahap penutupan oleh " . auth()->user()->name;
                } elseif ($newStatusId == 21) {
                    $keterangan = "Pelaporan permohonan oleh " . auth()->user()->name;
                } elseif ($newStatusId == 14) {
                    $keterangan = $request->keterangan;
                }

                if ($newStatusId == 13) {

                    PerubahanLayananPenutupan::create([
                        'perubahan_layanan_id' => $perubahan->id,
                        'tanggal_penyelesaian' => $validated['tanggal_penyelesaian'],
                        'kesesuaian_hasil' => $validated['kesesuaian_hasil'],
                        'kesesuaian_penjelasan' => $validated['kesesuaian_penjelasan'],
                        'dampak_spbe' => $validated['dampak_spbe'],
                        'dampak_spbe_penjelasan' => $validated['dampak_spbe_penjelasan'],
                        'persetujuan_penyelesaian' => $validated['persetujuan_penyelesaian'],
                        'persetujuan_catatan' => $validated['persetujuan_catatan'],
                        'kordinator_spbe' => $validated['kordinator_spbe'],
                        'kordinator_jabatan' => $validated['kordinator_jabatan'],
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
