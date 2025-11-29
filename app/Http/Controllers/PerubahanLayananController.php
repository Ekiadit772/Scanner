<?php

namespace App\Http\Controllers;

use App\Models\Satuan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\LayananSyarat;
use App\Models\KatalogLayanan;
use App\Models\PenyediaLayanan;
use App\Models\PerangkatDaerah;
use App\Models\StatusTransaksi;
use App\Models\PerubahanLayanan;
use App\Models\KategoriPerubahan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use App\Models\PerubahanLayananDetail;
use App\Models\PerubahanLayananStatus;
use App\Models\PerubahanLayananSyarat;
use App\Models\PerubahanLayananVerifikasi;
use App\Services\Syarat\SyaratFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PerubahanLayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activeStatus = StatusTransaksi::where('is_aktif', 1)
            ->orderBy('id', 'asc')
            ->get();
        return view('perubahan_layanan.index', compact('activeStatus'));
    }

    public function getSummaryPerubahanLayanan(Request $request)
    {
        $search     = $request->get('search');
        $pemohonId  = $request->get('pemohon_id');
        $penyediaId = $request->get('penyedia_layanan_id');
        $layananId  = $request->get('layanan_id');
        $statusId   = $request->get('status_id');

        $user = $request->user();

        // base query
        $query = PerubahanLayanan::with('status')
            ->whereHas('status', fn($q) => $q->where('is_aktif', 1));;

        // restriction user perangkat daerah
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

        // summary grouping
        $raw = $query->select('status_id', DB::raw('COUNT(*) as total'))
            ->groupBy('status_id')
            ->pluck('total', 'status_id');

        return response()->json([
            'dalam_antrian' => $raw[9] ?? 0,
            'disetujui'     => $raw[10] ?? 0,
            'dilaksanakan'  => $raw[11] ?? 0,
            'penelusuran'   => $raw[12] ?? 0,
            'selesai'       => $raw[13] ?? 0,
            'ditolak'       => $raw[14] ?? 0,
            'pelaporan'     => $raw[21] ?? 0,
        ]);
    }

    public function verifikasi()
    {
        return view('perubahan_layanan.verifikasi');
    }

    // public function getPerubahanLayanan(Request $request)
    // {
    //     $perPage    = (int) $request->get('per_page', 10);
    //     $page       = (int) $request->get('page', 1);
    //     $search     = $request->get('search');
    //     $pemohonId  = $request->get('pemohon_id');              // id perangkat PEMOHON
    //     $penyediaId = $request->get('penyedia_layanan_id');     // id perangkat PENYEDIA (bidang)
    //     $layananId  = $request->get('layanan_id');
    //     $statusId   = $request->get('status_id');

    //     $user = $request->user();

    //     $query = PerubahanLayanan::with([
    //         'perangkatDaerah',      // penyedia
    //         'penyediaLayanan',
    //         'layanan',
    //         'perangkatPemohon',     // pemohon
    //         'status',
    //     ])
    //         ->select(
    //             'id',
    //             'unit_kerja_pemohon',
    //             'no_permohonan',
    //             'no_antrian',
    //             'nama_pemohon',
    //             'nip_pemohon',
    //             'jabatan_pemohon',
    //             'tanggal',
    //             'perangkat_daerah_id',              // PENYEDIA
    //             'perangkat_daerah_pemohon_id',      // PEMOHON
    //             'penyedia_layanan_id',
    //             'layanan_id',
    //             'deskripsi_spek',
    //             'status_id'
    //         );

    //     if ((int) $user->perangkat_daerah_id !== -1) {
    //         $query->where('perangkat_daerah_pemohon_id', $user->perangkat_daerah_id);
    //     }

    //     if (!empty($pemohonId)) {
    //         $query->where('perangkat_daerah_pemohon_id', $pemohonId);
    //     }

    //     if (!empty($penyediaId)) {
    //         $query->where('penyedia_layanan_id', $penyediaId);
    //     }

    //     if (!empty($layananId)) {
    //         $query->where('layanan_id', $layananId);
    //     }

    //     if (!empty($statusId)) {
    //         $query->where('status_id', $statusId);
    //     }

    //     if (!empty($search)) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('no_permohonan', 'like', "%{$search}%")
    //                 ->orWhereHas('layanan', fn($sub) => $sub->where('nama', 'like', "%{$search}%"))
    //                 ->orWhereHas('perangkatPemohon', fn($sub) => $sub->where('nama', 'like', "%{$search}%"))
    //                 ->orWhereHas('perangkatDaerah', fn($sub) => $sub->where('nama', 'like', "%{$search}%"))
    //                 ->orWhereHas('penyediaLayanan', fn($sub) => $sub->where('nama_bidang', 'like', "%{$search}%"));
    //         });
    //     }

    //     $data = $query->paginate($perPage, ['*'], 'page', $page);

    //     $formatted = collect($data->items())->map(function ($item, $index) use ($page, $perPage) {
    //         $currentUser = auth()->user();
    //         $canEdit = $currentUser->can('Manajemen Perubahan;Edit');
    //         $canDelete = $currentUser->can('Manajemen Perubahan;Hapus');
    //         $canView = $currentUser->can('Manajemen Perubahan;Lihat Riwayat');
    //         $userPerangkatId = $currentUser->perangkat_daerah_id;
    //         $userPerangkatNama = $currentUser->perangkatDaerah->nama ?? null;
    //         $hashedId = Hashids::encode($item->id);

    //         $statusIdVal = $item->status_id;
    //         $statusText = $item->status->nama_status;
    //         $pemohonPerangkatIdItem = $item->perangkat_daerah_pemohon_id;

    //         $isAdmin = $userPerangkatId === -1;
    //         $isOwnerRow = $pemohonPerangkatIdItem === $userPerangkatId;

    //         $buttons = "<div class='flex items-center'>";

    //         if ($canEdit && $this->isDalamAntrian($statusIdVal, $statusText) && ($isAdmin || $isOwnerRow)) {
    //             // =============================
    //             // EDIT BUTTON
    //             // =============================
    //             if ($canEdit) {
    //                 $iconEdit = Blade::render('<x-icons.edit />');
    //                 $editUrl = route('perubahan-layanan.edit', $hashedId);
    //                 $buttons .= <<<HTML
    //                 <a href="{$editUrl}" class="text-blue-500 mr-2" x-tooltip="Edit">
    //                     {$iconEdit}
    //                 </a>
    //                 HTML;
    //             }
    //         }

    //         if ($canView) {
    //             $iconView = Blade::render('<x-icons.view />');
    //             $showUrl = route('perubahan-layanan.show', $hashedId);
    //             $buttons .= <<<HTML
    //                 <a href="{$showUrl}" class="text-gray-500 mr-2" x-tooltip="Preview">
    //                     {$iconView}
    //                 </a>
    //                 HTML;
    //         }

    //         $buttons .= "</div>";

    //         return [
    //             'id'                           => $hashedId,
    //             'no'                           => (($page - 1) * $perPage) + ($index + 1),
    //             'no_permohonan'                => $item->no_permohonan,
    //             'no_antrian'                   => $item->no_antrian,
    //             'tanggal'                      => $item->tanggal,
    //             'pemohon_nama'                 => $item->perangkatPemohon->nama ?? '-',
    //             'nama_pemohon'                 => $item->nama_pemohon ?? '-',
    //             'nip'                          => $item->nip_pemohon ?? '-',
    //             'jabatan'                      => $item->jabatan_pemohon ?? '-',
    //             'unit_kerja'                   => $item->unit_kerja_pemohon ?? '-',
    //             'layanan_nama'                 => $item->layanan->nama ?? '-',
    //             'penyedia_nama'                => $item->penyediaLayanan->nama_bidang ?? '-',
    //             'deskripsi_spek'               => $item->deskripsi_spek ?? '-',
    //             'status_id'                    => $item->status_id ?? null,
    //             'status'                       => $item->status->nama_status ?? '-',
    //             'perangkat_daerah_pemohon_id'  => $item->perangkat_daerah_pemohon_id,
    //             'buttons' => $buttons
    //         ];
    //     });

    //     return response()->json([
    //         'data'         => $formatted,
    //         'total'        => $data->total(),
    //         'perPage'      => $data->perPage(),
    //         'current_page' => $data->currentPage(),
    //         'last_page'    => $data->lastPage(),
    //     ]);
    // }

    public function getPerubahanLayanan(Request $request)
    {
        $perPage    = (int) $request->get('per_page', 10);
        $page       = (int) $request->get('page', 1);
        $search     = $request->get('search');
        $pemohonId  = $request->get('pemohon_id');
        $statusId = $request->get('status_id');
        $user = $request->user();

        $query = PerubahanLayanan::with([
            'perangkatDaerah',      // penyedia
            'penyediaLayanan',
            'perangkatPemohon',     // pemohon
            'status',
        ])
            ->select(
                'id',
                'unit_kerja_pemohon',
                'no_permohonan',
                'no_antrian',
                'jabatan_pemohon',
                'tanggal',
                'perangkat_daerah_pemohon_id',
                'jenis_perubahan',
                'area_perubahan_ids',
                'deskripsi',
                'status_id'
            );

        if ((int) $user->perangkat_daerah_id !== -1) {
            //yJs $query->where('perangkat_daerah_pemohon_id', $user->perangkat_daerah_id);
            $query->orWhere('perangkat_daerah_pemohon_id', $user->perangkat_daerah_id);
            $query->orWhere('perangkat_daerah_id', $user->perangkat_daerah_id);

        }

        if (!empty($pemohonId)) {
            //yjs$query->where('perangkat_daerah_pemohon_id', $pemohonId);
             $query->orWhere('perangkat_daerah_id', $pemohonId);
             $query->orWhere('perangkat_daerah_pemohon_id', $pemohonId);
        }

        if (!empty($statusId)) {
            $query->where('status_id', $statusId);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('no_permohonan', 'like', "%{$search}%")
                    ->orWhereHas('perangkatPemohon', fn($sub) => $sub->where('nama', 'like', "%{$search}%"));
            });
        }

        $data = $query->paginate($perPage, ['*'], 'page', $page);

        $formatted = collect($data->items())->map(function ($item, $index) use ($page, $perPage) {
            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Manajemen Perubahan;Edit');
            $canDelete = $currentUser->can('Manajemen Perubahan;Hapus');
            $canView = $currentUser->can('Manajemen Perubahan;Lihat Riwayat');
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
                    $editUrl = route('perubahan-layanan.edit', $hashedId);
                    $buttons .= <<<HTML
                    <a href="{$editUrl}" class="text-blue-500 mr-2" x-tooltip="Edit">
                        {$iconEdit}
                    </a>
                    HTML;
                }
            }

            if ($canView) {
                $iconView = Blade::render('<x-icons.view />');
                $showUrl = route('perubahan-layanan.show', $hashedId);
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
                'pemohon_nama'                 => $item->perangkatPemohon->nama ?? '-',
                'tanggal'                      => $item->tanggal,
                'jabatan'                      => $item->jabatan_pemohon ?? '-',
                'unit_kerja'                   => $item->unit_kerja_pemohon ?? '-',
                'jenis_perubahan'              => $item->jenis_perubahan ?? '-',
                'area_perubahan_nama' => $item->area_perubahan_text,
                'deskripsi'                    => $item->deskripsi ?? '-',
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

        $sub = PerubahanLayanan::query()
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

    // public function getByStatus($status)
    // {
    //     $currentUser = auth()->user();
    //     $userPenyediaId = $currentUser->penyedia_layanan_id;

    //     $query = PerubahanLayanan::with(['perangkatDaerah', 'penyediaLayanan', 'layanan', 'perangkatPemohon'])
    //         ->select(
    //             'id',
    //             'unit_kerja_pemohon',
    //             'no_permohonan',
    //             'no_antrian',
    //             'nama_pemohon',
    //             'nip_pemohon',
    //             'jabatan_pemohon',
    //             'tanggal',
    //             'perangkat_daerah_id',
    //             'perangkat_daerah_pemohon_id',
    //             'penyedia_layanan_id',
    //             'layanan_id',
    //             'deskripsi_spek',
    //             'status_id'
    //         )
    //         ->where('status_id', $status);

    //     if ($userPenyediaId != -1) {
    //         $query->where('penyedia_layanan_id', $userPenyediaId);
    //     }

    //     $items = $query->get();

    //     $data = $items->map(function ($item, $index) use ($currentUser) {

    //         $canVerifikasi = $currentUser->hasAnyPermission([
    //             'Manajemen Perubahan;Persetujuan Perubahan',
    //             'Manajemen Perubahan;Pelaksanaan Perubahan',
    //             'Manajemen Perubahan;Penelusuran',
    //             'Manajemen Perubahan;Penutupan',
    //         ]);

    //         $hashedId = Hashids::encode($item->id);

    //         $buttons = "<div class='flex items-center'>";

    //         // =============================
    //         // View BUTTON
    //         // =============================
    //         if ($canVerifikasi) {
    //             $verifikasiUrl = route('perubahan-layanan.preview', $hashedId);
    //             $buttons .= <<<HTML
    //             <a href="{$verifikasiUrl}" class="btn btn-primary font-semibold px-3 py-1 rounded">
    //                 Pratinjau
    //             </a>
    //         HTML;
    //         }

    //         $buttons .= "</div>";

    //         return [
    //             'id' => $hashedId,
    //             'no' => $index + 1,
    //             'no_permohonan' => $item->no_permohonan,
    //             'no_antrian' => $item->no_antrian,
    //             'tanggal' => $item->tanggal,
    //             'pemohon_nama' => $item->perangkatPemohon->nama ?? '-',
    //             'nama_pemohon' => $item->nama_pemohon ?? '-',
    //             'nip' => $item->nip_pemohon ?? '-',
    //             'jabatan' => $item->jabatan_pemohon ?? '-',
    //             'unit_kerja' => $item->unit_kerja_pemohon ?? '-',
    //             'layanan_nama' => $item->layanan->nama ?? '-',
    //             'penyedia_nama' => $item->penyediaLayanan->nama_bidang ?? '-',
    //             'deskripsi_spek' => $item->deskripsi_spek ?? '-',
    //             'status_id' => $item->status_id ?? '-',
    //             'buttons' => $buttons
    //         ];
    //     });

    //     return response()->json(['data' => $data]);
    // }

    public function getByStatus($status)
    {
        $currentUser = auth()->user();
        $userPenyediaId = $currentUser->penyedia_layanan_id;

        $query = PerubahanLayanan::with(['perangkatDaerah', 'penyediaLayanan', 'perangkatPemohon', 'status'])
            ->select(
                'id',
                'unit_kerja_pemohon',
                'no_permohonan',
                'no_antrian',
                'jabatan_pemohon',
                'tanggal',
                'perangkat_daerah_pemohon_id',
                'jenis_perubahan',
                'area_perubahan_ids',
                'deskripsi',
                'status_id'
            )
            ->where('status_id', $status);

        if ($userPenyediaId != -1) {
            $query->where('penyedia_layanan_id', $userPenyediaId);
        }

        $items = $query->get();

        $data = $items->map(function ($item, $index) use ($currentUser) {

            $canVerifikasi = $currentUser->hasAnyPermission([
                'Manajemen Perubahan;Persetujuan Perubahan',
                'Manajemen Perubahan;Pelaksanaan Perubahan',
                'Manajemen Perubahan;Penelusuran',
                'Manajemen Perubahan;Penutupan',
            ]);

            $hashedId = Hashids::encode($item->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // View BUTTON
            // =============================
            if ($canVerifikasi) {
                if ($item->status_id == 10) {
                    // Jika status 10, arahkan ke route pelaporan
                    $verifikasiUrl = route('perubahan-layanan.verifikasi-pelaporan', $hashedId);
                } else if ($item->status_id == 21) {
                    $verifikasiUrl = route('perubahan-layanan.verifikasi-penutupan', $hashedId);
                } else {
                    // Selain itu tetap ke pratinjau
                    $verifikasiUrl = route('perubahan-layanan.preview', $hashedId);
                }

                $buttons .= <<<HTML
                    <a href="{$verifikasiUrl}" class="btn btn-primary font-semibold px-3 py-1 rounded">
                        Pratinjau
                    </a>
                HTML;
            }

            $buttons .= "</div>";

            return [
                'id'                           => $hashedId,
                'no'                           => $index + 1,
                'no_permohonan'                => $item->no_permohonan,
                'no_antrian'                   => $item->no_antrian,
                'pemohon_nama'                 => $item->perangkatPemohon->nama ?? '-',
                'tanggal'                      => $item->tanggal,
                'jabatan'                      => $item->jabatan_pemohon ?? '-',
                'unit_kerja'                   => $item->unit_kerja_pemohon ?? '-',
                'jenis_perubahan'              => $item->jenis_perubahan ?? '-',
                'area_perubahan_nama' => $item->area_perubahan_text,
                'deskripsi'                    => $item->deskripsi ?? '-',
                'status_id'                    => $item->status_id ?? null,
                'status'                       => $item->status->nama_status ?? '-',
                'perangkat_daerah_pemohon_id'  => $item->perangkat_daerah_pemohon_id,
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
            ->where('jenis_layanan_id', config('constants.jenis_layanan.PERUBAHAN'))
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

        $lastperubahan = PerubahanLayanan::where('layanan_id', $layanan->id)
            ->where('no_antrian', 'like', $layanan->kode . '-%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastperubahan && preg_match('/-(\d{4})$/', $lastperubahan->no_antrian, $matches)
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
        $perubahanId = $request->query('perubahan_id');

        $layananSyarat = LayananSyarat::with('jenisSyarat:id,nama,kelompok,keterangan')
            ->where('katalog_layanan_id', $katalogLayananId)
            ->get();

        $existingByLayananSyaratId = [];
        if ($perubahanId) {
            $existingByLayananSyaratId = PerubahanLayananSyarat::where('perubahan_layanan_id', $perubahanId)
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

                // ini dari perubahan_layanan_syarat:
                'is_approve'      => (bool) optional($existing)->is_approve,
                'perubahan_syarat_id' => optional($existing)->id,
            ];
        });

        return response()->json($data);
    }

    public function getKategoriPerubahan(Request $request)
    {
        $search = $request->get('q');

        $query = KategoriPerubahan::select('id', 'nama');

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
        return view('perubahan_layanan.create', compact('satuans'));
    }


    // public function store(Request $request)
    // {
    //     dd($request->all());
    //     $validator = Validator::make($request->all(), [
    //         'perangkat_daerah_pemohon_id' => 'required|exists:perangkat_daerah,id',
    //         'kategori_perubahan_id' => 'required',
    //         'nama_pemohon'                => 'required|string|max:255',
    //         'nip_pemohon'                 => 'required|string|max:30',
    //         'jabatan_pemohon'             => 'required|string|max:255',
    //         'unit_kerja_pemohon'          => 'required|string',
    //         'tanggal'                     => 'required|date',
    //         'perangkat_daerah_id'         => 'required|exists:perangkat_daerah,id',
    //         'penyedia_layanan_id'         => 'required|exists:penyedia_layanan,id',
    //         'layanan_id'                  => 'required|exists:katalog_layanan,id',
    //         'deskripsi_spek'              => 'required|string',
    //         'detailList'                  => 'required|string|min:1',
    //         'syarat'                      => 'nullable|array',
    //     ], [
    //         'perangkat_daerah_pemohon_id.required' => 'Perangkat daerah pemohon wajib dipilih.',
    //         'kategori_perubahan_id.required' => 'Kategori perubahan wajib dipilih.',
    //         'nama_pemohon.required' => 'Nama pemohon wajib diisi.',
    //         'nip_pemohon.required' => 'NIP pemohon wajib diisi.',
    //         'jabatan_pemohon.required' => 'Jabatan pemohon wajib diisi.',
    //         'unit_kerja_pemohon.required' => 'Unit kerja pemohon wajib diisi.',
    //         'tanggal.required' => 'Tanggal permohonan wajib diisi.',
    //         'perangkat_daerah_id.required' => 'Perangkat daerah wajib dipilih.',
    //         'penyedia_layanan_id.required' => 'Penyedia layanan wajib dipilih.',
    //         'layanan_id.required' => 'Layanan wajib dipilih.',
    //         'deskripsi_spek.required' => 'Deskripsi spesifikasi layanan wajib diisi.',
    //         'detailList.required' => 'Detail layanan wajib diisi.',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $validated   = $validator->validated();
    //     $detailList  = json_decode($validated['detailList'], true) ?? [];

    //     // ================= VALIDASI SYARAT WAJIB UNTUK LAYANAN =================
    //     $syaratGroups = $request->input('syarat_form', []);
    //     $syaratFiles  = $request->file('syarat_form', []);

    //     $userJenisSyaratIds = [];
    //     foreach ($syaratGroups as $formTypeId => $group) {
    //         foreach ($group['items'] as $jenisId => $item) {
    //             $userJenisSyaratIds[] = (int)$jenisId;
    //         }
    //     }

    //     $requiredJenisIds = LayananSyarat::where('katalog_layanan_id', $validated['layanan_id'])
    //         ->pluck('jenis_syarat_id')
    //         ->toArray();

    //     $syaratErrors = [];

    //     foreach ($requiredJenisIds as $requiredJenisId) {
    //         if (!in_array($requiredJenisId, $userJenisSyaratIds)) {
    //             $syaratErrors["syarat.$requiredJenisId"] = ["Persyaratan wajib diisi."];
    //         }
    //     }

    //     if (!empty($syaratErrors)) {
    //         throw ValidationException::withMessages($syaratErrors);
    //     }
    //     // ================= END VALIDASI SYARAT =================

    //     DB::beginTransaction();

    //     try {
    //         // ===== GENERATE NO PERMOHONAN =====
    //         $layanan = KatalogLayanan::findOrFail($validated['layanan_id']);
    //         $lastperubahan = PerubahanLayanan::where('layanan_id', $layanan->id)
    //             ->where('no_antrian', 'like', $layanan->kode . '-%')
    //             ->orderBy('id', 'desc')
    //             ->first();

    //         $nextNumber = $lastperubahan && preg_match('/-(\d{4})$/', $lastperubahan->no_antrian, $matches)
    //             ? str_pad((int)$matches[1] + 1, 4, '0', STR_PAD_LEFT)
    //             : '0001';

    //         $noAntrian = $layanan->kode . '-' . $nextNumber;

    //         // ===== SIMPAN perubahan =====
    //         $perubahan = PerubahanLayanan::create([
    //             'perangkat_daerah_pemohon_id'  => $validated['perangkat_daerah_pemohon_id'],
    //             'kategori_perubahan_id'        => $validated['kategori_perubahan_id'],
    //             'nama_pemohon'                 => $validated['nama_pemohon'],
    //             'nip_pemohon'                  => $validated['nip_pemohon'],
    //             'jabatan_pemohon'              => $validated['jabatan_pemohon'],
    //             'unit_kerja_pemohon'           => $validated['unit_kerja_pemohon'],
    //             'no_antrian'                   => $noAntrian,
    //             'tanggal'                      => $validated['tanggal'],
    //             'perangkat_daerah_id'          => $validated['perangkat_daerah_id'],
    //             'penyedia_layanan_id'          => $validated['penyedia_layanan_id'],
    //             'layanan_id'                   => $validated['layanan_id'],
    //             'deskripsi_spek'               => $validated['deskripsi_spek'],
    //             'status_id'                    => 9,
    //             'created_by'                   => auth()->user()->name,
    //         ]);

    //         // ===== SIMPAN DETAIL =====
    //         foreach ($detailList as $item) {
    //             PerubahanLayananDetail::create([
    //                 'perubahan_layanan_id'   => $perubahan->id,
    //                 'nama_item'               => $item['nama_item'],
    //                 'deskripsi_layanan'       => $item['deskripsi_layanan'],
    //                 'banyaknya'               => $item['banyaknya'],
    //                 'satuan'                  => $item['satuan'],
    //                 'created_by'              => auth()->user()->name,
    //             ]);
    //         }

    //         // ===== SIMPAN STATUS AWAL =====
    //         PerubahanLayananStatus::create([
    //             'perubahan_layanan_id' => $perubahan->id,
    //             'status_id'             => 9,
    //             'keterangan'            => 'perubahan layanan dibuat',
    //             'created_by'            => auth()->user()->name,
    //         ]);

    //         // ===== MAPPING ID UTAMA -> ID ASLI =====
    //         $idMap = [
    //             1 => [1],                 // Identitas Aplikasi
    //             2 => [2],                 // KAK
    //             3 => [3],                 // Surat Rekomendasi
    //             4 => [4, 5],              // Dokumen Teknis BRS SRS
    //             5 => [6, 7, 8, 9, 11],    // Dokumen Pengujian
    //             6 => [10],                // NDA
    //         ];

    //         // ===== SIMPAN SYARAT MODULAR =====
    //         foreach ($syaratGroups as $formTypeId => $group) {

    //             // if (!isset($idMap[$formTypeId])) continue;
    //             $targetJenisIds = $idMap[$formTypeId] ?? [$formTypeId];

    //             foreach ($group['items'] as $jenisSyaratId => $fields) {

    //                 // jenis_syarat_id = sudah benar (tidak perlu pakai index mapping)
    //                 $typeId = $jenisSyaratId;

    //                 // cari layanan_syarat_id
    //                 $layananSyaratId = LayananSyarat::where('katalog_layanan_id', $validated['layanan_id'])
    //                     ->where('jenis_syarat_id', $typeId)
    //                     ->value('id');

    //                 if (!$layananSyaratId) continue;

    //                 // buat entry perubahan_syarat
    //                 $perubahanSyarat = PerubahanLayananSyarat::create([
    //                     'perubahan_layanan_id' => $perubahan->id,
    //                     'layanan_syarat_id'     => $layananSyaratId,
    //                     'jenis_syarat_id'       => $typeId,
    //                     'created_by'            => auth()->user()->name,
    //                 ]);

    //                 // buat request kecil untuk service
    //                 $subRequest = new Request($fields);

    //                 // masukkan file jika ada
    //                 if (isset($syaratFiles[$formTypeId]['items'][$jenisSyaratId]['file_pendukung'])) {
    //                     $subRequest->files->set(
    //                         'file_pendukung',
    //                         $syaratFiles[$formTypeId]['items'][$jenisSyaratId]['file_pendukung']
    //                     );
    //                 }

    //                 $service = SyaratFactory::make($typeId);
    //                 $service->store($subRequest, $perubahanSyarat->id, 'perubahan');
    //             }
    //         }


    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Data perubahan layanan berhasil dibuat!',
    //         ]);
    //     } catch (ValidationException $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'errors' => $e->errors(),
    //         ], 422);
    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal menyimpan data: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'perangkat_daerah_id'         => 'required|exists:perangkat_daerah,id',
            'penyedia_layanan_id'         => 'required|exists:penyedia_layanan,id',
            'layanan_id'                  => 'required|exists:katalog_layanan,id',
            'syarat'                      => 'nullable|array',
            'kategori_perubahan_id' => 'required',
            'tanggal'                     => 'required|date',
            'no_antrian'                  => 'required|string',

            'perangkat_daerah_pemohon_id' => 'required|exists:perangkat_daerah,id',
            'unit_kerja_pemohon'          => 'required|string',
            'jabatan_pemohon'             => 'required|string|max:255',

            'judul_perubahan'             => 'required|string|max:500',
            'deskripsi'                   => 'required|string',

            'is_in_peta_spbe'             => 'nullable|in:0,1',

            'jenis_perubahan'             => 'required|in:Minor,Mayor,Darurat',

            'latar_belakang'              => 'required|string',
            'tujuan_perubahan'            => 'required|string',

            'area_perubahan_ids'              => 'required|array|min:1',
            'area_perubahan_ids.*'            => 'string',

            'jadwal_mulai'                => 'required|date',
            'jadwal_selesai'              => 'required|date|after_or_equal:jadwal_mulai',

            'is_downtime' => 'nullable|in:true,false,1,0',
            'downtime'    => 'nullable|string',
        ], [
            'perangkat_daerah_id.required' => 'Perangkat daerah wajib dipilih.',
            'penyedia_layanan_id.required' => 'Penyedia layanan wajib dipilih.',
            'layanan_id.required' => 'Layanan wajib dipilih.',
            'jadwal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

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

        // ===== DECRYPT area_perubahan =====
        $areaPerubahan = collect($validated['area_perubahan_ids'])
            ->map(function ($item) {
                $decoded = Hashids::decode($item);
                return $decoded[0] ?? null;
            })
            ->filter()
            ->implode(',');

        DB::beginTransaction();

        try {
            // ===== GENERATE NO PERMOHONAN =====
            $layanan = KatalogLayanan::findOrFail($validated['layanan_id']);
            $lastPermintaan = PerubahanLayanan::where('layanan_id', $layanan->id)
                ->where('no_antrian', 'like', $layanan->kode . '-%')
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $lastPermintaan && preg_match('/-(\d{4})$/', $lastPermintaan->no_antrian, $matches)
                ? str_pad((int)$matches[1] + 1, 4, '0', STR_PAD_LEFT)
                : '0001';

            $noAntrian = $layanan->kode . '-' . $nextNumber;

            $isDowntime = ($validated['is_downtime'] == 'true' || $validated['is_downtime'] == '1');

            $perubahan = PerubahanLayanan::create([
                'tanggal'                      => $validated['tanggal'],
                'no_antrian'                   => $noAntrian,

                'kategori_perubahan_id'             => $validated['kategori_perubahan_id'],
                'perangkat_daerah_id'          => $validated['perangkat_daerah_id'],
                'penyedia_layanan_id'          => $validated['penyedia_layanan_id'],
                'layanan_id'                   => $validated['layanan_id'],

                'perangkat_daerah_pemohon_id'  => $validated['perangkat_daerah_pemohon_id'],
                'jabatan_pemohon'              => $validated['jabatan_pemohon'],
                'unit_kerja_pemohon'           => $validated['unit_kerja_pemohon'],

                'judul_perubahan'              => $validated['judul_perubahan'],
                'deskripsi'                    => $validated['deskripsi'],

                'is_in_peta_spbe'              => $validated['is_in_peta_spbe'] ?? null,
                'jenis_perubahan'              => $validated['jenis_perubahan'],
                'latar_belakang'               => $validated['latar_belakang'],
                'tujuan_perubahan'             => $validated['tujuan_perubahan'],

                'area_perubahan_ids'               => $areaPerubahan, // hasil decrypt hashids

                'jadwal_mulai'                 => $validated['jadwal_mulai'],
                'jadwal_selesai'               => $validated['jadwal_selesai'],

                'is_downtime'                  => $isDowntime ? 1 : 0,
                'downtime'                     => $isDowntime ? ($validated['downtime'] ?? null) : null,

                'status_id'                    => 9,
                'created_by'                   => auth()->user()->name,
            ]);


            // ===== SIMPAN STATUS AWAL =====
            PerubahanLayananStatus::create([
                'perubahan_layanan_id' => $perubahan->id,
                'status_id'             => 9,
                'keterangan'            => 'perubahan layanan dibuat',
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

                    // buat entry perubahan_syarat
                    $perubahanSyarat = PerubahanLayananSyarat::create([
                        'perubahan_layanan_id' => $perubahan->id,
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
                    $service->store($subRequest, $perubahanSyarat->id, 'perubahan');
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data perubahan layanan berhasil dibuat!',
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

        $perubahan = PerubahanLayanan::with('perubahanSyarat')->findOrFail($id);

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
        foreach ($perubahan->perubahanSyarat as $item) {
            $service = SyaratFactory::make($item->layananSyarat->jenis_syarat_id);
            $formTypeId = $mapping[$item->layananSyarat->jenis_syarat_id] ?? null;
            if (!$formTypeId) continue;
            $syaratExisting[$item->layananSyarat->jenis_syarat_id] = $service->getEditData($item->id, 'perubahan');
        }

        $hashedAgain = Hashids::encode($perubahan->id);
        $hashedAreaPerubahan = collect(explode(',', $perubahan->area_perubahan_ids))
            ->map(fn($id) => Hashids::encode($id));

        return view('perubahan_layanan.detail', compact('perubahan', 'hashedAgain', 'hashedAreaPerubahan', 'syaratExisting'));
    }


    // public function edit($hashedId)
    // {
    //     $id = Hashids::decode($hashedId)[0] ?? null;

    //     $perubahan = PerubahanLayanan::with('perubahanSyarat')->findOrFail($id);

    //     if ($perubahan->status_id != 9) {
    //         return redirect()->route('perubahan-layanan.show', $id)
    //             ->with('error', 'perubahan layanan tidak dapat diedit karena sudah dalam proses verifikasi atau telah selesai.');
    //     }
    //     $satuans = Satuan::all();

    //     $mapping = [
    //         1 => 1,
    //         2 => 2,
    //         3 => 3,
    //         4 => 4,
    //         5 => 4,
    //         6 => 5,
    //         7 => 5,
    //         8 => 5,
    //         9 => 5,
    //         11 => 5,
    //         10 => 6
    //     ];

    //     $syaratExisting = [];
    //     foreach ($perubahan->perubahanSyarat as $item) {
    //         $service = SyaratFactory::make($item->layananSyarat->jenis_syarat_id);
    //         $formTypeId = $mapping[$item->layananSyarat->jenis_syarat_id] ?? null;
    //         if (!$formTypeId) continue;
    //         $syaratExisting[$item->layananSyarat->jenis_syarat_id] = $service->getEditData($item->id, 'perubahan');
    //     }

    //     $hashedAgain = Hashids::encode($perubahan->id);

    //     return view('perubahan_layanan.edit', compact('perubahan', 'syaratExisting', 'satuans', 'hashedAgain'));
    // }

    public function edit($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $perubahan = PerubahanLayanan::with('perubahanSyarat')->findOrFail($id);

        if ($perubahan->status_id != 9) {
            return redirect()->route('perubahan-layanan.show', $id)
                ->with('error', 'perubahan layanan tidak dapat diedit karena sudah dalam proses verifikasi atau telah selesai.');
        }

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
        foreach ($perubahan->perubahanSyarat as $item) {
            $service = SyaratFactory::make($item->layananSyarat->jenis_syarat_id);
            $formTypeId = $mapping[$item->layananSyarat->jenis_syarat_id] ?? null;
            if (!$formTypeId) continue;
            $syaratExisting[$item->layananSyarat->jenis_syarat_id] = $service->getEditData($item->id, 'perubahan');
        }

        $hashedAgain = Hashids::encode($perubahan->id);
        $hashedAreaPerubahan = collect(explode(',', $perubahan->area_perubahan_ids))
            ->map(fn($id) => Hashids::encode($id));

        return view('perubahan_layanan.edit', compact('perubahan', 'hashedAgain', 'syaratExisting', 'hashedAreaPerubahan'));
    }

    // public function update(Request $request, $hashedId)
    // {
    //     $id = Hashids::decode($hashedId)[0] ?? null;

    //     $validator = Validator::make($request->all(), [
    //         'perangkat_daerah_pemohon_id' => 'required|exists:perangkat_daerah,id',
    //         'nama_pemohon'                => 'required|string|max:255',
    //         'nip_pemohon'                 => 'required|string|max:30',
    //         'jabatan_pemohon'             => 'required|string|max:255',
    //         'unit_kerja_pemohon'          => 'required|string|max:255',
    //         'tanggal'                     => 'required|date',
    //         'perangkat_daerah_id'         => 'required|exists:perangkat_daerah,id',
    //         'penyedia_layanan_id'         => 'required|exists:penyedia_layanan,id',
    //         'layanan_id'                  => 'required|exists:katalog_layanan,id',
    //         'deskripsi_spek'              => 'required|string',
    //         'detailList'                  => 'required|string|min:1',
    //     ], [
    //         'perangkat_daerah_pemohon_id.required' => 'Perangkat daerah pemohon wajib dipilih.',
    //         'nama_pemohon.required' => 'Nama pemohon wajib diisi.',
    //         'nip_pemohon.required' => 'NIP pemohon wajib diisi.',
    //         'jabatan_pemohon.required' => 'Jabatan pemohon wajib diisi.',
    //         'unit_kerja_pemohon.required' => 'Unit kerja pemohon wajib diisi.',
    //         'tanggal.required' => 'Tanggal permohonan wajib diisi.',
    //         'perangkat_daerah_id.required' => 'Perangkat daerah wajib dipilih.',
    //         'penyedia_layanan_id.required' => 'Penyedia layanan wajib dipilih.',
    //         'layanan_id.required' => 'Layanan wajib dipilih.',
    //         'deskripsi_spek.required' => 'Deskripsi spesifikasi layanan wajib diisi.',
    //         'detailList.required' => 'Detail layanan wajib diisi.',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     $validated  = $validator->validated();
    //     $detailList = json_decode($validated['detailList'], true) ?? [];

    //     // ================= VALIDASI SYARAT WAJIB =================
    //     $syaratGroups = $request->input('syarat_form', []);
    //     $syaratFiles  = $request->file('syarat_form', []);

    //     $userJenisSyaratIds = [];
    //     foreach ($syaratGroups as $formTypeId => $group) {
    //         foreach ($group['items'] as $jenisId => $item) {
    //             $userJenisSyaratIds[] = (int)$jenisId;
    //         }
    //     }

    //     $requiredJenisIds = LayananSyarat::where('katalog_layanan_id', $validated['layanan_id'])
    //         ->pluck('jenis_syarat_id')
    //         ->toArray();

    //     $syaratErrors = [];
    //     foreach ($requiredJenisIds as $requiredJenisId) {
    //         if (!in_array($requiredJenisId, $userJenisSyaratIds)) {
    //             $syaratErrors["syarat.$requiredJenisId"] = ["Persyaratan wajib diisi."];
    //         }
    //     }

    //     if (!empty($syaratErrors)) {
    //         throw ValidationException::withMessages($syaratErrors);
    //     }
    //     // ================= END VALIDASI SYARAT =================

    //     DB::beginTransaction();

    //     try {
    //         // ================= UPDATE DATA UTAMA =================
    //         $perubahan = PerubahanLayanan::findOrFail($id);

    //         $perubahan->update([
    //             'perangkat_daerah_pemohon_id' => $validated['perangkat_daerah_pemohon_id'],
    //             'nama_pemohon'                => $validated['nama_pemohon'],
    //             'nip_pemohon'                 => $validated['nip_pemohon'],
    //             'jabatan_pemohon'             => $validated['jabatan_pemohon'],
    //             'unit_kerja_pemohon'          => $validated['unit_kerja_pemohon'],
    //             'tanggal'                     => $validated['tanggal'],
    //             'perangkat_daerah_id'         => $validated['perangkat_daerah_id'],
    //             'penyedia_layanan_id'         => $validated['penyedia_layanan_id'],
    //             'layanan_id'                  => $validated['layanan_id'],
    //             'deskripsi_spek'              => $validated['deskripsi_spek'],
    //             'updated_by'                  => auth()->user()->name,
    //         ]);

    //         // ================= UPDATE DETAIL =================
    //         $existingDetailIds = $perubahan->detailperubahan()->pluck('id')->toArray();
    //         $incomingIds       = [];

    //         foreach ($detailList as $item) {
    //             $data = [
    //                 'perubahan_layanan_id' => $perubahan->id,
    //                 'nama_item'             => $item['nama_item'],
    //                 'deskripsi_layanan'     => $item['deskripsi_layanan'],
    //                 'banyaknya'             => $item['banyaknya'],
    //                 'satuan'                => $item['satuan'],
    //                 'updated_by'            => auth()->user()->name,
    //                 'updated_at'            => now(),
    //             ];

    //             if (!empty($item['id']) && in_array($item['id'], $existingDetailIds)) {
    //                 DB::table('perubahan_layanan_detail')->where('id', $item['id'])->update($data);
    //                 $incomingIds[] = $item['id'];
    //             } else {
    //                 $data['created_by'] = auth()->user()->name;
    //                 $data['created_at'] = now();
    //                 $newId = DB::table('perubahan_layanan_detail')->insertGetId($data);
    //                 $incomingIds[] = $newId;
    //             }
    //         }

    //         DB::table('perubahan_layanan_detail')
    //             ->where('perubahan_layanan_id', $perubahan->id)
    //             ->whereNotIn('id', $incomingIds)
    //             ->delete();

    //         // ================= UPDATE SYARAT =================
    //         foreach ($syaratGroups as $formTypeId => $group) {
    //             foreach ($group['items'] as $jenisSyaratId => $fields) {
    //                 $layananSyaratId = LayananSyarat::where('katalog_layanan_id', $validated['layanan_id'])
    //                     ->where('jenis_syarat_id', $jenisSyaratId)
    //                     ->value('id');

    //                 if (!$layananSyaratId) continue;

    //                 $perubahanSyarat = PerubahanLayananSyarat::firstOrCreate(
    //                     [
    //                         'perubahan_layanan_id' => $perubahan->id,
    //                         'jenis_syarat_id'       => $jenisSyaratId,
    //                     ],
    //                     [
    //                         'layanan_syarat_id' => $layananSyaratId,
    //                         'created_by'        => auth()->user()->name,
    //                     ]
    //                 );

    //                 // --- Hanya sertakan file jika benar-benar UploadedFile ---
    //                 if (isset($fields['file_pendukung']) && !($fields['file_pendukung'] instanceof \Illuminate\Http\UploadedFile)) {
    //                     unset($fields['file_pendukung']); // hapus file lama agar tidak divalidasi
    //                 }

    //                 $subRequest = new Request($fields);

    //                 // jika ada file baru, tambahkan ke subRequest
    //                 if (
    //                     isset($syaratFiles[$formTypeId]['items'][$jenisSyaratId]['file_pendukung'])
    //                     && $syaratFiles[$formTypeId]['items'][$jenisSyaratId]['file_pendukung'] instanceof \Illuminate\Http\UploadedFile
    //                 ) {
    //                     $subRequest->files->set(
    //                         'file_pendukung',
    //                         $syaratFiles[$formTypeId]['items'][$jenisSyaratId]['file_pendukung']
    //                     );
    //                 }

    //                 $service = SyaratFactory::make($jenisSyaratId);
    //                 $service->update($subRequest, $perubahanSyarat->id, 'perubahan');
    //             }
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Data perubahan layanan berhasil diperbarui!',
    //         ]);
    //     } catch (ValidationException $e) {
    //         DB::rollBack();
    //         return response()->json(['errors' => $e->errors()], 422);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Gagal memperbarui data: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function update(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $validator = Validator::make($request->all(), [
            'tanggal'                     => 'required|date',
            'no_antrian'                  => 'required|string',

            'perangkat_daerah_id'         => 'required|exists:perangkat_daerah,id',
            'penyedia_layanan_id'         => 'required|exists:penyedia_layanan,id',
            'layanan_id'                  => 'required|exists:katalog_layanan,id',
            'kategori_perubahan_id' => 'required',

            'perangkat_daerah_pemohon_id' => 'required|exists:perangkat_daerah,id',
            'unit_kerja_pemohon'          => 'required|string',
            'jabatan_pemohon'             => 'required|string|max:255',

            'judul_perubahan'             => 'required|string|max:500',
            'deskripsi'                   => 'required|string',

            'is_in_peta_spbe'             => 'nullable|in:0,1',

            'jenis_perubahan'             => 'required|in:Minor,Mayor,Darurat',

            'latar_belakang'              => 'required|string',
            'tujuan_perubahan'            => 'required|string',

            'area_perubahan_ids'              => 'required|array|min:1',
            'area_perubahan_ids.*'            => 'string',

            'jadwal_mulai'                => 'required|date',
            'jadwal_selesai'              => 'required|date|after_or_equal:jadwal_mulai',

            'is_downtime' => 'nullable|in:true,false,1,0',
            'downtime'    => 'nullable|string',
        ], [
            'jadwal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated  = $validator->validated();

        // ================ DECRYPT AREA PERUBAHAN (Hashids) ==================
        $areaPerubahan = collect($validated['area_perubahan_ids'])
            ->map(function ($item) {
                $decoded = Hashids::decode($item);
                return $decoded[0] ?? null;
            })
            ->filter()
            ->implode(',');

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

        // ====================================================================

        DB::beginTransaction();

        try {
            $perubahan = PerubahanLayanan::findOrFail($id);

            $isDowntime = ($validated['is_downtime'] == 'true' || $validated['is_downtime'] == '1');

            $perubahan->update([
                'tanggal'                      => $validated['tanggal'],

                'perangkat_daerah_id'         => $validated['perangkat_daerah_id'],
                'penyedia_layanan_id'         => $validated['penyedia_layanan_id'],
                'layanan_id'                  => $validated['layanan_id'],
                'kategori_perubahan_id'                     => $validated['kategori_perubahan_id'],

                'perangkat_daerah_pemohon_id'  => $validated['perangkat_daerah_pemohon_id'],
                'jabatan_pemohon'              => $validated['jabatan_pemohon'],
                'unit_kerja_pemohon'           => $validated['unit_kerja_pemohon'],

                'judul_perubahan'              => $validated['judul_perubahan'],
                'deskripsi'                    => $validated['deskripsi'],

                'is_in_peta_spbe'              => $validated['is_in_peta_spbe'] ?? null,
                'jenis_perubahan'              => $validated['jenis_perubahan'],
                'latar_belakang'               => $validated['latar_belakang'],
                'tujuan_perubahan'             => $validated['tujuan_perubahan'],

                'area_perubahan_ids'               => $areaPerubahan, // hasil decrypt hashids

                'jadwal_mulai'                 => $validated['jadwal_mulai'],
                'jadwal_selesai'               => $validated['jadwal_selesai'],

                'is_downtime'                 => $isDowntime ? 1 : 0,
                'downtime'                    => $isDowntime ? $validated['downtime'] : null,

                'status_id'                    => 9,
                'updated_by'                   => auth()->user()->name,
            ]);

            // ================= UPDATE SYARAT =================
            foreach ($syaratGroups as $formTypeId => $group) {
                foreach ($group['items'] as $jenisSyaratId => $fields) {
                    $layananSyaratId = LayananSyarat::where('katalog_layanan_id', $validated['layanan_id'])
                        ->where('jenis_syarat_id', $jenisSyaratId)
                        ->value('id');

                    if (!$layananSyaratId) continue;

                    $perubahanSyarat = PerubahanLayananSyarat::firstOrCreate(
                        [
                            'perubahan_layanan_id' => $perubahan->id,
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
                    $service->update($subRequest, $perubahanSyarat->id, 'perubahan');
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data perubahan layanan berhasil diperbarui!',
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

        $perubahan = PerubahanLayanan::with('perubahanSyarat')->findOrFail($id);

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
        foreach ($perubahan->perubahanSyarat as $item) {
            $service = SyaratFactory::make($item->layananSyarat->jenis_syarat_id);
            $formTypeId = $mapping[$item->layananSyarat->jenis_syarat_id] ?? null;
            if (!$formTypeId) continue;
            $syaratExisting[$item->layananSyarat->jenis_syarat_id] = $service->getEditData($item->id, 'perubahan');
        }

        $hashedAgain = Hashids::encode($perubahan->id);

        $hashedAreaPerubahan = collect(explode(',', $perubahan->area_perubahan_ids))
            ->map(fn($id) => Hashids::encode($id));

        $statusMap = [
            9 => 'Dalam antrian',
            10 => 'Disetujui',
        ];

        $currentStatus = $statusMap[$perubahan->status_id] ?? null;

        return view('perubahan_layanan.preview-page', compact('perubahan', 'currentStatus', 'hashedAreaPerubahan', 'hashedAgain', 'syaratExisting'));
    }

    public function pelaporanPage($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $perubahan = PerubahanLayanan::with('perubahanSyarat', 'verifikasi')->findOrFail($id);

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
        foreach ($perubahan->perubahanSyarat as $item) {
            $service = SyaratFactory::make($item->layananSyarat->jenis_syarat_id);
            $formTypeId = $mapping[$item->layananSyarat->jenis_syarat_id] ?? null;
            if (!$formTypeId) continue;
            $syaratExisting[$item->layananSyarat->jenis_syarat_id] = $service->getEditData($item->id, 'perubahan');
        }

        $hashedAgain = Hashids::encode($perubahan->id);

        $hashedAreaPerubahan = collect(explode(',', $perubahan->area_perubahan_ids))
            ->map(fn($id) => Hashids::encode($id));

        $statusMap = [
            9 => 'Dalam antrian',
            10 => 'Disetujui',
            21 => 'Pelaporan',
        ];

        $currentStatus = $statusMap[$perubahan->status_id] ?? null;

        return view('perubahan_layanan.verifikasi-pelaporan', compact('perubahan', 'currentStatus', 'hashedAreaPerubahan', 'hashedAgain', 'syaratExisting'));
    }

    public function penutupanPage($hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $perubahan = PerubahanLayanan::with('perubahanSyarat', 'verifikasi', 'pelaporan')->findOrFail($id);

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
        foreach ($perubahan->perubahanSyarat as $item) {
            $service = SyaratFactory::make($item->layananSyarat->jenis_syarat_id);
            $formTypeId = $mapping[$item->layananSyarat->jenis_syarat_id] ?? null;
            if (!$formTypeId) continue;
            $syaratExisting[$item->layananSyarat->jenis_syarat_id] = $service->getEditData($item->id, 'perubahan');
        }

        $hashedAgain = Hashids::encode($perubahan->id);

        $hashedAreaPerubahan = collect(explode(',', $perubahan->area_perubahan_ids))
            ->map(fn($id) => Hashids::encode($id));

        $statusMap = [
            9 => 'Dalam antrian',
            10 => 'Disetujui',
            21 => 'Pelaporan',
        ];

        $currentStatus = $statusMap[$perubahan->status_id] ?? null;

        return view('perubahan_layanan.verifikasi-closing', compact('perubahan', 'currentStatus', 'hashedAreaPerubahan', 'hashedAgain', 'syaratExisting'));
    }

    public function updateStatus(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        $messages = [
            'status.required' => 'Status permohonan wajib dipilih.',
            'status.in'       => 'Status tidak valid.',

            'approvals'                     => 'nullable|array',
            'approvals.*.jenis_syarat_id'   => 'nullable|integer',
            'approvals.*.layanan_syarat_id' => 'nullable|integer',
            'approvals.*.is_approve'        => 'nullable|boolean',

            'keterangan.string' => 'Keterangan harus berupa teks.',

            'no_permohonan.required' => 'Nomor permohonan wajib diisi.',
            'no_permohonan.string'   => 'Nomor permohonan harus berupa teks.',

            'perubahan_layanan_id.required' => 'ID perubahan layanan tidak ditemukan.',

            'dampak_perubahan.required' => 'Dampak perubahan wajib diisi.',
            'dampak_perubahan.string'   => 'Dampak perubahan harus berupa teks.',

            'tingkat_dampak.required' => 'Tingkat dampak wajib dipilih.',
            'tingkat_dampak.in'       => 'Tingkat dampak tidak valid.',

            'kesiapan_personil.required' => 'Kesiapan personil wajib dipilih.',
            'kesiapan_personil.in'       => 'Kesiapan personil tidak valid.',

            'kesiapan_personil_catatan.required' => 'Catatan kesiapan personil wajib diisi.',
            'kesiapan_personil_catatan.string'   => 'Catatan kesiapan personil harus berupa teks.',

            'kesiapan_organisasi.required' => 'Kesiapan organisasi wajib dipilih.',
            'kesiapan_organisasi.in'       => 'Kesiapan organisasi tidak valid.',

            'kesiapan_organisasi_catatan.required' => 'Catatan kesiapan organisasi wajib diisi.',
            'kesiapan_organisasi_catatan.string'   => 'Catatan kesiapan organisasi harus berupa teks.',

            'risiko_potensial.required' => 'Risiko potensial wajib diisi.',
            'risiko_potensial.string'   => 'Risiko potensial harus berupa teks.',

            'rencana_mitigasi.required' => 'Rencana mitigasi risiko wajib diisi.',
            'rencana_mitigasi.string'   => 'Rencana mitigasi risiko harus berupa teks.',
        ];

        $validator = Validator::make($request->all(), [
            'status'     => 'required|string|in:Dalam antrian,Disetujui,Pelaporan,Dilaksanakan,Penelusuran,Selesai,Ditolak',
            'keterangan' => 'nullable|string',

            'no_permohonan' => 'required|string',
            'perubahan_layanan_id' => 'required',
            'dampak_perubahan'     => 'required|string',
            'tingkat_dampak'     => 'required|string|in:Tinggi,Sedang,Rendah',
            'kesiapan_personil'     => 'required|string|in:Tinggi,Sedang,Rendah',
            'kesiapan_personil_catatan'     => 'required|string',
            'kesiapan_organisasi'     => 'required|string|in:Tinggi,Sedang,Rendah',
            'kesiapan_organisasi_catatan'     => 'required|string',
            'risiko_potensial'     => 'required|string',
            'rencana_mitigasi'     => 'required|string',
        ], $messages);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        return DB::transaction(function () use ($request, $validated, $id) {

            $perubahan = PerubahanLayanan::findOrFail($id);

            if ($request->filled('approvals')) {
                foreach ($request->approvals as $row) {
                    if (empty($row['jenis_syarat_id']) && empty($row['layanan_syarat_id'])) {
                        continue;
                    }

                    $query = PerubahanLayananSyarat::where('perubahan_layanan_id', $perubahan->id);

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
                        PerubahanLayananSyarat::create(array_merge($dataUpdate, [
                            'perubahan_layanan_id' => $perubahan->id,
                            'layanan_syarat_id'  => $row['layanan_syarat_id'] ?? null,
                            'jenis_syarat_id'    => $row['jenis_syarat_id'] ?? null,
                            'created_by'         => auth()->user()->name ?? null,
                        ]));
                    }
                }
            }

            $statusIdMap = [
                'Dalam antrian' => 9,
                'Disetujui'     => 10,
                'Pelaporan'     => 21,
                'Selesai'       => 13,
                'Ditolak'       => 14,
            ];

            $newStatusId = $statusIdMap[$request->status];

            if ($perubahan->status_id != $newStatusId) {

                if ($request->status === 'Disetujui') {
                    $perubahan->no_permohonan = $request->no_permohonan;
                }

                $perubahan->status_id = $newStatusId;
                $perubahan->no_permohonan = $validated['no_permohonan'];
                $perubahan->save();


                $logKeterangan = null;
                if ($newStatusId == 10) {
                    $logKeterangan = "Permohonan disetujui oleh " . auth()->user()->name;
                } elseif ($newStatusId == 11) {
                    $logKeterangan = "Permohonan sedang dilaksanakan oleh " . auth()->user()->name;
                } elseif ($newStatusId == 12) {
                    $logKeterangan = "Permohonan dalam penelusuran oleh " . auth()->user()->name;
                } elseif ($newStatusId == 21) {
                    $logKeterangan = "Pelaporan permohonan oleh " . auth()->user()->name;
                } elseif ($newStatusId == 14) {
                    $logKeterangan = $request->keterangan;
                }


                PerubahanLayananVerifikasi::create([
                    'perubahan_layanan_id' => $perubahan->id,
                    'dampak_perubahan' => $validated['dampak_perubahan'],
                    'tingkat_dampak' => $validated['tingkat_dampak'],
                    'kesiapan_personil' => $validated['kesiapan_personil'],
                    'kesiapan_personil_catatan' => $validated['kesiapan_personil_catatan'],
                    'kesiapan_organisasi' => $validated['kesiapan_organisasi'],
                    'kesiapan_organisasi_catatan' => $validated['kesiapan_organisasi_catatan'],
                    'risiko_potensial' => $validated['risiko_potensial'],
                    'rencana_mitigasi' => $validated['rencana_mitigasi'],
                    'created_by'            => auth()->user()->name,
                    'updated_by'            => auth()->user()->name,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);

                DB::table('perubahan_layanan_status')->insert([
                    'perubahan_layanan_id' => $perubahan->id,
                    'status_id'             => $newStatusId,
                    'keterangan'            => $validated['keterangan'] ?: $logKeterangan,
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
        });
    }

    public function updateStatusTolak(Request $request, $hashedId)
    {
        $id = Hashids::decode($hashedId)[0] ?? null;

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID tidak valid.'
            ], 400);
        }

        // VALIDASI khusus untuk Ditolak
        $validated = $request->validate([
            'keterangan' => 'required|min:3',
        ]);

        return DB::transaction(function () use ($request, $validated, $id) {

            $perubahan = PerubahanLayanan::findOrFail($id);

            // Map status ID
            $statusIdMap = [
                'Dalam antrian' => 9,
                'Disetujui'     => 10,
                'Dilaksanakan'  => 11,
                'Penelusuran'   => 12,
                'Selesai'       => 13,
                'Ditolak'       => 14,
            ];

            $newStatusId = $statusIdMap[$request->status];

            // Update hanya jika berubah
            if ($perubahan->status_id != $newStatusId) {

                $perubahan->status_id = $newStatusId;
                $perubahan->save();

                // Keterangan khusus Ditolak
                $keterangan = $request->status === "Ditolak"
                    ? $validated['keterangan']
                    : null;

                DB::table('perubahan_layanan_status')->insert([
                    'perubahan_layanan_id' => $perubahan->id,
                    'status_id'             => $newStatusId,
                    'keterangan'            => $keterangan,
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
        });
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerubahanLayanan $hashedId)
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
