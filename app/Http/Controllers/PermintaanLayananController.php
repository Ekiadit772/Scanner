<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LayananSyarat;
use App\Models\KatalogLayanan;
use App\Models\KatalogAplikasi;
use App\Models\PenyediaLayanan;
use App\Models\PerangkatDaerah;
use App\Models\StatusTransaksi;
use App\Models\PermintaanLayanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use App\Models\SyaratSuratRekomendasi;
use App\Services\Syarat\SyaratFactory;
use App\Models\PermintaanLayananDetail;
use App\Models\PermintaanLayananStatus;
use App\Models\PermintaanLayananSyarat;
use Illuminate\Support\Facades\Storage;
use App\Services\SuratRekomendasiService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PermintaanLayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activeStatus = StatusTransaksi::where('is_aktif', 1)
            ->orderBy('id', 'asc')
            ->get();
        return view('permintaan_layanan.index', compact('activeStatus'));
    }

    public function getSummaryPermintaanLayanan(Request $request)
    {
        $search     = $request->get('search');
        $pemohonId  = $request->get('pemohon_id');
        $penyediaId = $request->get('penyedia_layanan_id');
        $layananId  = $request->get('layanan_id');
        $statusId   = $request->get('status_id');

        $user = $request->user('sanctum');

        // base query
        $query = PermintaanLayanan::with('status')
            ->whereHas('status', fn($q) => $q->where('is_aktif', 1));

        // restriction user perangkat daerah
        if ((int) $user->perangkat_daerah_id !== -1) {
            $query->orWhere('perangkat_daerah_pemohon_id', $user->perangkat_daerah_id);
            //yjs
            $query->orWhere('perangkat_daerah_id', $user->perangkat_daerah_id);
        }

        // filtering
        if (!empty($pemohonId)) {
            //yjs$query->where('perangkat_daerah_pemohon_id', $pemohonId);
            $query->orWhere('perangkat_daerah_id', $pemohonId);
        }

        if (!empty($penyediaId)) {
            $query->where('penyedia_layanan_id', $penyediaId);
        }

        if (!empty($layananId)) {
            $query->where('layanan_id', $layananId);
        }

        if (!empty($statusId)) {
            $query->where('status_id', $statusId);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('no_permohonan', 'like', "%{$search}%")
                    ->orWhereHas('layanan', fn($sub) => $sub->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('perangkatPemohon', fn($sub) => $sub->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('perangkatDaerah', fn($sub) => $sub->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('penyediaLayanan', fn($sub) => $sub->where('nama_bidang', 'like', "%{$search}%"));
            });
        }

        // summary grouping
        $raw = $query->select('status_id', DB::raw('COUNT(*) as total'))
            ->groupBy('status_id')
            ->pluck('total', 'status_id');
        // dd($raw);
        return response()->json([
            'dalam_antrian' => $raw[1] ?? 0, // sesuaikan ID status ya
            'verifikasi'    => $raw[2] ?? 0,
            'proses'   => $raw[3] ?? 0,
            'selesai'   => $raw[4] ?? 0,
            'ditolak'   => $raw[5] ?? 0,
        ]);
    }

    public function verifikasi()
    {
        return view('permintaan_layanan.verifikasi');
    }

    public function getPermintaanLayanan(Request $request)
    {
        $perPage    = (int) $request->get('per_page', 10);
        $page       = (int) $request->get('page', 1);
        $search     = $request->get('search');
        $pemohonId  = $request->get('pemohon_id');              // id perangkat PEMOHON
        $penyediaId = $request->get('penyedia_layanan_id');     // id perangkat PENYEDIA (bidang)
        $layananId  = $request->get('layanan_id');
        $statusId   = $request->get('status_id');

        $user = $request->user('sanctum');

        $query = PermintaanLayanan::with([
            'perangkatDaerah',      // penyedia
            'penyediaLayanan',
            'layanan',
            'perangkatPemohon',     // pemohon
            'status',
        ])
            ->select(
                'id',
                'unit_kerja_pemohon',
                'no_permohonan',
                'no_antrian',
                'nama_pemohon',
                'nip_pemohon',
                'jabatan_pemohon',
                'tanggal',
                'perangkat_daerah_id',              // PENYEDIA
                'perangkat_daerah_pemohon_id',      // PEMOHON
                'penyedia_layanan_id',
                'layanan_id',
                'deskripsi_spek',
                'status_id'
            );

        if ($user->perangkat_daerah_id != -1) {
            $query->where(function ($q) use ($user) {
                $q->where('perangkat_daerah_pemohon_id', $user->perangkat_daerah_id);
            });
        }

        if (!empty($pemohonId)) {
            $query->where('perangkat_daerah_pemohon_id', $pemohonId);
        }

        if (!empty($penyediaId)) {
            $query->where('penyedia_layanan_id', $penyediaId);
        }

        if (!empty($layananId)) {
            $query->where('layanan_id', $layananId);
        }

        if (!empty($statusId)) {
            $query->where('status_id', $statusId);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('no_permohonan', 'like', "%{$search}%")
                    ->orWhereHas('layanan', fn($sub) => $sub->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('perangkatPemohon', fn($sub) => $sub->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('perangkatDaerah', fn($sub) => $sub->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('penyediaLayanan', fn($sub) => $sub->where('nama_bidang', 'like', "%{$search}%"));
            });
        }

        $data = $query->paginate($perPage, ['*'], 'page', $page);

        $formatted = collect($data->items())->map(function ($item, $index) use ($page, $perPage) {
            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Permintaan Layanan;Edit');
            $canDelete = $currentUser->can('Permintaan Layanan;Hapus');
            $canView = $currentUser->can('Permintaan Layanan;Lihat Riwayat');
            $userPerangkatId = $currentUser->perangkat_daerah_id;
            $userPerangkatNama = $currentUser->perangkatDaerah->nama ?? null;
            $hashedId = Hashids::encode($item->id);

            $statusIdVal = $item->status_id;
            $statusText = $item->status->nama_status;
            $pemohonPerangkatIdItem = $item->perangkat_daerah_pemohon_id;

            $isAdmin = $userPerangkatId === -1;
            $isOwnerRow = $pemohonPerangkatIdItem === $userPerangkatId;

            $buttons = "<div class='flex items-center'>";

            if ($canEdit && $this->isDalamAntrian($statusIdVal, $statusText) && ($isAdmin || $isOwnerRow)) {
                // =============================
                // EDIT BUTTON
                // =============================
                if ($canEdit) {
                    $iconEdit = Blade::render('<x-icons.edit />');
                    $editUrl = route('permintaan-layanan.edit', $hashedId);
                    $buttons .= <<<HTML
                    <a href="{$editUrl}" class="text-blue-500 mr-2" x-tooltip="Edit">
                        {$iconEdit}
                    </a>
                    HTML;
                }
            }

            if ($canView) {
                $iconView = Blade::render('<x-icons.view />');
                $showUrl = route('permintaan-layanan.show', $hashedId);
                $buttons .= <<<HTML
                    <a href="{$showUrl}" class="text-gray-500 mr-2" x-tooltip="Preview">
                        {$iconView}
                    </a>
                    HTML;
            }

            $buttons .= "</div>";

            return [
                'id'                           => $hashedId,
                'no'                           => (($page - 1) * $perPage) + ($index + 1),
                'no_permohonan'                => $item->no_permohonan,
                'no_antrian'                   => $item->no_antrian,
                'tanggal'                      => $item->tanggal,
                'pemohon_nama'                 => $item->perangkatPemohon->nama ?? '-',
                'nama_pemohon'                 => $item->nama_pemohon ?? '-',
                'nip'                          => $item->nip_pemohon ?? '-',
                'jabatan'                      => $item->jabatan_pemohon ?? '-',
                'unit_kerja'                   => $item->unit_kerja_pemohon ?? '-',
                'layanan_nama'                 => $item->layanan->nama ?? '-',
                'penyedia_nama'                => $item->penyediaLayanan->nama_bidang ?? '-',
                'deskripsi_spek'               => $item->deskripsi_spek ?? '-',
                'status_id'                    => $item->status_id ?? null,
                'status'                       => $item->status->nama_status ?? '-',
                'perangkat_daerah_pemohon_id'  => $item->perangkat_daerah_pemohon_id,
                'buttons' => $buttons,
            ];
        });

        return response()->json([
            'data'         => $formatted,
            'total'        => $data->total(),
            'perPage'      => $data->perPage(),
            'current_page' => $data->currentPage(),
            'last_page'    => $data->lastPage(),
        ]);
    }

    public function isDalamAntrian($statusId, $statusText)
    {
        $DRAFT_STATUS_IDS = [1];
        $DRAFT_STATUS_TEXTS = ['Dalam antrian', 'Verifikasi'];

        return in_array((int) $statusId, $DRAFT_STATUS_IDS, true) ||
            in_array(trim((string) $statusText), $DRAFT_STATUS_TEXTS, true);
    }

    public function getPemohonDistinct(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $rows = \App\Models\PerangkatDaerah::query()
            ->when($q, fn($w) => $w->where('nama', 'like', "%{$q}%"))
            ->selectRaw('id as id, nama as text')
            ->orderBy('nama')
            ->limit(50)
            ->get();

        return response()->json(['results' => $rows]);
    }


    public function getPenyediaPerangkatDistinct(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $sub = PermintaanLayanan::query()
            ->select('perangkat_daerah_id') // perangkat penyedia
            ->whereNotNull('perangkat_daerah_id')
            ->distinct();

        $rows = \App\Models\PerangkatDaerah::query()
            ->whereIn('id', $sub)
            ->when($q, fn($w) => $w->where('nama', 'like', "%{$q}%"))
            ->selectRaw('id as id, nama as text')
            ->orderBy('nama')
            ->limit(50)
            ->get();

        return response()->json(['results' => $rows]);
    }

    public function getByStatus($status)
    {
        $currentUser = auth()->user();
        $userPenyediaId = $currentUser->penyedia_layanan_id;

        $query = PermintaanLayanan::with(['perangkatDaerah', 'penyediaLayanan', 'layanan', 'perangkatPemohon'])
            ->select(
                'id',
                'unit_kerja_pemohon',
                'no_permohonan',
                'no_antrian',
                'nama_pemohon',
                'nip_pemohon',
                'jabatan_pemohon',
                'tanggal',
                'perangkat_daerah_id',
                'perangkat_daerah_pemohon_id',
                'penyedia_layanan_id',
                'layanan_id',
                'deskripsi_spek',
                'status_id'
            )
            ->where('status_id', $status);

        if ($userPenyediaId != -1) {
            $query->where('penyedia_layanan_id', $userPenyediaId);
        }

        $items = $query->get();

        $data = $items->map(function ($item, $index) use ($currentUser) {
            $canVerifikasi = $currentUser->can('Permintaan Layanan;Verifikasi');
            $hashedId = Hashids::encode($item->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // View BUTTON
            // =============================
            if ($canVerifikasi) {
                $verifikasiUrl = route('permintaan-layanan.preview', $hashedId);
                $buttons .= <<<HTML
                <a href="{$verifikasiUrl}" class="btn btn-primary font-semibold px-3 py-1 rounded">
                    Pratinjau
                </a>
            HTML;
            }

            $buttons .= "</div>";

            return [
                'id' => $hashedId,
                'no' => $index + 1,
                'no_permohonan' => $item->no_permohonan,
                'no_antrian' => $item->no_antrian,
                'tanggal' => $item->tanggal,
                'pemohon_nama' => $item->perangkatPemohon->nama ?? '-',
                'nama_pemohon' => $item->nama_pemohon ?? '-',
                'nip' => $item->nip_pemohon ?? '-',
                'jabatan' => $item->jabatan_pemohon ?? '-',
                'unit_kerja' => $item->unit_kerja_pemohon ?? '-',
                'layanan_nama' => $item->layanan->nama ?? '-',
                'penyedia_nama' => $item->penyediaLayanan->nama_bidang ?? '-',
                'deskripsi_spek' => $item->deskripsi_spek ?? '-',
                'status_id' => $item->status_id ?? '-',
                'buttons' => $buttons
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function getPerangkatDaerah(Request $request)
    {
        $search = $request->get('q');
        $byUser = $request->boolean('by_user');

        $user = $request->user('sanctum');

        $query = PerangkatDaerah::query()
            ->select('id', 'nama');

        if ($byUser && $user) {
            if ((int) $user->perangkat_daerah_id !== -1) {
                $query->where('id', $user->perangkat_daerah_id);
            }
        }

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $data = $query->get()->map(function ($item) {
            return [
                'id'   => $item->id,
                'text' => $item->nama,
            ];
        });

        return response()->json(['results' => $data]);
    }

    public function getBidang(Request $request, $perangkatDaerahId)
    {
        $search = $request->get('q');

        $data = PenyediaLayanan::where('perangkat_daerah_id', $perangkatDaerahId)
            ->when($search, fn($q) => $q->where('nama_bidang', 'like', "%{$search}%"))
            ->select('id', 'nama_bidang')
            ->get()
            ->map(fn($item) => ['id' => $item->id, 'text' => $item->nama_bidang]);

        return response()->json(['results' => $data]);
    }

    public function getBidangAll(Request $request)
    {
        $search = $request->get('q');

        $query = PenyediaLayanan::query()
            ->with('perangkatDaerah:id,nama') // kalau relasinya ada
            ->when($search, function ($q) use ($search) {
                $q->where('nama_bidang', 'like', "%{$search}%");
            })
            ->orderBy('nama_bidang');

        $data = $query->get()->map(function ($item) {
            // Kalau mau tampil "Diskominfo - Sekretariat"
            $label = $item->nama_bidang;
            if ($item->perangkatDaerah && $item->perangkatDaerah->nama) {
                $label = $item->perangkatDaerah->nama . ' - ' . $item->nama_bidang;
            }

            return [
                'id'   => $item->id,
                'text' => $label,
            ];
        });

        return response()->json(['results' => $data]);
    }

    public function getLayanan(Request $request, $penyediaLayananId)
    {
        $search = $request->get('q');

        $data = KatalogLayanan::where('penyedia_layanan_id', $penyediaLayananId)
            ->when($search, fn($q) => $q->where('nama', 'like', "%{$search}%"))
            ->select('id', 'nama')
            ->where('jenis_layanan_id', config('constants.jenis_layanan.PERMINTAAN'))
            ->where('status_id', 7) //yJs munculkan hanya yg sudah verifikasi saja
            ->get()
            ->map(fn($item) => ['id' => $item->id, 'text' => $item->nama]);

        return response()->json(['results' => $data]);
    }

    public function generateNo(Request $request)
    {
        $layananId = $request->input('layanan_id');
        if (!$layananId) {
            return response()->json(['error' => 'ID layanan tidak ditemukan.'], 400);
        }

        $layanan = KatalogLayanan::find($layananId);
        if (!$layanan) {
            return response()->json(['error' => 'Layanan tidak ditemukan.'], 404);
        }

        $lastPermintaan = PermintaanLayanan::where('layanan_id', $layanan->id)
            ->where('no_antrian', 'like', $layanan->kode . '-%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastPermintaan && preg_match('/-(\d{4})$/', $lastPermintaan->no_antrian, $matches)
            ? str_pad(((int)$matches[1]) + 1, 4, '0', STR_PAD_LEFT)
            : '0001';

        $noAntrian = $layanan->kode . '-' . $nextNumber;

        return response()->json([
            'success' => true,
            'no_antrian' => $noAntrian
        ]);
    }

    public function getSyarat(Request $request, $katalogLayananId)
    {
        $permintaanId = $request->query('permintaan_id');

        $layananSyarat = LayananSyarat::with('jenisSyarat:id,nama,kelompok,keterangan')
            ->where('katalog_layanan_id', $katalogLayananId)
            ->get();

        $existingByLayananSyaratId = [];
        if ($permintaanId) {
            $existingByLayananSyaratId = PermintaanLayananSyarat::where('permintaan_layanan_id', $permintaanId)
                ->get()
                ->keyBy('layanan_syarat_id');
        }

        $data = $layananSyarat->map(function ($item) use ($existingByLayananSyaratId) {
            $existing = $existingByLayananSyaratId[$item->id] ?? null;

            return [
                'id'              => $item->id,
                'katalog_layanan_id' => $item->katalog_layanan_id,
                'jenis_syarat_id' => $item->jenis_syarat_id,
                'nama'            => $item->jenisSyarat->nama ?? '-',
                'kelompok'        => $item->jenisSyarat->kelompok ?? '-',
                'keterangan'      => $item->jenisSyarat->keterangan ?? '-',
                'deskripsi'       => $item->deskripsi ?? '-',

                // ini dari permintaan_layanan_syarat:
                'is_approve'      => optional($existing)->is_approve,
                'permintaan_syarat_id' => optional($existing)->id,
            ];
        });

        return response()->json($data);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $satuans = Satuan::select('nama')->get();
        return view('permintaan_layanan.create', compact('satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perangkat_daerah_pemohon_id' => 'required|exists:perangkat_daerah,id',
            'nama_pemohon'                => 'required|string|max:255',
            'nip_pemohon'                 => 'required|string|max:30',
            'jabatan_pemohon'             => 'required|string|max:255',
            'telepon_pemohon'             => 'required|max:50',
            'email_pemohon'               => 'required|max:50',
            'unit_kerja_pemohon'          => 'required|string',
            'tanggal'                     => 'required|date',
            'perangkat_daerah_id'         => 'required|exists:perangkat_daerah,id',
            'penyedia_layanan_id'         => 'required|exists:penyedia_layanan,id',
            'layanan_id'                  => 'required|exists:katalog_layanan,id',
            'deskripsi_spek'              => 'required|string',
            'detailList'                  => 'nullable|string|min:1',
            'syarat'                      => 'nullable|array',
        ], [
            'perangkat_daerah_pemohon_id.required' => 'Perangkat daerah pemohon wajib dipilih.',
            'nama_pemohon.required' => 'Nama pemohon wajib diisi.',
            'nip_pemohon.required' => 'NIP pemohon wajib diisi.',
            'jabatan_pemohon.required' => 'Jabatan pemohon wajib diisi.',
            'telepon_pemohon.required' => 'Telpon Pemohon wajib diisi.',
            'email_pemohon.required' => 'Email Pemohon wajib diisi.',
            'unit_kerja_pemohon.required' => 'Unit kerja pemohon wajib diisi.',
            'tanggal.required' => 'Tanggal permohonan wajib diisi.',
            'perangkat_daerah_id.required' => 'Perangkat daerah wajib dipilih.',
            'penyedia_layanan_id.required' => 'Penyedia layanan wajib dipilih.',
            'layanan_id.required' => 'Layanan wajib dipilih.',
            'deskripsi_spek.required' => 'Deskripsi spesifikasi layanan wajib diisi.',
            'detailList.nullable' => 'Detail layanan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated   = $validator->validated();
        $detailList  = json_decode($validated['detailList'], true) ?? [];

        // ================= VALIDASI SYARAT WAJIB UNTUK LAYANAN =================
        $syaratGroups = $request->input('syarat_form', []);
        $syaratFiles  = $request->file('syarat_form', []);

        $userJenisSyaratIds = [];
        foreach ($syaratGroups as $formTypeId => $group) {
            foreach ($group['items'] as $jenisId => $item) {
                $userJenisSyaratIds[] = (int)$jenisId;
            }
        }

        $requiredJenisIds = LayananSyarat::where('katalog_layanan_id', $validated['layanan_id'])
            ->pluck('jenis_syarat_id')
            ->toArray();

        $syaratErrors = [];

        foreach ($requiredJenisIds as $requiredJenisId) {
            if (!in_array($requiredJenisId, $userJenisSyaratIds)) {
                $syaratErrors["syarat.$requiredJenisId"] = ["Persyaratan wajib diisi."];
            }
        }

        if (!empty($syaratErrors)) {
            throw ValidationException::withMessages($syaratErrors);
        }
        // ================= END VALIDASI SYARAT =================

        DB::beginTransaction();

        try {
            // ===== GENERATE NO PERMOHONAN =====
            $layanan = KatalogLayanan::findOrFail($validated['layanan_id']);
            $lastPermintaan = PermintaanLayanan::where('layanan_id', $layanan->id)
                ->where('no_antrian', 'like', $layanan->kode . '-%')
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $lastPermintaan && preg_match('/-(\d{4})$/', $lastPermintaan->no_antrian, $matches)
                ? str_pad((int)$matches[1] + 1, 4, '0', STR_PAD_LEFT)
                : '0001';

            $noAntrian = $layanan->kode . '-' . $nextNumber;

            // ===== SIMPAN PERMINTAAN =====
            $permintaan = PermintaanLayanan::create([
                'perangkat_daerah_pemohon_id'  => $validated['perangkat_daerah_pemohon_id'],
                'nama_pemohon'                 => $validated['nama_pemohon'],
                'nip_pemohon'                  => $validated['nip_pemohon'],
                'jabatan_pemohon'              => $validated['jabatan_pemohon'],
                'telepon_pemohon'              => $validated['telepon_pemohon'],
                'email_pemohon'                => $validated['email_pemohon'],
                'unit_kerja_pemohon'           => $validated['unit_kerja_pemohon'],
                'no_antrian'                   => $noAntrian,
                'tanggal'                      => $validated['tanggal'],
                'perangkat_daerah_id'          => $validated['perangkat_daerah_id'],
                'penyedia_layanan_id'          => $validated['penyedia_layanan_id'],
                'layanan_id'                   => $validated['layanan_id'],
                'deskripsi_spek'               => $validated['deskripsi_spek'],
                'status_id'                    => 1,
                'created_by'                   => auth()->user()->name,
            ]);

            // ===== SIMPAN DETAIL =====
            foreach ($detailList as $item) {
                PermintaanLayananDetail::create([
                    'permintaan_layanan_id'   => $permintaan->id,
                    'nama_item'               => $item['nama_item'],
                    'deskripsi_layanan'       => $item['deskripsi_layanan'],
                    'banyaknya'               => $item['banyaknya'],
                    'satuan'                  => $item['satuan'],
                    'created_by'              => auth()->user()->name,
                ]);
            }

            // ===== SIMPAN STATUS AWAL =====
            PermintaanLayananStatus::create([
                'permintaan_layanan_id' => $permintaan->id,
                'status_id'             => 1,
                'keterangan'            => 'Permintaan layanan dibuat',
                'created_by'            => auth()->user()->name,
            ]);

            // ===== MAPPING ID UTAMA -> ID ASLI =====
            $idMap = [
                1 => [1],                 // Identitas Aplikasi
                2 => [2],                 // KAK
                3 => [3],                 // Surat Rekomendasi
                4 => [4, 5],              // Dokumen Teknis BRS SRS
                5 => [6, 7, 8, 9, 11],    // Dokumen Pengujian
                6 => [10],                // NDA
            ];

            // ===== SIMPAN SYARAT MODULAR =====
            foreach ($syaratGroups as $formTypeId => $group) {

                // if (!isset($idMap[$formTypeId])) continue;
                $targetJenisIds = $idMap[$formTypeId] ?? [$formTypeId];

                foreach ($group['items'] as $jenisSyaratId => $fields) {

                    // jenis_syarat_id = sudah benar (tidak perlu pakai index mapping)
                    $typeId = $jenisSyaratId;


                    // cari layanan_syarat_id
                    $layananSyaratId = LayananSyarat::where('katalog_layanan_id', $validated['layanan_id'])
                        ->where('jenis_syarat_id', $typeId)
                        ->value('id');

                    if (!$layananSyaratId) continue;

                    // buat entry permintaan_syarat
                    $permintaanSyarat = PermintaanLayananSyarat::create([
                        'permintaan_layanan_id' => $permintaan->id,
                        'layanan_syarat_id'     => $layananSyaratId,
                        'jenis_syarat_id'       => $typeId,
                        'created_by'            => auth()->user()->name,
                    ]);

                    // buat request kecil untuk service
                    $subRequest = new Request($fields);

                    // masukkan file jika ada
                    if (isset($syaratFiles[$formTypeId]['items'][$jenisSyaratId]['file_pendukung'])) {
                        $subRequest->files->set(
                            'file_pendukung',
                            $syaratFiles[$formTypeId]['items'][$jenisSyaratId]['file_pendukung']
                        );
                    }

                    $service = SyaratFactory::make($typeId);
                    $service->store($subRequest, $permintaanSyarat->id, 'permintaan');
                }
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data permintaan layanan berhasil dibuat!',
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $permintaan = PermintaanLayanan::with('permintaanSyarat')->findOrFail($id);
        $satuans = Satuan::all();

        $mapping = [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 4,
            6 => 5,
            7 => 5,
            8 => 5,
            9 => 5,
            11 => 5,
            10 => 6
        ];

        $syaratExisting = [];
        foreach ($permintaan->permintaanSyarat as $item) {
            $service = SyaratFactory::make($item->layananSyarat->jenis_syarat_id);
            $formTypeId = $mapping[$item->layananSyarat->jenis_syarat_id] ?? null;
            if (!$formTypeId) {
                $formTypeId = $item->layananSyarat->jenis_syarat_id;
            };
            $syaratExisting[$item->layananSyarat->jenis_syarat_id] = $service->getEditData($item->id, 'permintaan');
        }

        return view('permintaan_layanan.detail', compact('permintaan', 'syaratExisting', 'satuans'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $permintaan = PermintaanLayanan::with('permintaanSyarat')->findOrFail($id);

        if ($permintaan->status_id != 1) {
            return redirect()->route('permintaan-layanan.show', $id)
                ->with('error', 'Permintaan layanan tidak dapat diedit karena sudah dalam proses verifikasi atau telah selesai.');
        }
        $satuans = Satuan::all();

        $mapping = [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 4,
            6 => 5,
            7 => 5,
            8 => 5,
            9 => 5,
            11 => 5,
            10 => 6
        ];

        $syaratExisting = [];
        foreach ($permintaan->permintaanSyarat as $item) {
            $service = SyaratFactory::make($item->layananSyarat->jenis_syarat_id);
            $formTypeId = $mapping[$item->layananSyarat->jenis_syarat_id] ?? null;
            if (!$formTypeId) {
                $formTypeId = $item->layananSyarat->jenis_syarat_id;
            }
            $syaratExisting[$item->layananSyarat->jenis_syarat_id] = $service->getEditData($item->id, 'permintaan');
        }

        $hashedAgain = Hashids::encode($permintaan->id);

        return view('permintaan_layanan.edit', compact('permintaan', 'syaratExisting', 'satuans', 'hashedAgain'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $validator = Validator::make($request->all(), [
            'perangkat_daerah_pemohon_id' => 'required|exists:perangkat_daerah,id',
            'nama_pemohon'                => 'required|string|max:255',
            'nip_pemohon'                 => 'required|string|max:30',
            'jabatan_pemohon'             => 'required|string|max:255',
            'telepon_pemohon'             => 'required|max:50',
            'email_pemohon'               => 'required|max:50',
            'unit_kerja_pemohon'          => 'required|string|max:255',
            'tanggal'                     => 'required|date',
            'perangkat_daerah_id'         => 'required|exists:perangkat_daerah,id',
            'penyedia_layanan_id'         => 'required|exists:penyedia_layanan,id',
            'layanan_id'                  => 'required|exists:katalog_layanan,id',
            'deskripsi_spek'              => 'required|string',
            'detailList'                  => 'nullable|string|min:1',
        ], [
            'perangkat_daerah_pemohon_id.required' => 'Perangkat daerah pemohon wajib dipilih.',
            'nama_pemohon.required' => 'Nama pemohon wajib diisi.',
            'nip_pemohon.required' => 'NIP pemohon wajib diisi.',
            'jabatan_pemohon.required' => 'Jabatan pemohon wajib diisi.',
            'telepon_pemohon.required' => 'Telpon Pemohon wajib diisi.',
            'email_pemohon.required' => 'Email Pemohon wajib diisi.',
            'unit_kerja_pemohon.required' => 'Unit kerja pemohon wajib diisi.',
            'tanggal.required' => 'Tanggal permohonan wajib diisi.',
            'perangkat_daerah_id.required' => 'Perangkat daerah wajib dipilih.',
            'penyedia_layanan_id.required' => 'Penyedia layanan wajib dipilih.',
            'layanan_id.required' => 'Layanan wajib dipilih.',
            'deskripsi_spek.required' => 'Deskripsi spesifikasi layanan wajib diisi.',
            'detailList.nullable' => 'Detail layanan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated  = $validator->validated();
        $detailList = json_decode($validated['detailList'], true) ?? [];

        // ================= VALIDASI SYARAT WAJIB =================
        $syaratGroups = $request->input('syarat_form', []);
        $syaratFiles  = $request->file('syarat_form', []);

        $userJenisSyaratIds = [];
        foreach ($syaratGroups as $formTypeId => $group) {
            foreach ($group['items'] as $jenisId => $item) {
                $userJenisSyaratIds[] = (int)$jenisId;
            }
        }

        $requiredJenisIds = LayananSyarat::where('katalog_layanan_id', $validated['layanan_id'])
            ->pluck('jenis_syarat_id')
            ->toArray();

        $syaratErrors = [];
        foreach ($requiredJenisIds as $requiredJenisId) {
            if (!in_array($requiredJenisId, $userJenisSyaratIds)) {
                $syaratErrors["syarat.$requiredJenisId"] = ["Persyaratan wajib diisi."];
            }
        }

        if (!empty($syaratErrors)) {
            throw ValidationException::withMessages($syaratErrors);
        }
        // ================= END VALIDASI SYARAT =================

        DB::beginTransaction();

        try {
            // ================= UPDATE DATA UTAMA =================
            $permintaan = PermintaanLayanan::findOrFail($id);

            $permintaan->update([
                'perangkat_daerah_pemohon_id' => $validated['perangkat_daerah_pemohon_id'],
                'nama_pemohon'                => $validated['nama_pemohon'],
                'nip_pemohon'                 => $validated['nip_pemohon'],
                'jabatan_pemohon'             => $validated['jabatan_pemohon'],
                'telepon_pemohon'             => $validated['telepon_pemohon'],
                'email_pemohon'               => $validated['email_pemohon'],
                'unit_kerja_pemohon'          => $validated['unit_kerja_pemohon'],
                'tanggal'                     => $validated['tanggal'],
                'perangkat_daerah_id'         => $validated['perangkat_daerah_id'],
                'penyedia_layanan_id'         => $validated['penyedia_layanan_id'],
                'layanan_id'                  => $validated['layanan_id'],
                'deskripsi_spek'              => $validated['deskripsi_spek'],
                'updated_by'                  => auth()->user()->name,
            ]);

            // ================= UPDATE DETAIL =================
            $existingDetailIds = $permintaan->detailPermintaan()->pluck('id')->toArray();
            $incomingIds       = [];

            foreach ($detailList as $item) {
                $data = [
                    'permintaan_layanan_id' => $permintaan->id,
                    'nama_item'             => $item['nama_item'],
                    'deskripsi_layanan'     => $item['deskripsi_layanan'],
                    'banyaknya'             => $item['banyaknya'],
                    'satuan'                => $item['satuan'],
                    'updated_by'            => auth()->user()->name,
                    'updated_at'            => now(),
                ];

                if (!empty($item['id']) && in_array($item['id'], $existingDetailIds)) {
                    DB::table('permintaan_layanan_detail')->where('id', $item['id'])->update($data);
                    $incomingIds[] = $item['id'];
                } else {
                    $data['created_by'] = auth()->user()->name;
                    $data['created_at'] = now();
                    $newId = DB::table('permintaan_layanan_detail')->insertGetId($data);
                    $incomingIds[] = $newId;
                }
            }

            DB::table('permintaan_layanan_detail')
                ->where('permintaan_layanan_id', $permintaan->id)
                ->whereNotIn('id', $incomingIds)
                ->delete();

            // ================= UPDATE SYARAT =================
            foreach ($syaratGroups as $formTypeId => $group) {
                foreach ($group['items'] as $jenisSyaratId => $fields) {
                    $layananSyaratId = LayananSyarat::where('katalog_layanan_id', $validated['layanan_id'])
                        ->where('jenis_syarat_id', $jenisSyaratId)
                        ->value('id');

                    if (!$layananSyaratId) continue;

                    $permintaanSyarat = PermintaanLayananSyarat::firstOrCreate(
                        [
                            'permintaan_layanan_id' => $permintaan->id,
                            'jenis_syarat_id'       => $jenisSyaratId,
                        ],
                        [
                            'layanan_syarat_id' => $layananSyaratId,
                            'created_by'        => auth()->user()->name,
                        ]
                    );

                    // --- Hanya sertakan file jika benar-benar UploadedFile ---
                    if (isset($fields['file_pendukung']) && !($fields['file_pendukung'] instanceof \Illuminate\Http\UploadedFile)) {
                        unset($fields['file_pendukung']); // hapus file lama agar tidak divalidasi
                    }

                    $subRequest = new Request($fields);

                    // jika ada file baru, tambahkan ke subRequest
                    if (
                        isset($syaratFiles[$formTypeId]['items'][$jenisSyaratId]['file_pendukung'])
                        && $syaratFiles[$formTypeId]['items'][$jenisSyaratId]['file_pendukung'] instanceof \Illuminate\Http\UploadedFile
                    ) {
                        $subRequest->files->set(
                            'file_pendukung',
                            $syaratFiles[$formTypeId]['items'][$jenisSyaratId]['file_pendukung']
                        );
                    }

                    $service = SyaratFactory::make($jenisSyaratId);
                    $service->update($subRequest, $permintaanSyarat->id, 'permintaan');
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data permintaan layanan berhasil diperbarui!',
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function previewPage($hashedId)
    {

        $id = Hashids::decode($hashedId)[0] ?? null;

        $permintaan = PermintaanLayanan::with('permintaanSyarat')->findOrFail($id);
        $satuans = Satuan::all();

        $rekomendasis = PermintaanLayanan::where('perangkat_daerah_pemohon_id', $permintaan->perangkat_daerah_pemohon_id)
            ->where('layanan_id', config('constants.LAYANAN_REKOMENDASI_ID'))
            ->get();

        $pelaporans = PermintaanLayanan::where('perangkat_daerah_pemohon_id', $permintaan->perangkat_daerah_pemohon_id)
            ->where('layanan_id', config('constants.LAYANAN_PELAPORAN_ID'))
            ->get();

        $pengujians = PermintaanLayanan::where('perangkat_daerah_pemohon_id', $permintaan->perangkat_daerah_pemohon_id)
            ->where('layanan_id', config('constants.LAYANAN_PENGUJIAN_ID'))
            ->get();

        $mapping = [
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 4,
            6 => 5,
            7 => 5,
            8 => 5,
            9 => 5,
            11 => 5,
            10 => 6
        ];

        $syaratExisting = [];
        foreach ($permintaan->permintaanSyarat as $item) {
            $service = SyaratFactory::make($item->layananSyarat->jenis_syarat_id);
            $formTypeId = $mapping[$item->layananSyarat->jenis_syarat_id] ?? null;
            if (!$formTypeId) {
                $formTypeId = $item->layananSyarat->jenis_syarat_id;
            };
            $syaratExisting[$item->layananSyarat->jenis_syarat_id] = $service->getEditData($item->id, 'permintaan');
        }

        $statusMap = [
            1 => 'verifikasi',
            2 => 'proses',
            3 => 'closing',
            4 => 'selesai',
            5 => 'ditolak'
        ];

        $hashedAgain = Hashids::encode($permintaan->id);

        $currentStatus = $statusMap[$permintaan->status_id];

        return view('permintaan_layanan.preview-page', compact('permintaan', 'currentStatus', 'syaratExisting', 'satuans', 'hashedAgain', 'rekomendasis', 'pelaporans', 'pengujians'));
    }

    public function updateStatus(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $request->validate([
            'status'     => 'required|string|in:verifikasi,proses,closing,selesai,ditolak',
            'keterangan' => 'nullable|string',

            // approvals dikirim sebagai array
            'approvals'                      => 'nullable|array',
            'approvals.*.jenis_syarat_id'    => 'nullable|integer',
            'approvals.*.layanan_syarat_id'  => 'nullable|integer',
            'approvals.*.is_approve'         => 'nullable|boolean',
        ]);

        if ($request->status === 'proses') {
            $rules['no_permohonan'] = 'required|string';
        }

        $permintaan = PermintaanLayanan::findOrFail($id);

        // 1. SIMPAN APPROVE PER-SYARAT (kalau dikirim)
        if ($request->filled('approvals')) {
            foreach ($request->approvals as $row) {
                // skip baris yang kosong
                if (
                    empty($row['jenis_syarat_id']) &&
                    empty($row['layanan_syarat_id'])
                ) {
                    continue;
                }

                $query = PermintaanLayananSyarat::where('permintaan_layanan_id', $permintaan->id);

                if (!empty($row['layanan_syarat_id'])) {
                    $query->where('layanan_syarat_id', $row['layanan_syarat_id']);
                }

                if (!empty($row['jenis_syarat_id'])) {
                    $query->where('jenis_syarat_id', $row['jenis_syarat_id']);
                }

                $syaratRow = $query->first();

                $dataUpdate = [
                    'is_approve' => (bool) ($row['is_approve'] ?? false),
                    'updated_by' => auth()->user()->name ?? null,
                ];

                if ($syaratRow) {
                    $syaratRow->update($dataUpdate);
                } else {
                    PermintaanLayananSyarat::create(array_merge($dataUpdate, [
                        'permintaan_layanan_id' => $permintaan->id,
                        'layanan_syarat_id'     => $row['layanan_syarat_id'] ?? null,
                        'jenis_syarat_id'       => $row['jenis_syarat_id'] ?? null,
                        'created_by'            => auth()->user()->name ?? null,
                    ]));
                }
            }
        }

        // 2. PROSES PERUBAHAN STATUS SEPERTI BIASA
        $statusIdMap = [
            'verifikasi' => 1,
            'proses'     => 2,
            'closing'    => 3,
            'selesai'    => 4,
            'ditolak'    => 5,
        ];

        if ($request->status === 'closing') {
            if ($permintaan->layanan_id == config('constants.LAYANAN_REKOMENDASI_ID')) {
                $kadis = PerangkatDaerah::find($permintaan->perangkat_daerah_id);
                $syaratRekom = $permintaan->permintaanSyarat->first();

                $filePath = SuratRekomendasiService::generate($syaratRekom, $kadis);
            }
        }

        $newStatusId = $statusIdMap[$request->status];

        if ($permintaan->status_id != $newStatusId) {

            if ($request->status === 'proses') {
                $permintaan->no_permohonan = $request->no_permohonan;
            }

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

            DB::table('permintaan_layanan_status')->insert([
                'permintaan_layanan_id' => $permintaan->id,
                'status_id'             => $newStatusId,
                'keterangan'            => $request->keterangan ?? $keterangan,
                'created_by'            => auth()->user()->name,
                'updated_by'            => auth()->user()->name,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Status berhasil diubah menjadi {$request->status}"
        ]);
    }

    public function storeKatalogAplikasi(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;
        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID tidak valid.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'kode'            => 'required|string|max:100|unique:katalog_aplikasi,kode',
            'nama_aplikasi'   => 'required|string|max:255',
            'nama_ppk'        => 'required|string|max:255',
            'rekomendasi_id'  => 'nullable|string',
            'pelaporan_id'    => 'nullable|string',
            'pengujian_id'    => 'nullable|string',
            'perangkat_daerah_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // mapping checkbox to 1/0
            $is_pentest    = $request->has('is_pentest') ? 1 : 0;
            $is_integrasi  = $request->has('is_integrasi') ? 1 : 0;
            $is_hosting    = $request->has('is_hosting') ? 1 : 0;
            $is_domain     = $request->has('is_domain') ? 1 : 0;

            // Simpan ke tabel katalog_aplikasi
            $data = KatalogAplikasi::create([
                'permintaan_layanan_id'       => $id,
                'kode'                        => $request->kode,
                'nama_aplikasi'               => $request->nama_aplikasi,
                'nama_ppk'                    => $request->nama_ppk,
                'rekomendasi_id'              => $request->rekomendasi_id,
                'pelaporan_id'                => $request->pelaporan_id,
                'pengujian_id'                => $request->pengujian_id,
                'is_pentest'                  => $is_pentest,
                'is_integrasi'                => $is_integrasi,
                'is_hosting'                  => $is_hosting,
                'is_domain'                   => $is_domain,
                'keterangan'                   => $request->keterangan_katalog,
                'perangkat_daerah_id' => $request->perangkat_daerah_id,
                'created_by'                  => auth()->user()->name,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Katalog aplikasi berhasil disimpan.',
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan katalog aplikasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PermintaanLayanan $hashedId)
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
