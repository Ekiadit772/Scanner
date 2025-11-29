<?php

namespace Database\Seeders;

use App\Models\JenisLayanan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisLayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JenisLayanan::create(
            [
                'nama' => 'Layanan Permintaan'
            ]
        );

        JenisLayanan::create(
            [
                'nama' => 'Layanan Perubahan'
            ]
        );

        JenisLayanan::create(
            [
                'nama' => 'Layanan Insiden'
            ]
        );
    }
}
