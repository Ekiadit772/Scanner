<?php

namespace App\Http\Controllers;

use App\Models\JenisPeran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Vinkla\Hashids\Facades\Hashids;

class JenisPeranController extends Controller
{
    public function index()
    {
        $jenis_peran_all = JenisPeran::all();
        return view('jenis_peran.index', compact('jenis_peran_all'));
    }

    public function getJenisPeran()
    {
        $jenisPeran = JenisPeran::select('id', 'nama', 'peran')->get();

        $data = $jenisPeran->map(function ($jp, $index) {
            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Jenis Peran;Edit');
            $canDelete = $currentUser->can('Jenis Peran;Hapus');
            $hashedId = Hashids::encode($jp->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // EDIT BUTTON
            // =============================
            if ($canEdit) {
                $iconEdit = Blade::render('<x-icons.edit />');
                $buttons .= <<<HTML
                    <button type="button" onclick="editJenisPeran('{$hashedId}')" class="text-blue-500 mr-2" x-tooltip="Edit">
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
                    <button type="button" onclick="deleteJenisPeran('{$hashedId}')" class="text-red-500 mr-2" x-tooltip="Hapus">
                        {$iconDelete}
                    </button>
                    HTML;
            }

            $buttons .= "</div>";

            return [
                $index + 1,
                $jp->nama,
                $jp->peran,
                $hashedId,
                $buttons,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:100'],
            'peran' => ['nullable'],
        ], [
            'nama.required' => 'Nama jenis peran wajib diisi.',
            'nama.max' => 'Nama jenis peran maksimal 100 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek apakah nama sudah ada (aktif atau soft deleted)
        $existing = JenisPeran::withTrashed()->where('nama', $request->nama)->first();

        if ($existing) {
            if ($existing->trashed()) {
                $existing->forceDelete();
            } else {
                return response()->json([
                    'status' => 'error',
                    'errors' => [
                        'nama' => ['Nama jenis peran sudah digunakan.']
                    ]
                ], 422);
            }
        }

        // Simpan data baru
        JenisPeran::create([
            'nama' => $request->nama,
            'peran' => $request->peran,
            'created_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis peran berhasil ditambahkan.'
        ], 200);
    }

    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $jenisPeran = JenisPeran::find($id);

        if (!$jenisPeran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jenis peran tidak ditemukan.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => ['jenisPeran' => $jenisPeran]
        ]);
    }

    public function update(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;
        
        $validator = Validator::make($request->all(), [
            'nama' => ['required', 'string', 'max:100'],
            'peran' => ['nullable'],
        ], [
            'nama.required' => 'Nama jenis peran wajib diisi.',
            'nama.max' => 'Nama jenis peran maksimal 100 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jenisPeran = JenisPeran::find($id);

        if (!$jenisPeran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jenis peran tidak ditemukan.',
            ]);
        }

        $sameName = JenisPeran::withTrashed()
            ->where('nama', $request->nama)
            ->where('id', '!=', $id)
            ->first();

        if ($sameName) {
            if ($sameName->trashed()) {
                $sameName->forceDelete();
            } else {
                return response()->json([
                    'status' => 'error',
                    'errors' => [
                        'nama' => ['Nama jenis peran sudah digunakan oleh data lain.']
                    ]
                ], 422);
            }
        }

        // Update data
        $jenisPeran->update([
            'nama' => $request->nama,
            'peran' => $request->peran,
            'updated_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis peran berhasil diperbarui!',
        ]);
    }

    public function destroy(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $jenisPeran = JenisPeran::find($id);

        if (!$jenisPeran) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data jenis peran tidak ditemukan.'
            ]);
        }

        // Simpan siapa yang menghapus
        $jenisPeran->deleted_by = Auth::user()->name;
        $jenisPeran->save();

        // Soft delete
        $jenisPeran->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis peran berhasil dihapus.'
        ]);
    }
}
