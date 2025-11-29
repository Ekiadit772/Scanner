<?php

namespace App\Http\Controllers;

use App\Models\JenisLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JenisLayananController extends Controller
{
    public function index()
    {
        $jenis_layanan_all = JenisLayanan::all();
        return view('jenis_layanan.index', compact('jenis_layanan_all'));
    }

    public function getJenisLayanan()
    {
        $jenisLayanan = JenisLayanan::select('id', 'nama')->get();

        $data = $jenisLayanan->map(function ($jl, $index) {
            return [
                $index + 1,
                $jl->nama,
                $jl->id,
                null,
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
            'nama.required' => 'Nama jenis layanan wajib diisi.',
            'nama.max' => 'Nama jenis layanan maksimal 150 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        JenisLayanan::create([
            'nama' => $request->nama,
            'created_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis layanan berhasil ditambahkan.'
        ], 200);
    }

    public function edit($id)
    {
        $jenisLayanan = JenisLayanan::find($id);

        if (!$jenisLayanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data jenis layanan tidak ditemukan.'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'jenisLayanan' => $jenisLayanan,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => [
                'required',
                'string',
                'max:150',
            ],
        ], [
            'nama.required' => 'Nama jenis layanan wajib diisi.',
            'nama.max' => 'Nama jenis layanan maksimal 150 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $jenisLayanan = JenisLayanan::find($id);

        if (!$jenisLayanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jenis layanan tidak ditemukan.',
            ]);
        }

        $jenisLayanan->update([
            'nama' => $request->nama,
            'updated_by' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis layanan berhasil diperbarui!',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $jenisLayanan = JenisLayanan::find($id);

        if (!$jenisLayanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Jenis layanan tidak ditemukan.'
            ]);
        }

        // Simpan siapa yang menghapus
        $jenisLayanan->deleted_by = Auth::user()->name;
        $jenisLayanan->save();

        // Soft delete
        $jenisLayanan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis layanan berhasil dihapus.'
        ]);
    }
}
