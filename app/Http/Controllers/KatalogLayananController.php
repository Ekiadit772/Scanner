<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use App\Models\Layanan;
use App\Models\LayananSla;
use App\Models\JenisSyarat;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;
use App\Models\LayananSyarat;
use App\Models\KatalogLayanan;
use App\Models\KelompokLayanan;
use App\Models\PenyediaLayanan;
use App\Models\PerangkatDaerah;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\KatalogLayananStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class KatalogLayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelLayanans = KelompokLayanan::all();
        $jenisLayanans= JenisLayanan::all();
        return view('katalog_layanan.index', compact('kelLayanans', 'jenisLayanans'));
    }

    public function getSummary(Request $request)
    {
        $summaryJenis = KatalogLayanan::select('jenis_layanan_id', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_layanan_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->jenis_layanan_id => $item->total
                ];
            });

        $summaryKelompok = KatalogLayanan::select('kelompok_layanan_id', DB::raw('COUNT(*) as total'))
            ->groupBy('kelompok_layanan_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->kelompok_layanan_id => $item->total
                ];
            });

        $summaryStatus = KatalogLayanan::select('status_id', DB::raw('COUNT(*) as total'))
            ->groupBy('status_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->status_id => $item->total
                ];
            });

        return response()->json([
            'layanan_permintaan' => $summaryJenis[1] ?? 0,
            'layanan_perubahan'  => $summaryJenis[2] ?? 0,
            'layanan_insiden'    => $summaryJenis[3] ?? 0,

            'data'          => $summaryKelompok[1] ?? 0,
            'aplikasi'      => $summaryKelompok[2] ?? 0,
            'infrastruktur' => $summaryKelompok[3] ?? 0,
            'suprastruktur' => $summaryKelompok[4] ?? 0,

            'dalam_antrian' => $summaryStatus[6] ?? 0,
            'verifikasi'    => $summaryStatus[7] ?? 0,
        ]);
    }

    public function verifikasi()
    {
        return view('katalog_layanan.verifikasi');
    }

    public function getKatalogLayanan(Request $request)
    {
        $opdAuth = Auth::user()->perangkat_daerah_id;
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $search = $request->get('search', null);

        $filterOpd = $request->get('opd', null);
        $filterBidang = $request->get('bidang', null);
        $filterKelompok = $request->get('kelompok', null);
        $filterJenis = $request->get('jenis', null);

        $query = KatalogLayanan::with(['perangkatDaerah', 'penyediaLayanan', 'kelompokLayanan', 'sla', 'syarat', 'status'])
            ->select(
                'id',
                'perangkat_daerah_id',
                'penyedia_layanan_id',
                'kelompok_layanan_id',
                'kode',
                'nama',
                'deskripsi',
                'status_id'
            );

        // Optional: batasi user non-admin hanya OPD sendiri
        // if ($opdAuth !== 1) {
        //     $query->where('perangkat_daerah_id', $opdAuth);
        // }

        if ($filterOpd) {
            $query->where('perangkat_daerah_id', $filterOpd);
        }

        if ($filterBidang) {
            $query->where('penyedia_layanan_id', $filterBidang);
        }

        if ($filterKelompok) {
            $query->where('kelompok_layanan_id', $filterKelompok);
        }

        if ($filterJenis) {
            $query->where('jenis_layanan_id', $filterJenis);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        $layanan = $query->paginate($perPage, ['*'], 'page', $page);

        $formatted = collect($layanan->items())->map(function ($item, $index) use ($page, $perPage) {
            $slaCount = LayananSla::where('katalog_layanan_id', $item->id)->count();
            $syaratCount = LayananSyarat::where('katalog_layanan_id', $item->id)->count();

            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Katalog Layanan;Edit');
            $canDelete = $currentUser->can('Katalog Layanan;Hapus');
            $canView = $currentUser->can('Katalog Layanan;Lihat Riwayat');

            $hashedId = Hashids::encode($item->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // EDIT BUTTON
            // =============================
            if ($canEdit) {
                $iconEdit = Blade::render('<x-icons.edit />');
                $editUrl = route('katalog-layanan.edit', $hashedId);
                $buttons .= <<<HTML
                    <a href="{$editUrl}" class="text-blue-500 mr-2" x-tooltip="Edit">
                        {$iconEdit}
                    </a>
                    HTML;
            }

            // =============================
            // View BUTTON
            // =============================
            if ($canView) {
                $iconView = Blade::render('<x-icons.view />');
                $showUrl = route('katalog-layanan.show', $hashedId);
                $buttons .= <<<HTML
                    <a href="{$showUrl}" class="text-gray-500 mr-2" x-tooltip="Detail">
                        {$iconView}
                    </a>
                    HTML;
            }

            $buttons .= "</div>";

            return [
                'no' => $index + 1,
                'perangkat_daerah' => $item->perangkatDaerah->nama ?? '-',
                'bidang' => $item->penyediaLayanan->nama_bidang ?? '-',
                'nama' => $item->nama ?? '-',
                'kode' => $item->kode ?? '-',
                'kelompok' => $item->kelompokLayanan->nama ?? '-',
                'deskripsi' => $item->deskripsi ?? '-',
                'status' => $item->status->nama_status ?? '-',
                'sla' => $slaCount,
                'syarat' => $syaratCount,
                'id' => $hashedId,
                'buttons' => $buttons,
            ];
        });

        return response()->json([
            'data' => $formatted,
            'total' => $layanan->total(),
            'perPage' => $layanan->perPage(),
            'current_page' => $layanan->currentPage(),
            'last_page' => $layanan->lastPage(),
        ]);
    }

    public function getByStatus($status)
    {
        $layanan = KatalogLayanan::with(['perangkatDaerah', 'penyediaLayanan', 'kelompokLayanan', 'sla', 'syarat.jenisSyarat'])
            ->select(
                'id',
                'perangkat_daerah_id',
                'penyedia_layanan_id',
                'kelompok_layanan_id',
                'kode',
                'nama',
                'deskripsi',
                'status_id'
            )
            ->where('status_id', $status)
            ->get();

        $data = $layanan->map(function ($item, $index) {
            $slaCount = LayananSla::where('katalog_layanan_id', $item->id)->count();
            $syaratCount = LayananSyarat::where('katalog_layanan_id', $item->id)->count();

            $currentUser = auth()->user();
            $canVerifikasi = $currentUser->can('Katalog Layanan;Verifikasi');

            $hashedId = Hashids::encode($item->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // View BUTTON
            // =============================
            if ($canVerifikasi) {
                $verifikasiUrl = route('katalog-layanan.preview', $hashedId);
                $buttons .= <<<HTML
                    <a href="{$verifikasiUrl}" class="btn btn-primary font-semibold px-3 py-1 rounded">
                        Pratinjau
                    </a>
                    HTML;
            }

            $buttons .= "</div>";

            return [
                'no' => $index + 1,
                'perangkat_daerah' => $item->perangkatDaerah->nama ?? '-',
                'bidang' => $item->penyediaLayanan->nama_bidang ?? '-',
                'nama' => $item->nama ?? '-',
                'kode' => $item->kode ?? '-',
                'kelompok' => $item->kelompokLayanan->nama ?? '-',
                'deskripsi' => $item->deskripsi ?? '-',
                'status' => $item->status,
                'sla' => $slaCount,
                'syarat' => $syaratCount,
                'id' => $hashedId,
                'buttons' => $buttons
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function getPerangkatDaerah(Request $request)
    {
        $query = PerangkatDaerah::query();

        if ($request->search) {
            $query->where('nama', 'LIKE', "%{$request->search}%");
        }

        $data = $query->limit(20)->get();

        return response()->json(
            $data->map(fn($row) => [
                'id' => $row->id,
                'text' => $row->nama
            ])
        );
    }

    public function getPenyediaLayanan(Request $request)
    {
        $query = PenyediaLayanan::where('perangkat_daerah_id', $request->perangkat_daerah_id);

        if ($request->search) {
            $query->where('nama_bidang', 'LIKE', "%{$request->search}%");
        }

        $data = $query->limit(20)->get();

        return response()->json(
            $data->map(fn($row) => [
                'id' => $row->id,
                'text' => $row->nama_bidang
            ])
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $data = ServiceCatalog::findOrFail($id);
        $opds = PerangkatDaerah::all();
        $kelLayanans = KelompokLayanan::all();
        $satuans = Satuan::select('nama')->get();
        $jenisSyaratAll = JenisSyarat::all();
        $jenisLayanan = JenisLayanan::all();
        return view('katalog_layanan.create', compact('opds', 'kelLayanans', 'satuans', 'jenisSyaratAll', 'jenisLayanan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('katalog_layanan', 'kode')->whereNull('deleted_at'),
            ],
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('katalog_layanan', 'nama')->whereNull('deleted_at'),
            ],
            'deskripsi' => 'nullable|string|max:1000',
            'perangkat_daerah_id' => 'required|exists:perangkat_daerah,id',
            'penyedia_layanan_id' => 'required|exists:penyedia_layanan,id',
            'kelompok_layanan_id' => 'required|exists:kelompok_layanan,id',
            'jenis_layanan_id' => 'required|exists:jenis_layanan,id',
            'sla' => 'required|json',
            'syarat' => 'required|json',
            'tahun' => 'required|integer|min:2000|max:2100',
        ], [
            'kode.required' => 'Kode layanan wajib diisi.',
            'kode.unique' => 'Kode layanan sudah terdaftar.',
            'nama.required' => 'Nama layanan wajib diisi.',
            'nama.unique' => 'Nama layanan sudah terdaftar.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $existing = KatalogLayanan::withTrashed()
            ->where('kode', $validated['kode'])
            ->orWhere('nama', $validated['nama'])
            ->first();

        if ($existing && $existing->trashed()) {
            $existing->forceDelete();
        }

        if ($existing && !$existing->trashed()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => [
                    'kode' => ['Kode layanan sudah terdaftar.'],
                    'nama' => ['Nama layanan sudah terdaftar.'],
                ]
            ], 422);
        }

        $slaList = json_decode($validated['sla'] ?? '[]', true) ?? [];
        $syaratList = json_decode($validated['syarat'] ?? '[]', true) ?? [];

        if (count($slaList) === 0) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => ['sla' => ['Minimal harus ada 1 SLA.']]
            ], 422);
        }

        if (count($syaratList) === 0) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => ['syarat' => ['Minimal harus ada 1 Syarat layanan.']]
            ], 422);
        }

        $layanan = KatalogLayanan::create([
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status_id' => 6,
            'tahun' => $validated['tahun'],
            'perangkat_daerah_id' => $validated['perangkat_daerah_id'],
            'penyedia_layanan_id' => $validated['penyedia_layanan_id'],
            'kelompok_layanan_id' => $validated['kelompok_layanan_id'],
            'jenis_layanan_id' => $validated['jenis_layanan_id'],
            'created_by' => auth()->user()->name,
        ]);

        DB::table('katalog_layanan_sla')->insert(
            collect($slaList)->map(fn($s) => [
                'katalog_layanan_id' => $layanan->id,
                'nama' => $s['nama'] ?? null,
                'deskripsi' => $s['deskripsi'] ?? null,
                'nilai' => $s['nilai'] ?? null,
                'satuan' => $s['satuan'] ?? null,
                'created_by' => auth()->user()->name,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray()
        );

        DB::table('katalog_layanan_syarat')->insert(
            collect($syaratList)->map(fn($s) => [
                'katalog_layanan_id' => $layanan->id,
                'jenis_syarat_id' => $s['jenis_syarat_id'] ?? null,
                // 'deskripsi' => $s['deskripsi'] ?? null,
                'created_by' => auth()->user()->name,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray()
        );

        KatalogLayananStatus::create([
            'katalog_layanan_id' => $layanan->id,
            'status_id' => 6,
            'keterangan' => 'Layanan dibuat',
            'created_by' => auth()->user()->name,
        ]);

        return response()->json([
            'message' => 'Data layanan berhasil disimpan.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $layanan = KatalogLayanan::with(['sla', 'syarat'])->findOrFail($id);
        $kelLayanans = KelompokLayanan::all();
        $satuans = Satuan::select('nama')->get();
        $jenisSyaratAll = JenisSyarat::all();
        $jenisLayanan = JenisLayanan::all();
        return view('katalog_layanan.detail', compact('layanan', 'kelLayanans', 'satuans', 'jenisSyaratAll', 'jenisLayanan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $layanan = KatalogLayanan::with(['sla', 'syarat.jenisSyarat'])->findOrFail($id);
        $kelLayanans = KelompokLayanan::all();
        $satuans = Satuan::select('nama')->get();
        $jenisSyaratAll = JenisSyarat::all();
        $jenisLayanan = JenisLayanan::all();

        $hashedAgain = Hashids::encode($layanan->id);

        return view('katalog_layanan.edit', compact('layanan', 'kelLayanans', 'satuans', 'jenisSyaratAll', 'jenisLayanan', 'hashedAgain'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $layanan = KatalogLayanan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode' => [
                'required',
                'string',
                'max:50',
                Rule::unique('katalog_layanan', 'kode')->ignore($id),
            ],
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('katalog_layanan', 'nama')->ignore($id),
            ],
            'deskripsi' => 'nullable|string|max:1000',
            'perangkat_daerah_id' => 'required|exists:perangkat_daerah,id',
            'penyedia_layanan_id' => 'required|exists:penyedia_layanan,id',
            'kelompok_layanan_id' => 'required|exists:kelompok_layanan,id',
            'jenis_layanan_id' => 'required|exists:jenis_layanan,id',
            'sla' => 'required|json',
            'syarat' => 'required|json',
            'tahun' => 'required|integer|min:2000|max:2100',
        ], [
            'kode.required' => 'Kode layanan wajib diisi.',
            'kode.unique' => 'Kode layanan sudah terdaftar.',
            'nama.required' => 'Nama layanan wajib diisi.',
            'nama.unique' => 'Nama layanan sudah terdaftar.',
            'perangkat_daerah_id.required' => 'Perangkat daerah wajib dipilih.',
            'penyedia_layanan_id.required' => 'Nama bidang wajib dipilih.',
            'kelompok_layanan_id.required' => 'Kelompok layanan wajib dipilih.',
            'jenis_layanan_id.required' => 'Jenis layanan wajib dipilih.',
            'sla.required' => 'Minimal harus ada 1 SLA.',
            'syarat.required' => 'Minimal harus ada 1 Syarat layanan.',
            'tahun.required' => 'Tahun layanan wajib diisi.',
            'tahun.integer' => 'Tahun layanan harus berupa angka.',
            'tahun.min' => 'Tahun layanan minimal adalah 2000.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $slaList = json_decode($validated['sla'] ?? '[]', true) ?? [];
        $syaratList = json_decode($validated['syarat'] ?? '[]', true) ?? [];

        if (count($slaList) === 0) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => ['sla' => ['Minimal harus ada 1 SLA.']]
            ], 422);
        }

        if (count($syaratList) === 0) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => ['syarat' => ['Minimal harus ada 1 Syarat layanan.']]
            ], 422);
        }

        $layanan->update([
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
            'tahun' => $validated['tahun'],
            'perangkat_daerah_id' => $validated['perangkat_daerah_id'],
            'penyedia_layanan_id' => $validated['penyedia_layanan_id'],
            'kelompok_layanan_id' => $validated['kelompok_layanan_id'],
            'jenis_layanan_id' => $validated['jenis_layanan_id'],
            'updated_by' => auth()->user()->name,
            'deskripsi' => $validated['deskripsi'] ?? null,
        ]);

        $slaData = json_decode($request->sla ?? '[]', true);
        $existingSlaIds = $layanan->sla()->pluck('id')->toArray();

        $newSlaIds = [];
        foreach ($slaData as $item) {
            if (isset($item['id']) && $item['id']) {
                $sla = LayananSla::find($item['id']);
                if ($sla) {
                    $sla->update([
                        'nama' => $item['nama'] ?? null,
                        'deskripsi' => $item['deskripsi'] ?? null,
                        'nilai' => $item['nilai'] ?? null,
                        'satuan' => $item['satuan'] ?? null,
                        'updated_by' => auth()->user()->name,
                        'updated_at' => now(),
                    ]);
                    $newSlaIds[] = $sla->id;
                }
            } else {
                $new = $layanan->sla()->create([
                    'nama' => $item['nama'] ?? null,
                    'deskripsi' => $item['deskripsi'] ?? null,
                    'nilai' => $item['nilai'] ?? null,
                    'satuan' => $item['satuan'] ?? null,
                    'created_by' => auth()->user()->name,
                    'created_at' => now(),
                ]);
                $newSlaIds[] = $new->id;
            }
        }

        $toDelete = array_diff($existingSlaIds, $newSlaIds);
        if (!empty($toDelete)) {
            LayananSla::whereIn('id', $toDelete)->update([
                'deleted_by' => auth()->user()->name,
                'updated_at' => now()
            ]);
            LayananSla::whereIn('id', $toDelete)->delete();
        }

        $syaratData = json_decode($request->syarat ?? '[]', true);
        $existingSyaratIds = $layanan->syarat()->pluck('id')->toArray();

        $newSyaratIds = [];
        foreach ($syaratData as $item) {
            if (isset($item['id']) && $item['id']) {
                $syarat = LayananSyarat::find($item['id']);
                if ($syarat) {
                    $syarat->update([
                        'jenis_syarat_id' => $item['jenis_syarat_id'] ?? null,
                        'deskripsi' => $item['deskripsi'] ?? null,
                        'updated_by' => auth()->user()->name,
                        'updated_at' => now(),
                    ]);
                    $newSyaratIds[] = $syarat->id;
                }
            } else {
                $new = $layanan->syarat()->create([
                    'jenis_syarat_id' => $item['jenis_syarat_id'] ?? null,
                    'deskripsi' => $item['deskripsi'] ?? null,
                    'created_by' => auth()->user()->name,
                    'created_at' => now(),
                ]);
                $newSyaratIds[] = $new->id;
            }
        }

        $toDeleteSyarat = array_diff($existingSyaratIds, $newSyaratIds);
        if (!empty($toDeleteSyarat)) {
            LayananSyarat::whereIn('id', $toDeleteSyarat)->update([
                'deleted_by' => auth()->user()->name,
                'updated_at' => now(),
            ]);
            LayananSyarat::whereIn('id', $toDeleteSyarat)->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Layanan berhasil diupdate'
        ]);
    }

    public function previewPage($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $layanan = KatalogLayanan::with([
            'perangkatDaerah',
            'penyediaLayanan',
            'kelompokLayanan',
            'jenisLayanan',
            'sla',
            'syarat.jenisSyarat',
        ])->findOrFail($id);

        $statusMap = [
            6 => 'dalam antrian',
            7 => 'verifikasi',
        ];

        $hashedAgain = Hashids::encode($layanan->id);

        $currentStatus = $statusMap[$layanan->status_id] ?? null;
        $jenisLayanan = JenisLayanan::all();
        $jenisSyaratAll = JenisSyarat::all();
        $kelLayanans = KelompokLayanan::all();
        return view('katalog_layanan.preview-page', compact('layanan', 'currentStatus', 'jenisLayanan', 'jenisSyaratAll', 'kelLayanans', 'hashedAgain'));
    }

    public function updateStatus(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $request->validate([
            'status' => 'required|string|in:verifikasi,proses,closing,selesai,ditolak',
            'keterangan' => 'nullable|string'
        ]);

        $permintaan = KatalogLayanan::findOrFail($id);

        $statusIdMap = [
            'verifikasi' => 7,
            'ditolak'    => 8,
        ];

        $newStatusId = $statusIdMap[$request->status];

        if ($permintaan->status_id != $newStatusId) {
            $permintaan->status_id = $newStatusId;
            $permintaan->save();

            $keterangan = null;
            if ($newStatusId == 1) {
                $keterangan = "Sudah diverifikasi oleh " . auth()->user()->name;
            } elseif ($newStatusId == 2) {
                $keterangan = "Sedang diproses oleh " . auth()->user()->name;
            } elseif ($newStatusId == 5) {
                $keterangan = $request->keterangan;
            }

            DB::table('katalog_layanan_status')->insert([
                'katalog_layanan_id' => $permintaan->id,
                'status_id'  => $newStatusId,
                'keterangan' => $request->keterangan_status ?? $keterangan,
                'created_by' => auth()->user()->name,
                'updated_by' => auth()->user()->name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Status berhasil diubah menjadi {$request->status}"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KatalogLayanan $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $id->deleted_by = Auth::user()->name;
        $id->save();
        $id->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan berhasil dihapus.',
        ]);
    }
}
