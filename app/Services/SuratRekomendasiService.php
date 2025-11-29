<?php

namespace App\Services;

use App\Models\PermintaanLayananSyarat;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;

class SuratRekomendasiService
{
    public static function generate(PermintaanLayananSyarat $permintaanSyarat, $kadis)
    {
        $permintaan = $permintaanSyarat->permintaanLayanan;
        $templatePath = storage_path('app/template-surat-rekomendasi.docx');

        if (!file_exists($templatePath)) {
            throw new \Exception("TEMPLATE surat rekomendasi tidak ditemukan: $templatePath");
        }

        // BUAT FOLDER DI DISK PUBLIC (yang benar)
        $folder = 'surat-rekomendasi/' . $permintaan->id;
        Storage::disk('public')->makeDirectory($folder);

        // Nama file
        $fileName = 'surat-rekomendasi-' . $permintaan->id . '.docx';

        // Path file untuk saveAs
        $outputPath = "app/public/{$folder}/{$fileName}";

        $template = new TemplateProcessor($templatePath);

        // isi template
        $template->setValue('KADIS_NAMA', $kadis->kadis_nama ?? '-');
        $template->setValue('KADIS_NIP', $kadis->kadis_nip ?? '-');

        $judulKak = $permintaan->syaratKAK->judul ?? '(Judul KAK tidak ditemukan)';
        $template->setValue('JUDUL_KAK', $judulKak);

        $template->setValue('NO_PERMOHONAN', $permintaan->no_permohonan ?? '-');
        $template->setValue('TANGGAL', now()->translatedFormat('d F Y'));
        $template->setValue('UNIT_KERJA_PEMOHON', $permintaan->unit_kerja_pemohon ?? '-');
        $template->setValue('NAMA_PPK', $permintaan->syaratKAK->nama_ppk ?? '-');
        $template->setValue('TELEPON_PEMOHON', $permintaan->telepon_pemohon ?? '-');

        // TTE Kadis
        $ttePath = $kadis->kadis_tte;
        if ($ttePath && Storage::disk('private')->exists($ttePath)) {
            $template->setImageValue('KADIS_TTE', [
                'path'   => storage_path('app/private/' . $ttePath),
                'width'  => 400,
                'ratio'  => true
            ]);
        } else {
            $template->setValue('KADIS_TTE', '');
        }

        // SIMPAN FILE DI STORAGE/APP/PUBLIC
        $template->saveAs(storage_path($outputPath));

        // Return path yang bisa diakses via asset('storage/...')
        return "{$folder}/{$fileName}";
    }
}
