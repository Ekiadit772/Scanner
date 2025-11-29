<?php

namespace Database\Seeders;

use App\Models\KatalogLayanan;
use App\Models\PenyediaLayanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run()
    {
        $grouped = PenyediaLayanan::all()->groupBy('perangkat_daerah_id');

        foreach ($grouped as $pd_id => $penyediaList) {
            foreach ($penyediaList as $penyedia) {
                KatalogLayanan::create([
                    'kode' => 'LYN-' . str_pad($penyedia->id, 3, '0', STR_PAD_LEFT),
                    'nama' => 'Layanan untuk ' . $penyedia->nama_bidang . rand(1, 100),
                    'perangkat_daerah_id' => $pd_id,
                    'penyedia_layanan_id' => $penyedia->id,
                    'deskripsi' => 'Deskripsi layanan untuk ' . $penyedia->nama_bidang,
                    'kelompok_layanan_id' => rand(1, 4),
                    'status' => 0,
                ]);
            }
        }
    }
}
