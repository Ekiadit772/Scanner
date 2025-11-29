<?php

namespace App\Services\Syarat;

use App\Models\SyaratKak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SyaratKakService implements SyaratServiceInterface
{
    private function fkColumn($jenis)
    {
        return match ($jenis) {
            'permintaan' => 'permintaan_layanan_syarat_id',
            'perubahan'  => 'perubahan_layanan_id',
            'insiden'    => 'insiden_layanan_syarat_id',
            default      => throw new \Exception("Jenis tidak valid: {$jenis}")
        };
    }

    public function store(Request $request, $id, $jenis)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tahun' => 'required|digits:4',
            'jumlah_anggaran' => 'required',
            'sumber_anggaran' => 'nullable|string|max:255',
            'nama_ppk' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096'
        ]);

        $jumlahAnggaran = preg_replace('/[^0-9]/', '', $request->jumlah_anggaran);

        $path = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $ext = $file->getClientOriginalExtension();
            $fileName = "syarat-kak-{$id}.{$ext}";

            $path = $file->storeAs("syarat/kak/{$id}", $fileName, 'public');
        }

        $fkCol = $this->fkColumn($jenis);

        return SyaratKak::create([
            $fkCol => $id,
            'judul' => $request->judul,
            'tahun' => $request->tahun,
            'jumlah_anggaran' => $jumlahAnggaran,
            'sumber_anggaran' => $request->sumber_anggaran,
            'nama_ppk' => $request->nama_ppk,
            'deskripsi' => $request->deskripsi,
            'file_pendukung' => $path,
            'created_by' => Auth::user()->name ?? 'system',
        ]);
    }

    public function getEditData($id, $jenis)
    {
        $fkCol = $this->fkColumn($jenis);

        $data = SyaratKak::where($fkCol, $id)->first();
        if (!$data) return null;

        return [
            "fields" => [
                "judul" => $data->judul,
                "tahun" => $data->tahun,
                "jumlah_anggaran" => $data->jumlah_anggaran,
                "sumber_anggaran" => $data->sumber_anggaran,
                "nama_ppk" => $data->nama_ppk,
                "deskripsi" => $data->deskripsi,
            ],
            "files" => [
                "file_pendukung" => $data->file_pendukung
            ],
            "file_urls" => [
                "file_pendukung" => $data->file_pendukung,
            ]
        ];
    }

    public function update(Request $request, $id, $jenis)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tahun' => 'required|digits:4',
            'jumlah_anggaran' => 'required',
            'sumber_anggaran' => 'nullable|string|max:255',
            'nama_ppk' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096'
        ]);

        $fkCol = $this->fkColumn($jenis);

        $data = SyaratKak::where($fkCol, $id)->firstOrFail();

        $jumlahAnggaran = preg_replace('/[^0-9]/', '', $request->jumlah_anggaran);

        if ($request->hasFile('file_pendukung')) {
            if ($data->file_pendukung) {
                Storage::disk('public')->delete($data->file_pendukung);
            }

            $file = $request->file('file_pendukung');
            $ext = $file->getClientOriginalExtension();
            $fileName = "syarat-kak-{$id}.{$ext}";
            $data->file_pendukung = $file->storeAs("syarat/kak/{$id}", $fileName, 'public');
        }

        $data->update([
            'judul' => $request->judul,
            'tahun' => $request->tahun,
            'jumlah_anggaran' => $jumlahAnggaran,
            'sumber_anggaran' => $request->sumber_anggaran,
            'nama_ppk' => $request->nama_ppk,
            'deskripsi' => $request->deskripsi,
            'file_pendukung' => $data->file_pendukung,
            'updated_by' => Auth::user()->name ?? 'system',
        ]);

        return $data;
    }

    public function view($id, $jenis)
    {
        $fkCol = $this->fkColumn($jenis);

        $data = SyaratKak::where($fkCol, $id)->first();
        if (!$data) return null;

        return [
            'id' => $data->id,
            'judul' => $data->judul,
            'tahun' => $data->tahun,
            'jumlah_anggaran' => $data->jumlah_anggaran,
            'sumber_anggaran' => $data->sumber_anggaran,
            'nama_ppk' => $data->nama_ppk,
            'deskripsi' => $data->deskripsi,
            'file_url' => $data->file_pendukung
                ? Storage::disk('public')->url($data->file_pendukung)
                : null,
            'created_by' => $data->created_by,
            'created_at' => $data->created_at,
        ];
    }
}
