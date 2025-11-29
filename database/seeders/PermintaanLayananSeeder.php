<?php

namespace Database\Seeders;

use App\Models\KatalogLayanan;
use App\Models\PermintaanLayanan;
use Illuminate\Database\Seeder;

class PermintaanLayananSeeder extends Seeder
{
    public function run()
    {
        $layanans = KatalogLayanan::all();

        foreach ($layanans as $layanan) {
            PermintaanLayanan::create([
                'layanan_id' => $layanan->id,
                'perangkat_daerah_id' => $layanan->perangkat_daerah_id,
                'penyedia_layanan_id' => $layanan->penyedia_layanan_id,
                'status' => 0,
                'deskripsi_spek' => 'Permintaan awal untuk ' . $layanan->nama,
                'nama_pemohon' => 'Pemohon ' . rand(1, 100),
                'nip' => rand(100000000, 999999999),
                'nama_instansi' => 'Instansi ' . rand(1, 50),
                'unit_kerja' => 'Unit Kerja ' . rand(1, 20),
                'no_permohonan' => 'PMT-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'tanggal' => now(),
                'persyaratan' => json_encode([
                    '1' => 'persyaratan/syarat1-' . time() . '.docx',
                    '2' => 'persyaratan/syarat2-' . time() . '.docx',
                    '3' => 'persyaratan/syarat3-' . time() . '.docx',
                ]),
            ]);
        }
    }
}
