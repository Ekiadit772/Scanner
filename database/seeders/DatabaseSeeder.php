<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Satuan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\JenisPeran;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PerangkatDaerahSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(JenisPeranSeeder::class);
        $this->call(PenyediaLayanan::class);
        $this->call(KelompokLayananSeeder::class);
        $this->call(StatusTransaksiSeeder::class);
        $this->call(JenisSyaratSeeder::class);
        $this->call(JenisLayananSeeder::class);
        $this->call(AreaPerubahanSeeder::class);
        // $this->call(LayananSeeder::class);
        // $this->call(PermintaanLayananSeeder::class);
        // $this->call(PerangkatDaerahSeeder::class);
        // $this->call(JenisPeranSeeder::class);

        Satuan::create([
            'nama' => 'Unit',
        ]);
    }
}
