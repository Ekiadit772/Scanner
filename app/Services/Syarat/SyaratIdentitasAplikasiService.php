<?php

namespace App\Services\Syarat;

use App\Models\SyaratIdentitasAplikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SyaratIdentitasAplikasiService implements SyaratServiceInterface
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
        $request->validate([
            'nama_aplikasi' => 'required|string|max:255',
            'pemilik_aplikasi' => 'required|string|max:255',
            'versi' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'url_aplikasi' => 'nullable|url',
            'username_aplikasi' => 'required|string|max:500',
            'password_aplikasi' => 'nullable|string|max:255',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096'
        ]);

        $path = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $extension = $file->getClientOriginalExtension();
            $fileName = "syarat-identitas-aplikasi-{$id}.{$extension}";
            $path = $file->storeAs("syarat/identitas-aplikasi/{$id}", $fileName, 'public');
        }

        $fkCol = $this->fkColumn($jenis);

        return SyaratIdentitasAplikasi::create([
            $fkCol => $id,
            'nama_aplikasi' => $request->nama_aplikasi,
            'pemilik_aplikasi' => $request->pemilik_aplikasi,
            'versi' => $request->versi,
            'deskripsi' => $request->deskripsi,
            'url_aplikasi' => $request->url_aplikasi,
            'username_aplikasi' => $request->username_aplikasi,
            'password_aplikasi' => $request->password_aplikasi,
            'file_pendukung' => $path,
            'created_by' => Auth::user()->name ?? 'system',
        ]);
    }

    public function getEditData($id, $jenis)
    {
        $fkCol = $this->fkColumn($jenis);

        $data = SyaratIdentitasAplikasi::where($fkCol, $id)->first();
        if (!$data) return null;

        return [
            'fields' => [
                'nama_aplikasi' => $data->nama_aplikasi,
                'pemilik_aplikasi' => $data->pemilik_aplikasi,
                'versi' => $data->versi,
                'deskripsi' => $data->deskripsi,
                'url_aplikasi' => $data->url_aplikasi,
                'username_aplikasi' => $data->username_aplikasi,
                'password_aplikasi' => $data->password_aplikasi,
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
            'nama_aplikasi' => 'required|string|max:255',
            'pemilik_aplikasi' => 'required|string|max:255',
            'versi' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'url_aplikasi' => 'nullable|url',
            'username_aplikasi' => 'required|string|max:500',
            'password_aplikasi' => 'nullable|string|max:255',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096'
        ]);

        $fkCol = $this->fkColumn($jenis);

        $data = SyaratIdentitasAplikasi::where($fkCol, $id)->first();
        if (!$data) {
            throw new \Exception("Data tidak ditemukan untuk {$fkCol}: {$id}");
        }

        if ($request->hasFile('file_pendukung')) {
            if ($data->file_pendukung) {
                Storage::disk('public')->delete($data->file_pendukung);
            }

            $file = $request->file('file_pendukung');
            $extension = $file->getClientOriginalExtension();
            $fileName = "syarat-identitas-aplikasi-{$id}.{$extension}";
            $data->file_pendukung =
                $file->storeAs("syarat/identitas-aplikasi/{$id}", $fileName, 'public');
        }

        $data->nama_aplikasi = $request->nama_aplikasi;
        $data->pemilik_aplikasi = $request->pemilik_aplikasi;
        $data->versi = $request->versi;
        $data->deskripsi = $request->deskripsi;
        $data->url_aplikasi = $request->url_aplikasi;
        $data->username_aplikasi = $request->username_aplikasi;
        $data->password_aplikasi = $request->password_aplikasi;
        $data->updated_by = Auth::user()->name ?? 'system';
        $data->save();

        return $data;
    }

    public function view($id, $jenis)
    {
        $fkCol = $this->fkColumn($jenis);

        $data = SyaratIdentitasAplikasi::where($fkCol, $id)->first();

        return $data && $data->file_pendukung
            ? Storage::disk('public')->url($data->file_pendukung)
            : null;
    }
}
