<?php

namespace App\Http\Controllers;

use App\Models\KatalogAplikasi;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class KatalogAplikasiController extends Controller
{
    public function index()
    {
        $katalog_aplikasis = KatalogAplikasi::all();
        return view('katalog_aplikasi.index', compact('katalog_aplikasis'));
    }

    public function getKatalogAplikasi()
    {
        $katalogAplikasi = KatalogAplikasi::with(['rekomendasi', 'pelaporan', 'pengujian', 'perangkatDaerah'])
            ->select('id', 'kode', 'nama_aplikasi', 'nama_ppk', 'perangkat_daerah_id', 'rekomendasi_id', 'pelaporan_id', 'pengujian_id')
            ->get();

        $data = $katalogAplikasi->map(function ($jp, $index) {
            $limit = function ($value) {
                $value = $value ?? '-';
                return strlen($value) > 15 ? substr($value, 0, 15) . '...' : $value;
            };

            $currentUser = auth()->user();
            $canEdit = $currentUser->can('Jenis Peran;Edit');
            $canDelete = $currentUser->can('Jenis Peran;Hapus');
            $hashedId = Hashids::encode($jp->id);

            $buttons = "<div class='flex items-center'>";

            // =============================
            // EDIT BUTTON
            // =============================
            // if ($canEdit) {
            //     $iconEdit = Blade::render('<x-icons.edit />');
            //     $buttons .= <<<HTML
            //         <button type="button" onclick="editJenisPeran('{$hashedId}')" class="text-blue-500 mr-2" x-tooltip="Edit">
            //             {$iconEdit}
            //         </button>
            //         HTML;
            // }

            // =============================
            // DELETE BUTTON
            // =============================
            // if ($canDelete) {
            //     $iconDelete = Blade::render('<x-icons.delete />');
            //     $buttons .= <<<HTML
            //         <button type="button" onclick="deleteJenisPeran('{$hashedId}')" class="text-red-500 mr-2" x-tooltip="Hapus">
            //             {$iconDelete}
            //         </button>
            //         HTML;
            // }

            $buttons .= "</div>";

            return [
                $index + 1,
                $jp->kode,
                $jp->nama_aplikasi,
                $jp->nama_ppk,
                $limit(optional($jp->perangkatDaerah)->nama ?? '-'),

                // ambil no_permohonan relasi
                $limit(optional($jp->rekomendasi)->no_permohonan  ?? '-'),
                $limit(optional($jp->pelaporan)->no_permohonan  ?? '-'),
                $limit(optional($jp->pengujian)->no_permohonan  ?? '-'),

                // $jp->is_pentest,
                // $jp->is_integrasi,
                // $jp->is_hosting,
                // $jp->is_domain,
                // $jp->keterangan,

                $hashedId,
                // $buttons,
            ];
        });

        return response()->json(['data' => $data]);
    }
}
