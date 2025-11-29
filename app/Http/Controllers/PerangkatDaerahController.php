<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PerangkatDaerah;
use Illuminate\Validation\Rule;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PerangkatDaerahController extends Controller
{
    public function index()
    {
        $perangkat_daerah_all = PerangkatDaerah::all();
        return view('perangkat_daerah.index', compact('perangkat_daerah_all'));
    }

    public function getPerangkatDaerah()
    {
        $perangkatDaerah = PerangkatDaerah::select('id', 'kode', 'nama', 'kadis_nama', 'kadis_nip')->get();

        $data = $perangkatDaerah->map(function ($perda, $index) {
            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Perangkat Daerah;Edit');
            $canDelete = $currentUser->can('Perangkat Daerah;Hapus');
            $canView   = $currentUser->can('Perangkat Daerah;Lihat');
            $hashedId = Hashids::encode($perda->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // EDIT BUTTON
            // =============================
            if ($canEdit) {
                $iconEdit = Blade::render('<x-icons.edit />');
                $buttons .= <<<HTML
                    <button type="button" onclick="editPerangkatDaerah('{$hashedId}')" class="text-blue-500 mr-2" x-tooltip="Edit">
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
                    <button type="button" onclick="deletePerangkatDaerah('{$hashedId}')" class="text-red-500 mr-2" x-tooltip="Hapus">
                        {$iconDelete}
                    </button>
                    HTML;
            }

            // =============================
            // VIEW BUTTON
            // =============================
            if ($canView) {
                $iconView = Blade::render('<x-icons.view/>');
                $buttons .= <<<HTML
                <button type="button" onclick="detailPerangkatDaerah('{$hashedId}')" class="text-gray-600" x-tooltip="View">
                    {$iconView}
                </button>
                HTML;
            }

            $buttons .= "</div>";

            return [
                $index + 1,
                $perda->kode,
                $perda->nama,
                $perda->kadis_nama,
                $perda->kadis_nip,
                $hashedId,
                $buttons,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => [
                'required',
                'string',
                'max:30',
                'unique:' . PerangkatDaerah::class,
            ],
            'nama' => [
                'required',
                'string',
                'max:150',
                'unique:' . PerangkatDaerah::class,
            ],
            'kadis_nama' => 'required|string|max:150',
            'kadis_nip' => 'required|string|max:50',
            'kadis_tte' => 'nullable|file|mimes:png,jpg,jpeg,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $filePath = null;

        if ($request->hasFile('kadis_tte')) {

            $slugNamaDinas = Str::slug($request->nama, '_');
            $extension = $request->kadis_tte->getClientOriginalExtension();

            $fileName = "tte_kadis_{$slugNamaDinas}." . $extension;

            $filePath = $request->kadis_tte->storeAs(
                'tte_kadis',
                $fileName,
                'private'
            );
        }

        PerangkatDaerah::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kadis_nama' => $request->kadis_nama,
            'kadis_nip' => $request->kadis_nip,
            'kadis_tte' => $filePath,
            'created_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Perangkat daerah berhasil ditambahkan.'
        ], 200);
    }


    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $perangkatDaerah = PerangkatDaerah::find($id);

        if (!$perangkatDaerah) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data perangkat daerah tidak ditemukan.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'perangkatDaerah' => $perangkatDaerah,
            ]
        ]);
    }

    public function viewTTE($filename)
    {
        $path = storage_path("app/private/tte_kadis/" . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        $mime = mime_content_type($path);

        return response()->file($path, [
            'Content-Type' => $mime
        ]);
    }

    public function update(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $validator = Validator::make($request->all(), [
            'kode' => [
                'required',
                'string',
                'max:30',
                Rule::unique('perangkat_daerah')->ignore($id)->whereNull('deleted_at'),
            ],
            'nama' => [
                'required',
                'string',
                'max:150',
                Rule::unique('perangkat_daerah')->ignore($id)->whereNull('deleted_at'),
            ],
            'kadis_nama' => 'required|string|max:150',
            'kadis_nip' => 'required|string|max:50',
            'kadis_tte' => 'nullable|file|mimes:png,jpg,jpeg,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $perangkatDaerah = PerangkatDaerah::find($id);

        if (!$perangkatDaerah) {
            return response()->json([
                'status' => 'error',
                'message' => 'Perangkat daerah tidak ditemukan.',
            ]);
        }

        if ($request->hasFile('kadis_tte')) {

            // Jika ada file lama → hapus dulu
            if (!empty($perangkatDaerah->kadis_tte)) {
                if (Storage::disk('private')->exists($perangkatDaerah->kadis_tte)) {
                    Storage::disk('private')->delete($perangkatDaerah->kadis_tte);
                }
            }

            // Upload file baru
            $slugNamaDinas = Str::slug($request->nama, '_');
            $extension = $request->kadis_tte->getClientOriginalExtension();

            $fileName = "tte_kadis_{$slugNamaDinas}." . $extension;

            $filePath = $request->kadis_tte->storeAs(
                'tte_kadis',
                $fileName,
                'private'
            );

            $perangkatDaerah->kadis_tte = $filePath;
        }

        $perangkatDaerah->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kadis_nama' => $request->kadis_nama,
            'kadis_nip' => $request->kadis_nip,
            'updated_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Perangkat daerah berhasil diperbarui!',
        ]);
    }

    public function destroy(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $perangkatDaerah = PerangkatDaerah::find($id);

        if (!$perangkatDaerah) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data perangkat daerah tidak ditemukan.'
            ]);
        }

        // Simpan siapa yang menghapus
        $perangkatDaerah->deleted_by = Auth::user()->name;
        $perangkatDaerah->save();

        // Soft delete
        $perangkatDaerah->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Perangkat daerah berhasil dihapus.'
        ]);
    }
}
