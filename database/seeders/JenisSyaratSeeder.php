<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisSyaratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_syarat')->insert([
            ['nama' => 'Identitas Aplikasi', 'kelompok' => '', 'keterangan' => '', 'is_internal' => 0],
            ['nama' => 'KAK', 'kelompok' => '', 'keterangan' => '', 'is_internal' => 0],
            ['nama' => 'Surat Rekomendasi', 'kelompok' => '', 'keterangan' => '', 'is_internal' => 0],
            ['nama' => 'Bussiness Requirements Specification (BRS)', 'kelompok' => 'Dokumen Teknis Pengembangan', 'keterangan' => '', 'is_internal' => 0],
            ['nama' => 'Software Requirement Specification (SRS)', 'kelompok' => 'Dokumen Teknis Pengembangan', 'keterangan' => '', 'is_internal' => 0],
            ['nama' => 'Site Acceptance Test (SAT)', 'kelompok' => 'Dokumen Pengujian', 'keterangan' => '', 'is_internal' => 1],
            ['nama' => 'User Acceptance Test (UAT)', 'kelompok' => 'Dokumen Pengujian', 'keterangan' => '', 'is_internal' => 1],
            ['nama' => 'Stressing Test', 'kelompok' => 'Dokumen Pengujian', 'keterangan' => '', 'is_internal' => 1],
            ['nama' => 'Katalog', 'kelompok' => 'Dokumen Pengujian', 'keterangan' => '', 'is_internal' => 1],
            ['nama' => 'Dokumen Non-disclosure Agreement (DNA)', 'kelompok' => '', 'keterangan' => '', 'is_internal' => 0],
            ['nama' => 'Penetration test', 'kelompok' => 'Dokumen Pengujian', 'keterangan' => '', 'is_internal' => 1],
        ]);
    }
}
