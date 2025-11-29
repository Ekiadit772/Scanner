<?php

namespace App\Services\Syarat;

use App\Models\SyaratDokumenTeknisPengembangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SyaratDokumenTeknisPengembanganService implements SyaratServiceInterface
{
    private function fkColumn($jenis)
    {
        return match ($jenis) {
            'permintaan' => 'permintaan_layanan_syarat_id',
            'perubahan'  => 'perubahan_layanan_syarat_id',
            'insiden'    => 'insiden_layanan_syarat_id',
            default      => throw new \Exception("Jenis tidak valid: {$jenis}")
        };
    }


    public function store(Request $request, $id, $jenis)
    {
        \Log::info('HAS FILE?', [$request->hasFile('file_pendukung')]);
        \Log::info('FILES', $request->allFiles());

        $request->validate([
            'nomor_dokumen' => 'required|string|max:255',
            'nama_dokumen' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'penyusun' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096'
        ]);

        $path = $request->file('file_pendukung')
            ? $request->file('file_pendukung')->store("syarat/dokumen_teknis_pengembangan/{$id}", 'public')
            : null;

        $fkCol = $this->fkColumn($jenis);

        return SyaratDokumenTeknisPengembangan::create([
            $fkCol => $id,
            'nomor_dokumen' => $request->nomor_dokumen,
            'nama_dokumen' => $request->nama_dokumen,
            'tanggal' => $request->tanggal,
            'penyusun' => $request->penyusun,
            'deskripsi' => $request->deskripsi,
            'dokumen_type' => $request->dokumen_type,
            'file_pendukung' => $path,
            'created_by' => Auth::user()->name ?? 'system',
        ]);
    }

    public function getEditData($id, $jenis)
    {
        $fkCol = $this->fkColumn($jenis);
        $data = SyaratDokumenTeknisPengembangan::where($fkCol, $id)->first();

        if (!$data) {
            return null;
        }

        return [
            'fields' => [
                'nomor_dokumen' => $data->nomor_dokumen,
                'nama_dokumen' => $data->nama_dokumen,
                'tanggal' => $data->tanggal,
                'penyusun' => $data->penyusun,
                'deskripsi' => $data->deskripsi,
            ],
            'files' => [
                'file_pendukung' => $data->file_pendukung,
            ],
            'file_urls' => [
                'file_pendukung' => $data->file_pendukung,
            ],
        ];
    }

    public function update(Request $request, $id, $jenis)
    {
        $request->validate([
            'nomor_dokumen' => 'required|string|max:255',
            'nama_dokumen' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'penyusun' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096'
        ]);

        $fkCol = $this->fkColumn($jenis);
        $data = SyaratDokumenTeknisPengembangan::where($fkCol, $id)->first();

        if (!$data) {
            throw new \Exception("Data not found for permintaan_layanan_syarat_id: {$id}");
        }

        // Handle file upload
        if ($request->hasFile('file_pendukung')) {
            if ($data->file_pendukung) {
                Storage::disk('public')->delete($data->file_pendukung);
            }
            $data->file_pendukung = $request->file('file_pendukung')
                ->store("syarat/dokumen_teknis_pengembangan/{$id}", 'public');
        }

        $data->nomor_dokumen = $request->nomor_dokumen;
        $data->nama_dokumen = $request->nama_dokumen;
        $data->tanggal = $request->tanggal;
        $data->penyusun = $request->penyusun;
        $data->deskripsi = $request->deskripsi;
        $data->dokumen_type = $request->dokumen_type;
        $data->updated_by = Auth::user()->name ?? 'system';
        $data->save();

        return $data;
    }

    public function view($id, $jenis)
    {
        $fkCol = $this->fkColumn($jenis);
        $data = SyaratDokumenTeknisPengembangan::where($fkCol, $id)->first();
        return $data ? Storage::disk('public')->url($data->file_pendukung) : null;
    }
}
