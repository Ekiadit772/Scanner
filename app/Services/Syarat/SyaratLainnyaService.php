<?php

namespace App\Services\Syarat;

use App\Models\SyaratLainnya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SyaratLainnyaService implements SyaratServiceInterface
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
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096'
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'file_pendukung.mimes' => 'File pendukung harus berupa file dengan format: pdf, doc, atau docx.',
            'file_pendukung.max' => 'Ukuran file pendukung maksimal adalah 4MB.',
        ]);


        $path = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $extension = $file->getClientOriginalExtension();
            $fileName = "syarat-lainnya-{$id}.{$extension}";

            $path = $file->storeAs(
                "syarat/lainnya/{$id}",
                $fileName,
                'public'
            );
        }

        $fkCol = $this->fkColumn($jenis);

        return SyaratLainnya::create([
            $fkCol => $id,
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'file_pendukung' => $path,
            'created_by' => Auth::user()->name ?? 'system',
        ]);
    }

    public function getEditData($id, $jenis)
    {
        $fkCol = $this->fkColumn($jenis);
        $data = SyaratLainnya::where($fkCol, $id)->first();

        if (!$data) return null;

        return [
            "fields" => [
                "nama" => $data->nama,
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
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096'
        ]);

        $fkCol = $this->fkColumn($jenis);
        $SyaratLainnya = SyaratLainnya::where($fkCol, $id)->firstOrFail();

        if ($request->hasFile('file_pendukung')) {
            if ($SyaratLainnya->file_pendukung) {
                Storage::disk('public')->delete($SyaratLainnya->file_pendukung);
            }

            $file = $request->file('file_pendukung');
            $extension = $file->getClientOriginalExtension();
            $fileName = "syarat-lainnya-{$id}.{$extension}";

            $SyaratLainnya->file_pendukung = $file->storeAs(
                "syarat/lainnya/{$id}",
                $fileName,
                'public'
            );
        }

        $SyaratLainnya->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'updated_by' => Auth::user()->name ?? 'system',
            'file_pendukung' => $SyaratLainnya->file_pendukung,
        ]);

        return $SyaratLainnya;
    }



    public function view($id, $jenis)
    {
        $fkCol = $this->fkColumn($jenis);
        $data = SyaratLainnya::where($fkCol, $id)->first();

        if (!$data) {
            return null;
        }

        return [
            'id' => $data->id,
            'permintaan_layanan_id' => $data->permintaan_layanan_id,
            'nama' => $data->nama,
            'deskripsi' => $data->deskripsi,
            'file_url' => $data->file_pendukung ? Storage::disk('public')->url($data->file_pendukung) : null,
            'created_by' => $data->created_by,
            'created_at' => $data->created_at,
        ];
    }
}
