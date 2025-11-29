<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;

class SatuanController extends Controller
{
    public function index()
    {
        $satuan_all = Satuan::all();
        return view('satuan.index', compact('satuan_all'));
    }

    public function getSatuan()
    {
        $satuan = Satuan::select('id', 'nama')->get();

        $data = $satuan->map(function ($sat, $index) {
            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Satuan;Edit');
            $canDelete = $currentUser->can('Satuan;Hapus');
            $hashedId = Hashids::encode($sat->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // EDIT BUTTON
            // =============================
            if ($canEdit) {
                $iconEdit = Blade::render('<x-icons.edit />');
                $buttons .= <<<HTML
                    <button type="button" onclick="editSatuan('{$hashedId}')" class="text-blue-500 mr-2" x-tooltip="Edit">
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
                    <button type="button" onclick="deleteSatuan('{$hashedId}')" class="text-red-500 mr-2" x-tooltip="Hapus">
                        {$iconDelete}
                    </button>
                    HTML;
            }

            $buttons .= "</div>";

            return [
                $index + 1,
                $sat->nama,
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
                'max:50',
                'unique:' . Satuan::class,
            ],
        ], [
            'nama.required' => 'Nama satuan wajib diisi.',
            'nama.unique' => 'Nama satuan sudah digunakan.',
            'nama.max' => 'Nama satuan maksimal 50 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        Satuan::create([
            'nama' => $request->nama,
            'created_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Satuan berhasil ditambahkan.'
        ], 200);
    }

    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $satuan = Satuan::find($id);

        if (!$satuan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Satuan tidak ditemukan.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'satuan' => $satuan,
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
                'max:50',
                Rule::unique('perangkat_daerah')->ignore($id)->whereNull('deleted_at'),
            ],
        ], [
            'nama.required' => 'Nama satuan wajib diisi.',
            'nama.unique' => 'Nama satuan sudah digunakan.',
            'nama.max' => 'Nama satuan maksimal 50 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $satuan = Satuan::find($id);

        if (!$satuan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Satuan tidak ditemukan.',
            ]);
        }

        $satuan->update([
            'nama' => $request->nama,
            'updated_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Satuan berhasil diperbarui!',
        ]);
    }

    public function destroy(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $satuan = Satuan::find($id);

        if (!$satuan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data satuan tidak ditemukan.'
            ]);
        }

        // Simpan siapa yang menghapus
        $satuan->deleted_by = Auth::user()->name;
        $satuan->save();

        // Soft delete
        $satuan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Satuan berhasil dihapus.'
        ]);
    }
}
