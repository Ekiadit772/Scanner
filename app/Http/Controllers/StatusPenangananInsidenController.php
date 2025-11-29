<?php

namespace App\Http\Controllers;

use App\Models\StatusPenangananInsiden;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class StatusPenangananInsidenController extends Controller
{
    public function index()
    {
        $status_penanganan_insiden_all = StatusPenangananInsiden::all();
        return view('status_penanganan_insiden.index', compact('status_penanganan_insiden_all'));
    }

    public function getStatusPenangananInsiden()
    {
        $status_penanganan_insiden = StatusPenangananInsiden::select('id', 'nama', 'keterangan')->get();

        $data = $status_penanganan_insiden->map(function ($kp, $index) {
            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Status Penanganan Insiden;Edit');
            $canDelete = $currentUser->can('Status Penanganan Insiden;Hapus');
            $hashedId = Hashids::encode($kp->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // EDIT BUTTON
            // =============================
            if ($canEdit) {
                $iconEdit = Blade::render('<x-icons.edit />');
                $buttons .= <<<HTML
                    <button type="button" onclick="editStatusPenangananInsiden('{$hashedId}')" class="text-blue-500 mr-2" x-tooltip="Edit">
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
                    <button type="button" onclick="deleteStatusPenangananInsiden('{$hashedId}')" class="text-red-500 mr-2" x-tooltip="Hapus">
                        {$iconDelete}
                    </button>
                    HTML;
            }

            $buttons .= "</div>";

            return [
                $index + 1,
                $kp->nama,
                $kp->keterangan,
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
            'nama.required' => 'Nama status penanganan insiden wajib diisi.',
            'nama.max' => 'Nama status penanganan insiden maksimal 150 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        StatusPenangananInsiden::create([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'created_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status penanganan insiden berhasil ditambahkan.'
        ], 200);
    }

    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $statusPenangananInsiden = StatusPenangananInsiden::find($id);

        if (!$statusPenangananInsiden) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data status penanganan insiden tidak ditemukan.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'statusPenangananInsiden' => $statusPenangananInsiden,
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
            'nama.required' => 'Nama status penanganan insiden wajib diisi.',
            'nama.max' => 'Nama status penanganan insiden maksimal 150 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $statusPenangananInsiden = StatusPenangananInsiden::find($id);

        if (!$statusPenangananInsiden) {
            return response()->json([
                'status' => 'error',
                'message' => 'Status penanganan insiden tidak ditemukan.',
            ]);
        }

        $statusPenangananInsiden->update([
            'nama' => $request->nama,
            'keterangan' => $request->keterangan,
            'updated_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status penanganan insiden berhasil diperbarui!',
        ]);
    }

    public function destroy(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;
        
        $statusPenangananInsiden = StatusPenangananInsiden::find($id);

        if (!$statusPenangananInsiden) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data status penanganan insiden tidak ditemukan.'
            ]);
        }

        // Simpan siapa yang menghapus
        $statusPenangananInsiden->deleted_by = Auth::user()->name;
        $statusPenangananInsiden->save();

        // Soft delete
        $statusPenangananInsiden->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Status penanganan insiden berhasil dihapus.'
        ]);
    }
}
