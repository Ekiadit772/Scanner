<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create([
            'name' => 'Super Administrator',
            'level' => 100,
            'jumlah_akun' => 1,
            'perangkat_daerah_id_required' => 0,
            'penyedia_layanan_id_required' => 0,
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);

        $user1 = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@gmail.com',
            'password' => 'password',
            'phone' => '12345678901',
            'nip' => '123456789012345678',
            'created_by' => 'system',
            'updated_by' => 'system',
            'deleted_by' => '',
            'perangkat_daerah_id' => -1,
            'penyedia_layanan_id' => -1, 
        ]);

        $user1->assignRole($admin);

        $adminPD = Role::create([
            'name' => 'Administrator PD',
            'level' => 90,
            'jumlah_akun' => 1,
            'perangkat_daerah_id_required' => 1,
            'penyedia_layanan_id_required' => 0,
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);

        $user2 = User::create([
            'name' => 'Testo',
            'email' => 'testo@gmail.com',
            'password' => 'password',
            'phone' => '12345678902',
            'nip' => '987654321098765432',
            'created_by' => 'system',
            'updated_by' => 'system',
            'deleted_by' => '',
            'perangkat_daerah_id' => 17,
            'penyedia_layanan_id' => 50, 
        ]);

        $user2->assignRole($adminPD);

        $kadin = Role::create([
            'name' => 'Kepala Dinas',
            'level' => 80,
            'jumlah_akun' => 1,
            'perangkat_daerah_id_required' => 1,
            'penyedia_layanan_id_required' => 0,
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);

        $user3 = User::create([
            'name' => 'Kadin',
            'email' => 'kadin@gmail.com',
            'password' => 'password',
            'phone' => '12345678909',
            'nip' => '112233445566778899',
            'created_by' => 'system',
            'updated_by' => 'system',
            'deleted_by' => '',
            'perangkat_daerah_id' => 18,
            'penyedia_layanan_id' => 57, 
        ]);

        $user3->assignRole($kadin);

        $kabid = Role::create([
            'name' => 'Kepala Bidang',
            'level' => 70,
            'jumlah_akun' => 1,
            'perangkat_daerah_id_required' => 1,
            'penyedia_layanan_id_required' => 1,
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);

        $kapokja = Role::create([
            'name' => 'Ketua Tim Pokja',
            'level' => 60,
            'jumlah_akun' => 1,
            'perangkat_daerah_id_required' => 1,
            'penyedia_layanan_id_required' => 1,
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);

        $anggotaPokja = Role::create([
            'name' => 'Anggota Tim Pokja',
            'level' => 50,
            'jumlah_akun' => 3,
            'perangkat_daerah_id_required' => 1,
            'penyedia_layanan_id_required' => 1,
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);

        $operatorPmo = Role::create([
            'name' => 'Operator PMO',
            'level' => 50,
            'jumlah_akun' => 10,
            'perangkat_daerah_id_required' => 1,
            'penyedia_layanan_id_required' => 0,
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);
    }
}
