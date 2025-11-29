<?php

namespace App\Http\Controllers;

use App\Models\PenyediaLayanan;
use App\Models\PeranPenyedia;
use App\Models\JenisPeran;
use App\Models\PerangkatDaerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class PenyediaLayananController extends Controller
{
    public function index()
    {
        $penyediaLayanan = PenyediaLayanan::with(['perangkatDaerah', 'peranPenyedia.jenisPeran'])->get();
        return view('penyedia_layanan.index', compact('penyediaLayanan'));
    }

    public function getPenyediaLayanan()
    {
        $penyediaLayanan = PenyediaLayanan::with('peranPenyedia', 'perangkatDaerah')->get();



        $data = $penyediaLayanan->map(function ($pl, $index) {
            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Penyedia Layanan;Edit');
            $canDelete = $currentUser->can('Penyedia Layanan;Hapus');
            $canView = $currentUser->can('Penyedia Layanan;Lihat');
            $hashedId = Hashids::encode($pl->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // EDIT BUTTON
            // =============================
            if ($canEdit) {
                $iconEdit = Blade::render('<x-icons.edit />');
                $buttons .= <<<HTML
                    <button type="button" onclick="editPenyediaLayanan('{$hashedId}')" class="text-blue-500 mr-2" x-tooltip="Edit">
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
                    <button type="button" onclick="deletePenyediaLayanan('{$hashedId}')" class="text-red-500 mr-2" x-tooltip="Hapus">
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
                <button type="button" class="text-gray-600" onclick="lihatPenyediaLayanan('{$hashedId}')" x-tooltip="View">
                    {$iconView}
                </button>
                HTML;
            }

            $buttons .= "</div>";

            return [
                $index + 1,
                $pl->perangkatDaerah->nama ?? '-',
                $pl->nama_bidang,
                $pl->id,
                $buttons,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function create()
    {
        $perangkat_daerah_all = PerangkatDaerah::all();
        $jenis_peran_all = JenisPeran::all();
        return view('penyedia_layanan.create', compact('perangkat_daerah_all', 'jenis_peran_all'));
    }

    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'perangkat_daerah_id' => 'required|exists:perangkat_daerah,id',
                'nama_bidang' => 'required|string|max:255',
                'personil' => 'required|array|min:1',
                'personil.*.nip' => 'required|string|max:50',
                'personil.*.nama' => 'required|string|max:500',
                'personil.*.jabatan' => 'required|string|max:500',
                'personil.*.id_peran' => 'required|exists:jenis_peran,id',
            ],
            [
                'perangkat_daerah_id.required' => 'Nama perangkat daerah wajib dipilih.',
                'perangkat_daerah_id.exists' => 'Perangkat daerah tidak valid.',
                'nama_bidang.required' => 'Nama bidang wajib diisi.',
                'nama_bidang.string' => 'Nama bidang harus berupa teks.',
                'personil.required' => 'Minimal satu personil harus diisi.',
                'personil.*.nip.required' => 'NIP personil wajib diisi.',
                'personil.*.nama.required' => 'Nama personil wajib diisi.',
                'personil.*.jabatan.required' => 'Jabatan personil wajib diisi.',
                'personil.*.id_peran.required' => 'Peran personil wajib dipilih.',
                'personil.*.id_peran.exists' => 'Peran personil tidak valid.',
            ]
        );


        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }


        // Simpan data utama penyedia layanan
        $penyedia = PenyediaLayanan::create([
            'perangkat_daerah_id' => $request->perangkat_daerah_id,
            'nama_bidang' => $request->nama_bidang,
            'created_by' => Auth::user()->name ?? 'system',
        ]);

        // Simpan data personil terkait
        foreach ($request->personil as $personil) {
            PeranPenyedia::create([
                'penyedia_layanan_id' => $penyedia->id,
                'jenis_peran_id' => $personil['id_peran'],
                'nip' => $personil['nip'] ?? null,
                'nama' => $personil['nama'],
                'jabatan' => $personil['jabatan'],
                'created_by' => Auth::user()->name ?? 'system',
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Penyedia Layanan berhasil ditambahkan.',
                'data' => $penyedia,
            ], 201);
        }

        return redirect()->route('penyedia-layanan.index')->with('success', 'Penyedia Layanan berhasil ditambahkan.');
    }

    public function show($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $penyedia = PenyediaLayanan::with(['peranPenyedia.jenisPeran', 'perangkatDaerah'])->findOrFail($id);
        $perangkat_daerah_all = PerangkatDaerah::all();
        $jenis_peran_all = JenisPeran::all();

        // Jika request dari AJAX (fetch)
        if (request()->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'penyedia' => $penyedia,
                    'perangkat_daerah_all' => $perangkat_daerah_all,
                    'jenis_peran_all' => $jenis_peran_all,
                ]
            ]);
        }

        return view('penyedia_layanan.show', compact('penyedia', 'perangkat_daerah_all', 'jenis_peran_all'));
    }

    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $penyedia = PenyediaLayanan::with(['peranPenyedia.jenisPeran', 'perangkatDaerah'])->findOrFail($id);
        $perangkat_daerah_all = PerangkatDaerah::all();
        $jenis_peran_all = JenisPeran::all();

        // Jika request dari AJAX (fetch)
        if (request()->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'penyedia' => $penyedia,
                    'perangkat_daerah_all' => $perangkat_daerah_all,
                    'jenis_peran_all' => $jenis_peran_all,
                ]
            ]);
        }

        return view('penyedia_layanan.edit', compact('penyedia', 'perangkat_daerah_all', 'jenis_peran_all'));
    }

    public function update(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $validator = Validator::make(
            $request->all(),
            [
                'perangkat_daerah_id' => 'required|exists:perangkat_daerah,id',
                'nama_bidang' => 'required|string|max:255',
                'personil' => 'required|array|min:1',
                'personil.*.nip' => 'required|string|max:50',
                'personil.*.nama' => 'required|string|max:500',
                'personil.*.jabatan' => 'required|string|max:500',
                'personil.*.id_peran' => 'required|exists:jenis_peran,id',
            ],
            [
                'perangkat_daerah_id.required' => 'Nama perangkat daerah wajib dipilih.',
                'perangkat_daerah_id.exists' => 'Perangkat daerah tidak valid.',
                'nama_bidang.required' => 'Nama bidang wajib diisi.',
                'nama_bidang.string' => 'Nama bidang harus berupa teks.',
                'personil.required' => 'Minimal satu personil harus diisi.',
                'personil.*.nip.required' => 'NIP personil wajib diisi.',
                'personil.*.nama.required' => 'Nama personil wajib diisi.',
                'personil.*.jabatan.required' => 'Jabatan personil wajib diisi.',
                'personil.*.id_peran.required' => 'Peran personil wajib dipilih.',
                'personil.*.id_peran.exists' => 'Peran personil tidak valid.',
            ]
        );


        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $penyedia = PenyediaLayanan::with('peranPenyedia')->findOrFail($id);
        $penyedia->update([
            'perangkat_daerah_id' => $request->perangkat_daerah_id,
            'nama_bidang' => $request->nama_bidang,
            'updated_by' => Auth::user()->name ?? 'system',
        ]);

        // Ambil personil lama dengan key berdasarkan kombinasi NIP dan peran
        $existingPersonil = $penyedia->peranPenyedia->keyBy(function ($p) {
            return ($p->nip ?? '') . '-' . $p->jenis_peran_id;
        });

        $newPersonilKeys = collect($request->personil)->map(function ($p) {
            return ($p['nip'] ?? '') . '-' . $p['id_peran'];
        });

        // Hapus personil yang sudah tidak ada di input baru
        foreach ($existingPersonil as $key => $old) {
            if (!$newPersonilKeys->contains($key)) {
                $old->delete();
            }
        }

        // Update atau tambahkan personil baru
        foreach ($request->personil as $personil) {
            $key = ($personil['nip'] ?? '') . '-' . $personil['id_peran'];

            if ($existingPersonil->has($key)) {
                // Jika sudah ada (berdasarkan NIP + peran), update nama-nya kalau berubah
                $old = $existingPersonil[$key];
                if ($existingPersonil->has($key)) {
                    $old = $existingPersonil[$key];

                    $dataUpdate = [];

                    if ($old->nama !== $personil['nama']) {
                        $dataUpdate['nama'] = $personil['nama'];
                    }

                    if ($old->jabatan !== $personil['jabatan']) {
                        $dataUpdate['jabatan'] = $personil['jabatan'];
                    }

                    if (!empty($dataUpdate)) {
                        $dataUpdate['updated_by'] = Auth::user()->name ?? 'system';
                        $old->update($dataUpdate);
                    }
                }
            } else {
                // Jika belum ada, tambahkan record baru
                PeranPenyedia::create([
                    'penyedia_layanan_id' => $penyedia->id,
                    'jenis_peran_id' => $personil['id_peran'],
                    'nip' => $personil['nip'] ?? null,
                    'nama' => $personil['nama'],
                    'jabatan' => $personil['jabatan'],
                    'created_by' => Auth::user()->name ?? 'system',
                ]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Penyedia Layanan berhasil diperbarui.',
                'data' => $penyedia->fresh('peranPenyedia'),
            ]);
        }

        return redirect()->route('penyedia-layanan.index')->with('success', 'Penyedia Layanan berhasil diperbarui.');
    }

    public function destroy($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $penyedia = PenyediaLayanan::findOrFail($id);

        // Simpan siapa yang menghapus
        $penyedia->deleted_by = Auth::user()->name ?? 'system';
        $penyedia->save();

        // Hapus relasi personil
        $penyedia->peranPenyedia()->update(['deleted_by' => Auth::user()->name ?? 'system']);
        $penyedia->peranPenyedia()->delete();

        // Soft delete penyedia layanan
        $penyedia->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Penyedia layanan berhasil dihapus.'
        ]);
    }
}
