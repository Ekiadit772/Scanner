<?php

namespace App\Http\Controllers;

use App\Models\JenisInsiden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;

class JenisInsidenController extends Controller
{
    public function index()
    {
        $jenis_insiden_all = JenisInsiden::all();
        return view('jenis_insiden.index', compact('jenis_insiden_all'));
    }

    public function getJenisInsiden()
    {
        $jenisInsiden = JenisInsiden::select('id', 'nama')->get();

        $data = $jenisInsiden->map(function ($ji, $index) {
            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Jenis Insiden;Edit');
            $canDelete = $currentUser->can('Jenis Insiden;Hapus');
            $hashedId = Hashids::encode($ji->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // EDIT BUTTON
            // =============================
            if ($canEdit) {
                $iconEdit = Blade::render('<x-icons.edit />');
                $buttons .= <<<HTML
                    <button type="button" onclick="editJenisInsiden('{$hashedId}')" class="text-blue-500 mr-2" x-tooltip="Edit">
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
                    <button type="button" onclick="deleteJenisInsiden('{$hashedId}')" class="text-red-500 mr-2" x-tooltip="Hapus">
                        {$iconDelete}
                    </button>
                    HTML;
            }

            $buttons .= "</div>";

            return [
                $index + 1,
                $ji->nama,
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
                'max:150',
            ],
        ], [
            'nama.required' => 'Nama jenis insiden wajib diisi.',
            'nama.max' => 'Nama jenis insiden maksimal 150 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        JenisInsiden::create([
            'nama' => $request->nama,
            'created_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis insiden berhasil ditambahkan.'
        ], 200);
    }

    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $jenisInsiden = JenisInsiden::find($id);

        if (!$jenisInsiden) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data jenis insiden tidak ditemukan.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'jenisInsiden' => $jenisInsiden,
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
        ], [
            'nama.required' => 'Nama jenis insiden wajib diisi.',
            'nama.max' => 'Nama jenis insiden maksimal 150 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jenisInsiden = JenisInsiden::find($id);

        if (!$jenisInsiden) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jenis insiden tidak ditemukan.',
            ]);
        }

        $jenisInsiden->update([
            'nama' => $request->nama,
            'updated_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis insiden berhasil diperbarui!',
        ]);
    }

    public function destroy(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;
        
        $jenisInsiden = JenisInsiden::find($id);

        if (!$jenisInsiden) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Jenis insiden tidak ditemukan.'
            ]);
        }

        // Simpan siapa yang menghapus
        $jenisInsiden->deleted_by = Auth::user()->name;
        $jenisInsiden->save();

        // Soft delete
        $jenisInsiden->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis insiden berhasil dihapus.'
        ]);
    }
}
