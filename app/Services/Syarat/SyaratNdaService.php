<?php

namespace App\Services\Syarat;

use App\Models\SyaratNda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SyaratNdaService implements SyaratServiceInterface
{
    private function fkColumn($jenis)
    {
        return match ($jenis) {
            'permintaan' => 'permintaan_layanan_syarat_id',
            'perubahan'  => 'perubahan_layanan_id',
            'insiden'    => 'insiden_layanan_id',
            default      => throw new \Exception("Jenis tidak valid: {$jenis}")
        };
    }

    public function store(Request $request, $id, $jenis)
    {
        $request->validate([
            'nomor_dokumen' => 'required|string|max:255',
            'nama_dokumen' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nama_pihak_1' => 'nullable|string|max:255',
            'nama_pihak_2' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096'
        ], [
            'nomor_dokumen.required' => 'Nomor dokumen wajib diisi.',
            'nomor_dokumen.max' => 'Nomor dokumen maksimal 255 karakter.',
            'nama_dokumen.required' => 'Nama dokumen wajib diisi.',
            'nama_dokumen.max' => 'Nama dokumen maksimal 255 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'file_pendukung.mimes' => 'File pendukung harus berupa file dengan format: pdf, doc, atau docx.',
            'file_pendukung.max' => 'Ukuran file pendukung maksimal adalah 4MB.',
        ]);

        $path = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $extension = $file->getClientOriginalExtension();
            $fileName = "syarat-nda-{$id}.{$extension}";

            $path = $file->storeAs(
                "syarat/nda/{$id}",
                $fileName,
                'public'
            );
        }
        $fkCol = $this->fkColumn($jenis);

        return SyaratNda::create([
            $fkCol => $id,
            'nomor_dokumen' => $request->nomor_dokumen,
            'nama_dokumen' => $request->nama_dokumen,
            'tanggal' => $request->tanggal,
            'nama_pihak_1' => $request->nama_pihak_1,
            'nama_pihak_2' => $request->nama_pihak_2,
            'deskripsi' => $request->deskripsi,
            'file_pendukung' => $path,
            'created_by' => Auth::user()->name ?? 'system',
        ]);
    }

    public function getEditData($id, $jenis)
    {
        $fkCol = $this->fkColumn($jenis);

        $data = SyaratNda::where($fkCol, $id)->first();
        if (!$data) {
            return null;
        }

        return [
            "fields" => [
                "nomor_dokumen" => $data->nomor_dokumen,
                "nama_dokumen" => $data->nama_dokumen,
                "tanggal" => $data->tanggal,
                "nama_pihak_1" => $data->nama_pihak_1,
                "nama_pihak_2" => $data->nama_pihak_2,
                "deskripsi" => $data->deskripsi,
            ],
            "files" => [
                "file_pendukung" => $data->file_pendukung,
            ],
            "file_urls" => [
                "file_pendukung" => $data->file_pendukung,
            ],
        ];
    }

    public function update(Request $request, $id, $jenis)
    {
        $request->validate([
            'nomor_dokumen' => 'required|string|max:255',
            'nama_dokumen' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nama_pihak_1' => 'nullable|string|max:255',
            'nama_pihak_2' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096'
        ], [
            'nomor_dokumen.required' => 'Nomor dokumen wajib diisi.',
            'nomor_dokumen.max' => 'Nomor dokumen maksimal 255 karakter.',
            'nama_dokumen.required' => 'Nama dokumen wajib diisi.',
            'nama_dokumen.max' => 'Nama dokumen maksimal 255 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'file_pendukung.mimes' => 'File pendukung harus berupa file dengan format: pdf, doc, atau docx.',
            'file_pendukung.max' => 'Ukuran file pendukung maksimal adalah 4MB.',
        ]);

        $fkCol = $this->fkColumn($jenis);

        $syaratNda = SyaratNda::where($fkCol, $id)->firstOrFail();

        if ($request->hasFile('file_pendukung')) {
            if ($syaratNda->file_pendukung) {
                Storage::disk('public')->delete($syaratNda->file_pendukung);
            }

            $file = $request->file('file_pendukung');
            $extension = $file->getClientOriginalExtension();
            $fileName = "syarat-nda-{$id}.{$extension}";

            $syaratNda->file_pendukung = $file->storeAs(
                "syarat/nda/{$id}",
                $fileName,
                'public'
            );
        }

        $syaratNda->update([
            'nomor_dokumen' => $request->nomor_dokumen,
            'nama_dokumen' => $request->nama_dokumen,
            'tanggal' => $request->tanggal,
            'nama_pihak_1' => $request->nama_pihak_1,
            'nama_pihak_2' => $request->nama_pihak_2,
            'deskripsi' => $request->deskripsi,
            'updated_by' => Auth::user()->name ?? 'system',
        ]);

        return $syaratNda;
    }

    public function view($id, $jenis)
    {
        $fkCol = $this->fkColumn($jenis);
        $data = SyaratNda::where($fkCol, $id)->first();
        return $data ? Storage::disk('public')->url($data->file_pendukung) : null;
    }
}
