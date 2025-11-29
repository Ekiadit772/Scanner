<?php

namespace App\Services\Syarat;

use App\Models\SyaratSuratRekomendasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SyaratSuratRekomendasiService implements SyaratServiceInterface
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
            'nomor_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096'
        ], [
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
            'nomor_surat.max' => 'Nomor surat maksimal 255 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'nama.required' => 'Nama wajib diisi.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'file_pendukung.mimes' => 'File pendukung harus berupa file dengan format: pdf, doc, atau docx.',
            'file_pendukung.max' => 'Ukuran file pendukung maksimal adalah 4MB.',
        ]);

        $path = null;
        if ($request->hasFile('file_pendukung')) {
            $file = $request->file('file_pendukung');
            $extension = $file->getClientOriginalExtension();
            $fileName = "syarat-rekomendasi-{$id}.{$extension}";
            $path = $file->storeAs("syarat/rekomendasi/{$id}", $fileName, 'public');
        }

        $fkCol = $this->fkColumn($jenis);

        return SyaratSuratRekomendasi::create([
            $fkCol => $id,
            'nomor_surat' => $request->nomor_surat,
            'tanggal' => $request->tanggal,
            'nama' => $request->nama,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'deskripsi' => $request->deskripsi,
            'file_pendukung' => $path,
            'created_by' => Auth::user()->name ?? 'system',
        ]);
    }

    public function getEditData($id, $jenis)
    {
        $fkCol = $this->fkColumn($jenis);
        
        $data = SyaratSuratRekomendasi::where($fkCol, $id)->first();

        if (!$data) {
            return null;
        }

        return [
            'fields' => [
                'nomor_surat' => $data->nomor_surat,
                'tanggal' => $data->tanggal,
                'nama' => $data->nama,
                'nip' => $data->nip,
                'jabatan' => $data->jabatan,
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
            'nomor_surat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'jabatan' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_pendukung' => 'nullable|file|mimes:pdf,doc,docx|max:4096'
        ], [
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
            'nomor_surat.max' => 'Nomor surat maksimal 255 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Tanggal tidak valid.',
            'nama.required' => 'Nama wajib diisi.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'file_pendukung.mimes' => 'File pendukung harus berupa file dengan format: pdf, doc, atau docx.',
            'file_pendukung.max' => 'Ukuran file pendukung maksimal adalah 4MB.',
        ]);

        $fkCol = $this->fkColumn($jenis);
        $data = SyaratSuratRekomendasi::where($fkCol, $id)->first();

        if (!$data) {
            throw new \Exception("Data not found for permintaan_layanan_syarat_id: {$id}");
        }

        // Handle file upload
        if ($request->hasFile('file_pendukung')) {
            // Delete old file if exists
            if ($data->file_pendukung) {
                Storage::disk('public')->delete($data->file_pendukung);
            }
            $file = $request->file('file_pendukung');
            $extension = $file->getClientOriginalExtension();
            $fileName = "syarat-rekomendasi-{$id}.{$extension}";
            $data->file_pendukung = $file->storeAs("syarat/rekomendasi/{$id}", $fileName, 'public');
        }

        $data->nomor_surat = $request->nomor_surat;
        $data->tanggal = $request->tanggal;
        $data->nama = $request->nama;
        $data->nip = $request->nip;
        $data->jabatan = $request->jabatan;
        $data->deskripsi = $request->deskripsi;
        $data->updated_by = Auth::user()->name ?? 'system';
        $data->save();

        return $data;
    }

    public function view($id, $jenis)
    {
        $fkCol = $this->fkColumn($jenis);
        $data = SyaratSuratRekomendasi::where($fkCol, $id)->first();
        return $data ? Storage::disk('public')->url($data->file_pendukung) : null;
    }
}
