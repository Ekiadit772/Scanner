<?php

namespace App\Http\Controllers;

use App\Models\KelompokLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;

class KelompokLayananController extends Controller
{
    public function index()
    {
        $kelompok_layanan_all = KelompokLayanan::all();
        return view('kelompok_layanan.index', compact('kelompok_layanan_all'));
    }

    public function getKelompokLayanan()
    {
        $kelompokLayanan = KelompokLayanan::select('id', 'nama', 'deskripsi')->get();

        $data = $kelompokLayanan->map(function ($kl, $index) {
            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Kelompok Layanan;Edit');
            $canDelete = $currentUser->can('Kelompok Layanan;Hapus');
            $hashedId = Hashids::encode($kl->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // EDIT BUTTON
            // =============================
            if ($canEdit) {
                $iconEdit = Blade::render('<x-icons.edit />');
                $buttons .= <<<HTML
                    <button type="button" onclick="editKelompokLayanan('{$hashedId}')" class="text-blue-500 mr-2" x-tooltip="Edit">
                        {$iconEdit}
                    </button>
                    HTML;
            }

            // =============================
            // DELETE BUTTON
            // =============================
            if ($canDelete) {
                $iconDelete = Blade::render('<x-icons.delete />');
                $buttons .= <<<HTML
                    <button type="button" onclick="deleteKelompokLayanan('{$hashedId}')" class="text-red-500 mr-2" x-tooltip="Hapus">
                        {$iconDelete}
                    </button>
                    HTML;
            }

            $buttons .= "</div>";

            return [
                $index + 1,
                $kl->nama,
                $kl->deskripsi,
                $hashedId,
                $buttons,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => [
                'required',
                'string',
                'max:100',
            ],
            'deskripsi' => [
                'nullable',
            ]
        ], [
            'nama.required' => 'Nama kelompok layanan wajib diisi.',
            'nama.max' => 'Nama kelompok layanan maksimal 100 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek apakah nama sudah ada (baik aktif maupun soft deleted)
        $existing = KelompokLayanan::withTrashed()->where('nama', $request->nama)->first();

        if ($existing) {
            if ($existing->trashed()) {
                // Jika soft deleted, hapus permanen agar bisa dibuat baru
                $existing->forceDelete();
            } else {
                // Jika masih aktif, tolak karena duplikat
                return response()->json([
                    'status' => 'error',
                    'errors' => [
                        'nama' => ['Nama kelompok layanan sudah digunakan.']
                    ]
                ], 422);
            }
        }

        // Simpan data baru
        KelompokLayanan::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'created_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kelompok layanan berhasil ditambahkan.'
        ], 200);
    }


    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $kelompokLayanan = KelompokLayanan::find($id);

        if (!$kelompokLayanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kelompok layanan tidak ditemukan.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'kelompokLayanan' => $kelompokLayanan,
            ]
        ]);
    }

    public function update(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $validator = Validator::make($request->all(), [
            'nama' => [
                'required',
                'string',
                'max:100',
            ],
            'deskripsi' => [
                'nullable',
            ]
        ], [
            'nama.required' => 'Nama kelompok layanan wajib diisi.',
            'nama.max' => 'Nama kelompok layanan maksimal 100 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $kelompokLayanan = KelompokLayanan::find($id);

        if (!$kelompokLayanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kelompok layanan tidak ditemukan.',
            ]);
        }

        // Cek apakah nama digunakan oleh data lain (termasuk yang soft deleted)
        $sameName = KelompokLayanan::withTrashed()
            ->where('nama', $request->nama)
            ->where('id', '!=', $id)
            ->first();

        if ($sameName) {
            if ($sameName->trashed()) {
                // Jika data lain yang punya nama sama sudah soft delete, hapus permanen
                $sameName->forceDelete();
            } else {
                // Kalau masih aktif, tolak update
                return response()->json([
                    'status' => 'error',
                    'errors' => [
                        'nama' => ['Nama kelompok layanan sudah digunakan oleh data lain.']
                    ]
                ], 422);
            }
        }

        // Update data
        $kelompokLayanan->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'updated_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kelompok layanan berhasil diperbarui!',
        ]);
    }

    public function destroy(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $kelompokLayanan = KelompokLayanan::find($id);

        if (!$kelompokLayanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data kelompok layanan tidak ditemukan.'
            ]);
        }

        // Simpan siapa yang menghapus
        $kelompokLayanan->deleted_by = Auth::user()->name;
        $kelompokLayanan->save();

        // Soft delete
        $kelompokLayanan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kelompok layanan berhasil dihapus.'
        ]);
    }
}
