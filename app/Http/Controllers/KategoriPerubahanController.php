<?php

namespace App\Http\Controllers;

use App\Models\KategoriPerubahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;

class KategoriPerubahanController extends Controller
{
    public function index()
    {
        $kategori_perubahan_all = KategoriPerubahan::all();
        return view('kategori_perubahan.index', compact('kategori_perubahan_all'));
    }

    public function getKategoriPerubahan()
    {
        $kategoriPerubahan = KategoriPerubahan::select('id', 'nama')->get();

        $data = $kategoriPerubahan->map(function ($kp, $index) {
            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Kategori Perubahan;Edit');
            $canDelete = $currentUser->can('Kategori Perubahan;Hapus');
            $hashedId = Hashids::encode($kp->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // EDIT BUTTON
            // =============================
            if ($canEdit) {
                $iconEdit = Blade::render('<x-icons.edit />');
                $buttons .= <<<HTML
                    <button type="button" onclick="editKategoriPerubahan('{$hashedId}')" class="text-blue-500 mr-2" x-tooltip="Edit">
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
                    <button type="button" onclick="deleteKategoriPerubahan('{$hashedId}')" class="text-red-500 mr-2" x-tooltip="Hapus">
                        {$iconDelete}
                    </button>
                    HTML;
            }

            $buttons .= "</div>";

            return [
                $index + 1,
                $kp->nama,
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
            'nama.required' => 'Nama kategori perubahan wajib diisi.',
            'nama.max' => 'Nama kategori perubahan maksimal 150 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        KategoriPerubahan::create([
            'nama' => $request->nama,
            'created_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori perubahan berhasil ditambahkan.'
        ], 200);
    }

    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $kategoriPerubahan = KategoriPerubahan::find($id);

        if (!$kategoriPerubahan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data kategori perubahan tidak ditemukan.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'kategoriPerubahan' => $kategoriPerubahan,
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
            'nama.required' => 'Nama kategori perubahan wajib diisi.',
            'nama.max' => 'Nama kategori perubahan maksimal 150 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $kategoriPerubahan = KategoriPerubahan::find($id);

        if (!$kategoriPerubahan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kategori perubahan tidak ditemukan.',
            ]);
        }

        $kategoriPerubahan->update([
            'nama' => $request->nama,
            'updated_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori perubahan berhasil diperbarui!',
        ]);
    }

    public function destroy(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;
        
        $kategoriPerubahan = KategoriPerubahan::find($id);

        if (!$kategoriPerubahan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Kategori perubahan tidak ditemukan.'
            ]);
        }

        // Simpan siapa yang menghapus
        $kategoriPerubahan->deleted_by = Auth::user()->name;
        $kategoriPerubahan->save();

        // Soft delete
        $kategoriPerubahan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kategori perubahan berhasil dihapus.'
        ]);
    }
}
