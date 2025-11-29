<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JenisPeranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jenis_peran')->insert([
            ['nama' => 'Pengelola',  'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pelaksana',  'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
