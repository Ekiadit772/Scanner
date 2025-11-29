<?php

namespace Database\Seeders;

use App\Models\AreaPerubahan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaPerubahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AreaPerubahan::create([
            'nama' => 'Aplikasi SPBE',
        ]);
        AreaPerubahan::create([
            'nama' => 'Perangkat Keras',
        ]);
        AreaPerubahan::create([
            'nama' => 'Perangkat Lunak Sistem',
        ]);
        AreaPerubahan::create([
            'nama' => 'Infrastruktur',
        ]);
        AreaPerubahan::create([
            'nama' => 'Proses Bisnis',
        ]);
        AreaPerubahan::create([
            'nama' => 'Lingkungan Organisasi',
        ]);
        AreaPerubahan::create([
            'nama' => 'Layanan SPBE',
        ]);
        AreaPerubahan::create([
            'nama' => 'Data',
        ]);
        AreaPerubahan::create([
            'nama' => 'Keamanan Informasi',
        ]);
        AreaPerubahan::create([
            'nama' => 'Arsitektur SPBE',
        ]);

    }
}
