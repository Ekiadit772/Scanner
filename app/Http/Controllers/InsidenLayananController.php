<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Support\Str;
use App\Models\JenisInsiden;
use Illuminate\Http\Request;
use App\Models\LayananSyarat;
use App\Models\InsidenLayanan;
use App\Models\KatalogLayanan;
use App\Models\PenyediaLayanan;
use App\Models\PerangkatDaerah;
use App\Models\StatusTransaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;
use App\Models\InsidenLayananDetail;
use App\Models\InsidenLayananStatus;
use App\Models\InsidenLayananSyarat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use App\Services\Syarat\SyaratFactory;
use App\Models\StatusPenangananInsiden;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class InsidenLayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activeStatus = StatusTransaksi::where('is_aktif', 1)
            ->orderBy('id', 'asc')
            ->get();
        return view('insiden_layanan.index', compact('activeStatus'));
    }

    public function getSummaryinsidenLayanan(Request $request)
    {
        $search     = $request->get('search');
        $pemohonId  = $request->get('pemohon_id');
        $penyediaId = $request->get('penyedia_layanan_id');
        $layananId  = $request->get('layanan_id');
        $statusId   = $request->get('status_id');

        $user = $request->user('sanctum');

        $query = InsidenLayanan::with('status')
            ->whereHas('status', fn($q) => $q->where('is_aktif', 1));

        if ($user->perangkat_daerah_id != -1) {
            $query->where(function ($q) use ($user) {
                 //yjs$q->where('perangkat_daerah_pemohon_id', $user->perangkat_daerah_id);
                  $q->orWhere('perangkat_daerah_pemohon_id', $user->perangkat_daerah_id);
                $q->orWhere('perangkat_daerah_id', $user->perangkat_daerah_id);
            });
        }

        if (!empty($pemohonId)) {
            //yjs$query->where('perangkat_daerah_pemohon_id', $pemohonId);
            $query->orWhere('perangkat_daerah_pemohon_id', $pemohonId);
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

        $raw = $query->select('status_id', DB::raw('COUNT(*) as total'))
            ->groupBy('status_id')
            ->pluck('total', 'status_id');

        return response()->json([
            'dalam_antrian' => $raw[15] ?? 0,
            'penugasan'     => $raw[16] ?? 0,
            'penanganan'    => $raw[17] ?? 0,
            'penelusuran'   => $raw[18] ?? 0,
            'selesai'       => $raw[19] ?? 0,
            'ditolak'       => $raw[20] ?? 0,
            'pelanggaran'   => $raw[21] ?? 0,
        ]);
    }


    public function verifikasi()
    {
        return view('insiden_layanan.verifikasi');
    }

    public function getinsidenLayanan(Request $request)
    {
        $perPage    = (int) $request->get('per_page', 10);
        $page       = (int) $request->get('page', 1);
        $search     = $request->get('search');
        $pemohonId  = $request->get('pemohon_id');              // id perangkat PEMOHON
        $penyediaId = $request->get('penyedia_layanan_id');     // id perangkat PENYEDIA (bidang)
        $layananId  = $request->get('layanan_id');
        $statusId   = $request->get('status_id');

        $user = $request->user('sanctum');

        $query = InsidenLayanan::with([
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
                'keluhan',
                'status_id'
            );

        if ((int) $user->perangkat_daerah_id !== -1) {
            //yJs$query->where('perangkat_daerah_pemohon_id', $user->perangkat_daerah_id);
            $query->orWhere('perangkat_daerah_pemohon_id', $user->perangkat_daerah_id);
            $query->orWhere('perangkat_daerah_id', $user->perangkat_daerah_id);
        }

        if (!empty($pemohonId)) {
            //yJs$query->where('perangkat_daerah_pemohon_id', $pemohonId);
             $query->orWhere('perangkat_daerah_id', $pemohonId);
             $query->orWhere('perangkat_daerah_pemohon_id', $pemohonId);
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
            $canEdit = $currentUser->can('Manajemen Insiden;Edit');
            $canDelete = $currentUser->can('Manajemen Insiden:Hapus');
            $canView = $currentUser->can('Manajemen Insiden;Lihat Riwayat');
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
                    $editUrl = route('insiden-layanan.edit', ['id' => $hashedId]);
                    $buttons .= <<<HTML
                    <a href="{$editUrl}" class="text-blue-500 mr-2" x-tooltip="Edit">
                        {$iconEdit}
                    </a>
                    HTML;
                }
            }

            if ($canView) {
                $iconView = Blade::render('<x-icons.view />');
                $showUrl = route('insiden-layanan.show', $hashedId);
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
                'keluhan'                      => $item->keluhan ?? '-',
                'status_id'                    => $item->status_id ?? null,
                'status'                       => $item->status->nama_status ?? '-',
                'perangkat_daerah_pemohon_id'  => $item->perangkat_daerah_pemohon_id,
                'buttons' => $buttons
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

        $sub = InsidenLayanan::query()
            ->select('perangkat_daerah_id')
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

        $query = InsidenLayanan::with(['perangkatDaerah', 'penyediaLayanan', 'layanan', 'perangkatPemohon'])
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
                'keluhan',
                'status_id'
            )
            ->where('status_id', $status);

        if ($userPenyediaId != -1) {
            $query->where('penyedia_layanan_id', $userPenyediaId);
        }

        $items = $query->get();

        $data = $items->map(function ($item, $index) use ($currentUser) {

            $canVerifikasi = $currentUser->hasAnyPermission([
                'Manajemen Insiden;Penugasan Penanganan Insiden',
                'Manajemen Insiden;Penanganan Insiden',
                'Manajemen Insiden;Penulusuran Insiden',
                'Manajemen Insiden;Penutupan Penanganan',
            ]);

            $hashedId = Hashids::encode($item->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // View BUTTON
            // =============================
            if ($canVerifikasi) {
                $verifikasiUrl = route('insiden-layanan.preview', $hashedId);
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
                'keluhan' => $item->keluhan ?? '-',
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
            ->with('perangkatDaerah:id,nama')
            ->when($search, function ($q) use ($search) {
                $q->where('nama_bidang', 'like', "%{$search}%");
            })
            ->orderBy('nama_bidang');

        $data = $query->get()->map(function ($item) {
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
            ->where('jenis_layanan_id', config('constants.jenis_layanan.INSIDEN'))
            ->where('status_id', 7)
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

        $lastinsiden = insidenLayanan::where('layanan_id', $layanan->id)
            ->where('no_antrian', 'like', $layanan->kode . '-%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastinsiden && preg_match('/-(\d{4})$/', $lastinsiden->no_antrian, $matches)
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
        $insidenId = $request->query('insiden_id');

        $layananSyarat = LayananSyarat::with('jenisSyarat:id,nama,kelompok,keterangan')
            ->where('katalog_layanan_id', $katalogLayananId)
            ->get();

        $existingByLayananSyaratId = [];
        if ($insidenId) {
            $existingByLayananSyaratId = InsidenLayananSyarat::where('insiden_layanan_id', $insidenId)
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

                // ini dari insiden_layanan_syarat:
                'is_approve' => optional($existing)->is_approve,
                'insiden_syarat_id' => optional($existing)->id,
            ];
        });

        return response()->json($data);
    }

    public function getJenisInsiden(Request $request)
    {
        $search = $request->get('q');

        $query = JenisInsiden::select('id', 'nama');

        $query->where('nama', 'like', "%{$search}%");

        $data = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nama,
            ];
        });

        return response()->json(['results' => $data]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $satuans = Satuan::select('nama')->get();
        return view('insiden_layanan.create', compact('satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perangkat_daerah_pemohon_id' => 'required|exists:perangkat_daerah,id',
            'jenis_insiden_id' => 'required',
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
            'keluhan'                     => 'required|string',
            // 'detailList'                  => 'required|string|min:1',
            'syarat'                      => 'nullable|array',
        ], [
            'perangkat_daerah_pemohon_id.required' => 'Perangkat daerah pemohon wajib dipilih.',
            'jenis_insiden_id.required' => 'Jenis Insiden wajib dipilih.',
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
            'keluhan.required' => 'Keluhan wajib diisi.',
            // 'detailList.required' => 'Detail layanan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated   = $validator->validated();
        // $detailList  = json_decode($validated['detailList'], true) ?? [];

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
            $lastinsiden = InsidenLayanan::where('layanan_id', $layanan->id)
                ->where('no_antrian', 'like', $layanan->kode . '-%')
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $lastinsiden && preg_match('/-(\d{4})$/', $lastinsiden->no_antrian, $matches)
                ? str_pad((int)$matches[1] + 1, 4, '0', STR_PAD_LEFT)
                : '0001';

            $noAntrian = $layanan->kode . '-' . $nextNumber;

            // ===== SIMPAN insiden =====
            $insiden = InsidenLayanan::create([
                'perangkat_daerah_pemohon_id'  => $validated['perangkat_daerah_pemohon_id'],
                'jenis_insiden_id'             => $validated['jenis_insiden_id'],
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
                'keluhan'                      => $validated['keluhan'],
                'status_id'                    => 15,
                'created_by'                   => auth()->user()->name,
            ]);

            // ===== SIMPAN DETAIL =====
            // foreach ($detailList as $item) {
            //     InsidenLayananDetail::create([
            //         'insiden_layanan_id'   => $insiden->id,
            //         'nama_item'               => $item['nama_item'],
            //         'deskripsi_layanan'       => $item['deskripsi_layanan'],
            //         'banyaknya'               => $item['banyaknya'],
            //         'satuan'                  => $item['satuan'],
            //         'created_by'              => auth()->user()->name,
            //     ]);
            // }

            // ===== SIMPAN STATUS AWAL =====
            InsidenLayananStatus::create([
                'insiden_layanan_id' => $insiden->id,
                'status_id'             => 15,
                'keterangan'            => 'insiden layanan dibuat',
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

                    // buat entry insiden_syarat
                    $insidenSyarat = InsidenLayananSyarat::create([
                        'insiden_layanan_id' => $insiden->id,
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
                    $service->store($subRequest, $insidenSyarat->id, 'insiden');
                }
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data insiden layanan berhasil dibuat!',
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

        $insiden = InsidenLayanan::with('insidenSyarat')->findOrFail($id);
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

        $statusMap = [
            15 => 'dalam_antrian',
            16 => 'penugasan',
            17 => 'penanganan',
            18 => 'penelusuran',
            19 => 'selesai',
            // 20 => 'ditolak',
        ];

        $currentStatus = $statusMap[$insiden->status_id] ?? null;

        $statusPenanganan = StatusPenangananInsiden::where('is_aktif', 1)
            ->orderBy('nama')
            ->get();

        $syaratExisting = [];
        foreach ($insiden->insidenSyarat as $item) {
            $service = SyaratFactory::make($item->layananSyarat->jenis_syarat_id);
            $formTypeId = $mapping[$item->layananSyarat->jenis_syarat_id] ?? null;
            if (!$formTypeId) {
                $formTypeId = $item->layananSyarat->jenis_syarat_id;
            };
            $syaratExisting[$item->layananSyarat->jenis_syarat_id] = $service->getEditData($item->id, 'insiden');
        }

        return view('insiden_layanan.detail', compact('insiden', 'syaratExisting', 'satuans', 'currentStatus', 'statusPenanganan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $insiden = InsidenLayanan::with('insidenSyarat')->findOrFail($id);

        // if ($insiden->status_id != 9) {
        //     return redirect()->route('insiden-layanan.show', $id)
        //         ->with('error', 'insiden layanan tidak dapat diedit karena sudah dalam proses verifikasi atau telah selesai.');
        // }
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
        foreach ($insiden->insidenSyarat as $item) {
            $service = SyaratFactory::make($item->layananSyarat->jenis_syarat_id);
            $formTypeId = $mapping[$item->layananSyarat->jenis_syarat_id] ?? null;
            if (!$formTypeId) {
                $formTypeId = $item->layananSyarat->jenis_syarat_id;
            };
            $syaratExisting[$item->layananSyarat->jenis_syarat_id] = $service->getEditData($item->id, 'insiden');
        }

        $hashedAgain = Hashids::encode($insiden->id);

        return view('insiden_layanan.edit', compact('insiden', 'syaratExisting', 'satuans', 'hashedAgain'));
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
            'keluhan'                     => 'required|string',
            // 'detailList'                  => 'required|string|min:1',
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
            'keluhan.required' => 'Deskripsi spesifikasi layanan wajib diisi.',
            // 'detailList.required' => 'Detail layanan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated  = $validator->validated();
        // $detailList = json_decode($validated['detailList'], true) ?? [];

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
        // foreach ($requiredJenisIds as $requiredJenisId) {
        //     if (!in_array($requiredJenisId, $userJenisSyaratIds)) {
        //         $syaratErrors["syarat.$requiredJenisId"] = ["Persyaratan wajib diisi."];
        //     }
        // }

        if (!empty($syaratErrors)) {
            throw ValidationException::withMessages($syaratErrors);
        }
        // ================= END VALIDASI SYARAT =================

        DB::beginTransaction();

        try {
            // ================= UPDATE DATA UTAMA =================
            $insiden = InsidenLayanan::findOrFail($id);

            $insiden->update([
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
                'keluhan'                     => $validated['keluhan'],
                'updated_by'                  => auth()->user()->name,
            ]);

            // ================= UPDATE DETAIL =================
            // $existingDetailIds = $insiden->detailinsiden()->pluck('id')->toArray();
            // $incomingIds       = [];

            // foreach ($detailList as $item) {
            //     $data = [
            //         'insiden_layanan_id' => $insiden->id,
            //         'nama_item'             => $item['nama_item'],
            //         'deskripsi_layanan'     => $item['deskripsi_layanan'],
            //         'banyaknya'             => $item['banyaknya'],
            //         'satuan'                => $item['satuan'],
            //         'updated_by'            => auth()->user()->name,
            //         'updated_at'            => now(),
            //     ];

            //     if (!empty($item['id']) && in_array($item['id'], $existingDetailIds)) {
            //         DB::table('insiden_layanan_detail')->where('id', $item['id'])->update($data);
            //         $incomingIds[] = $item['id'];
            //     } else {
            //         $data['created_by'] = auth()->user()->name;
            //         $data['created_at'] = now();
            //         $newId = DB::table('insiden_layanan_detail')->insertGetId($data);
            //         $incomingIds[] = $newId;
            //     }
            // }

            // DB::table('insiden_layanan_detail')
            //     ->where('insiden_layanan_id', $insiden->id)
            //     ->whereNotIn('id', $incomingIds)
            //     ->delete();

            // ================= UPDATE SYARAT =================
            foreach ($syaratGroups as $formTypeId => $group) {
                foreach ($group['items'] as $jenisSyaratId => $fields) {
                    $layananSyaratId = LayananSyarat::where('katalog_layanan_id', $validated['layanan_id'])
                        ->where('jenis_syarat_id', $jenisSyaratId)
                        ->value('id');

                    if (!$layananSyaratId) continue;

                    $insidenSyarat = InsidenLayananSyarat::firstOrCreate(
                        [
                            'insiden_layanan_id' => $insiden->id,
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
                    $service->update($subRequest, $insidenSyarat->id, 'insiden');
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data insiden layanan berhasil diperbarui!',
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

        $insiden = InsidenLayanan::with('insidenSyarat')->findOrFail($id);
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
        foreach ($insiden->insidenSyarat as $item) {
            $service = SyaratFactory::make($item->layananSyarat->jenis_syarat_id);
            $formTypeId = $mapping[$item->layananSyarat->jenis_syarat_id] ?? null;
            if (!$formTypeId) {
                $formTypeId = $item->layananSyarat->jenis_syarat_id;
            };
            $syaratExisting[$item->layananSyarat->jenis_syarat_id] = $service->getEditData($item->id, 'insiden');
        }

        $statusMap = [
            15 => 'dalam_antrian',
            16 => 'penugasan',
            17 => 'penanganan',
            18 => 'penelusuran',
            19 => 'selesai',
            // 20 => 'ditolak',
        ];

        $hashedAgain = Hashids::encode($insiden->id);

        $currentStatus = $statusMap[$insiden->status_id] ?? null;
        $statusPenanganan = StatusPenangananInsiden::where('is_aktif', 1)
            ->orderBy('nama')
            ->get();

        return view('insiden_layanan.preview-page', compact('insiden', 'currentStatus', 'syaratExisting', 'satuans', 'hashedAgain', 'statusPenanganan'));
    }

    public function updateStatus(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        // aturan dasar
        $rules = [
            'status'                        => 'required|string|in:dalam_antrian,penugasan,penanganan,penelusuran,selesai,ditolak',
            'keterangan_status'             => 'nullable|string',
            'approvals'                     => 'nullable|array',
            'approvals.*.jenis_syarat_id'   => 'nullable|integer',
            'approvals.*.layanan_syarat_id' => 'nullable|integer',
            'approvals.*.is_approve'        => 'nullable|boolean',
        ];

        // penugasan: wajib no_permohonan
        if ($request->status === 'penugasan') {
            $rules['no_permohonan'] = 'required|string';
        }

        // penanganan: wajib isi teknik & perangkat
        if ($request->status === 'penanganan') {
            $rules['penanganan_insiden']        = 'required|string';
            $rules['perangkat_yang_diperlukan'] = 'required|string';
        }

        // penelusuran: wajib status penanganan, keterangan opsional
        if ($request->status === 'penelusuran') {
            $rules['status_penanganan_insiden_id'] = 'required|integer|exists:status_penanganan_insiden,id';
            $rules['keterangan_pelaksanaan']       = 'nullable|string';
        }

        // ditolak: keterangan sebaiknya wajib (kalau mau, bisa dipaksa di sini)
        if ($request->status === 'ditolak') {
            $rules['keterangan'] = 'required|string';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $insiden = InsidenLayanan::findOrFail($id);

        // 1. SIMPAN APPROVE PER-SYARAT (kalau dikirim)
        if ($request->filled('approvals')) {
            foreach ($request->approvals as $row) {
                if (empty($row['jenis_syarat_id']) && empty($row['layanan_syarat_id'])) {
                    continue;
                }

                $query = InsidenLayananSyarat::where('insiden_layanan_id', $insiden->id);

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
                    InsidenLayananSyarat::create(array_merge($dataUpdate, [
                        'insiden_layanan_id' => $insiden->id,
                        'layanan_syarat_id'  => $row['layanan_syarat_id'] ?? null,
                        'jenis_syarat_id'    => $row['jenis_syarat_id'] ?? null,
                        'created_by'         => auth()->user()->name ?? null,
                    ]));
                }
            }
        }

        // 2. UPDATE FIELD PENANGANAN / PENELUSURAN (walau status tidak berubah tetap disimpan)
        $modified = false;

        if ($request->has('penanganan_insiden')) {
            $insiden->penanganan_insiden = $request->penanganan_insiden;
            $modified = true;
        }

        if ($request->has('perangkat_yang_diperlukan')) {
            $insiden->perangkat_yang_diperlukan = $request->perangkat_yang_diperlukan;
            $modified = true;
        }

        if ($request->has('status_penanganan_insiden_id')) {
            $insiden->status_penanganan_insiden_id = $request->status_penanganan_insiden_id ?: null;
            $modified = true;
        }

        if ($request->has('keterangan_pelaksanaan')) {
            $insiden->keterangan_pelaksanaan = $request->keterangan_pelaksanaan;
            $modified = true;
        }

        // 3. PROSES PERUBAHAN STATUS
        $statusIdMap = [
            'dalam_antrian' => 15,
            'penugasan'     => 16,
            'penanganan'    => 17,
            'penelusuran'   => 18,
            'selesai'       => 19,
            'ditolak'       => 20,
        ];

        $newStatusId = $statusIdMap[$request->status];

        if ($insiden->status_id != $newStatusId) {

            if ($request->status === 'penugasan') {
                $insiden->no_permohonan = $request->no_permohonan;
                $modified = true;
            }

            $insiden->status_id = $newStatusId;
            $modified = true;

            $keteranganLog = $request->keterangan_status ?? null;

            if ($newStatusId == 16) {
                $keteranganLog = "Penugasan penanganan insiden oleh " . auth()->user()->name;
            } elseif ($newStatusId == 17) {
                $keteranganLog = "Penanganan insiden oleh " . auth()->user()->name;
            } elseif ($newStatusId == 18) {
                $keteranganLog = "Penelusuran dan status penanganan insiden oleh " . auth()->user()->name;
            } elseif ($newStatusId == 19) {
                $keteranganLog = "Penutupan penanganan insiden oleh " . auth()->user()->name;
            } elseif ($newStatusId == 20) {
                $keteranganLog = $request->keterangan;
            }

            DB::table('insiden_layanan_status')->insert([
                'insiden_layanan_id' => $insiden->id,
                'status_id'          => $newStatusId,
                'keterangan'         => $request->keterangan_status ?? $keteranganLog,
                'created_by'         => auth()->user()->name,
                'updated_by'         => auth()->user()->name,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);
        }

        if ($modified) {
            $insiden->save();
        }

        return response()->json([
            'success' => true,
            'message' => "Status berhasil diubah menjadi {$request->status}"
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InsidenLayanan $hashedId)
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
