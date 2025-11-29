<?php

namespace App\Http\Controllers;

use App\Models\JenisSyarat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class JenisSyaratController extends Controller
{
    public function index()
    {
        $jenis_syarat_all = JenisSyarat::all();
        return view('jenis_syarat.index', compact('jenis_syarat_all'));
    }

    public function getJenisSyarat()
    {
        $jenisSyarat = JenisSyarat::select('id', 'nama', 'kelompok', 'keterangan', 'is_internal', 'is_aktif', 'is_custom')->get();

        $data = $jenisSyarat->map(function ($js, $index) {
            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Jenis Syarat;Edit');
            $canDelete = $currentUser->can('Jenis Syarat;Hapus');
            $hashedId = Hashids::encode($js->id);

            $buttons = "<div class='flex items-center'>";

            if ($js->is_custom === 1) {
                // =============================
                // EDIT BUTTON
                // =============================
                if ($canEdit) {
                    $iconEdit = Blade::render('<x-icons.edit />');
                    $buttons .= <<<HTML
                    <button type="button" onclick="editJenisSyarat('{$hashedId}')" class="text-blue-500 mr-2" x-tooltip="Edit">
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
                    <button type="button" onclick="deleteJenisSyarat('{$hashedId}')" class="text-red-500 mr-2" x-tooltip="Hapus">
                        {$iconDelete}
                    </button>
                    HTML;
                }
            } else {
                $iconLock = Blade::render('<x-icons.lock />');
                $buttons .= <<<HTML
                    <button type="button" class="text-red-500 mr-2">
                        {$iconLock}
                    </button>
                    HTML;
            }

            $buttons .= "</div>";

            return [
                $hashedId,
                $js->is_custom,
                $index + 1,
                $js->nama,
                $js->kelompok,
                $js->keterangan ?? "-",
                $buttons
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
                'max:150',
            ],
            'kelompok' => [
                'required',
                'string',
                'max:100',
            ],
            'keterangan' => [
                'nullable',
                'string',
            ],
        ], [
            'nama.required' => 'Nama jenis syarat wajib diisi.',
            'nama.max' => 'Nama jenis syarat maksimal 150 karakter.',
            'kelompok.required' => 'Kelompok jenis syarat wajib diisi.',
            'kelompok.max' => 'Kelompok jenis syarat maksimal 100 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        JenisSyarat::create([
            'nama' => $request->nama,
            'kelompok' => $request->kelompok,
            'keterangan' => $request->keterangan,
            'is_internal' => 0,
            'is_aktif' => 1,
            'is_custom' => 1,
            'created_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis Syarat berhasil ditambahkan.'
        ], 200);
    }

    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $jenisSyarat = JenisSyarat::find($id);

        if (!$jenisSyarat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data jenis syarat tidak ditemukan.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'jenisSyarat' => $jenisSyarat,
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
                'max:150',
            ],
            'kelompok' => [
                'required',
                'string',
                'max:100',
            ],
            'keterangan' => [
                'nullable',
                'string',
            ],
        ], [
            'nama.required' => 'Nama jenis syarat wajib diisi.',
            'nama.max' => 'Nama jenis syarat maksimal 150 karakter.',
            'kelompok.required' => 'Kelompok jenis syarat wajib diisi.',
            'kelompok.max' => 'Kelompok jenis syarat maksimal 100 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jenisSyarat = JenisSyarat::find($id);

        if (!$jenisSyarat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jenis syarat tidak ditemukan.',
            ]);
        }

        $jenisSyarat->update([
            'nama' => $request->nama,
            'kelompok' => $request->kelompok,
            'keterangan' => $request->keterangan,
            'updated_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis syarat berhasil diperbarui!',
        ]);
    }

    public function destroy(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;
        
        $jenisSyarat = JenisSyarat::find($id);

        if (!$jenisSyarat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Jenis syarat tidak ditemukan.'
            ]);
        }

        // Simpan siapa yang menghapus
        $jenisSyarat->deleted_by = Auth::user()->name;
        $jenisSyarat->save();

        // Soft delete
        $jenisSyarat->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis syarat berhasil dihapus.'
        ]);
    }
}
