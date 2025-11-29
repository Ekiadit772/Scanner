<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // ========================
            // MANJEMEN PENGGUNA
            // ========================
            [
                'name' => 'Pengguna Aplikasi;Lihat',
                'group_order' => 8,
                'group_name' => 'Manajemen Pengguna',
            ],
            [
                'name' => 'Pengguna Aplikasi;Tambah',
                'group_order' => 8,
                'group_name' => 'Manajemen Pengguna',
            ],
            [
                'name' => 'Pengguna Aplikasi;Edit',
                'group_order' => 8,
                'group_name' => 'Manajemen Pengguna',
            ],
            [
                'name' => 'Pengguna Aplikasi;Hapus',
                'group_order' => 8,
                'group_name' => 'Manajemen Pengguna',
            ],

            [
                'name' => 'Role Pengguna Aplikasi;Lihat',
                'group_order' => 8,
                'group_name' => 'Manajemen Pengguna',
            ],
            [
                'name' => 'Role Pengguna Aplikasi;Tambah',
                'group_order' => 8,
                'group_name' => 'Manajemen Pengguna',
            ],
            [
                'name' => 'Role Pengguna Aplikasi;Edit',
                'group_order' => 8,
                'group_name' => 'Manajemen Pengguna',
            ],
            [
                'name' => 'Role Pengguna Aplikasi;Hapus',
                'group_order' => 8,
                'group_name' => 'Manajemen Pengguna',
            ],

            // =============================
            // MANAGEMEN DATA MASTER & REF
            // =============================
            ['name' => 'Perangkat Daerah;Lihat', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Perangkat Daerah;Tambah', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Perangkat Daerah;Edit', 'group_order' => 2,  'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Perangkat Daerah;Hapus', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],

            ['name' => 'Jenis Layanan;Lihat', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi', 'is_aktif' => 0],
            ['name' => 'Jenis Layanan;Tambah', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi', 'is_aktif' => 0],
            ['name' => 'Jenis Layanan;Edit', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi', 'is_aktif' => 0],
            ['name' => 'Jenis Layanan;Hapus', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi', 'is_aktif' => 0],

            ['name' => 'Kategori Perubahan;Lihat', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Kategori Perubahan;Tambah', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Kategori Perubahan;Edit', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Kategori Perubahan;Hapus', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],

            ['name' => 'Jenis Insiden;Lihat', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Jenis Insiden;Tambah', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Jenis Insiden;Edit', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Jenis Insiden;Hapus', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],

            ['name' => 'Penyedia Layanan;Lihat', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Penyedia Layanan;Edit', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Penyedia Layanan;Tambah', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Penyedia Layanan;Hapus', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],

            ['name' => 'Satuan;Lihat', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Satuan;Tambah', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Satuan;Edit', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Satuan;Hapus', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            
            ['name' => 'Jenis Peran;Lihat', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Jenis Peran;Tambah', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Jenis Peran;Edit', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Jenis Peran;Hapus', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],

            ['name' => 'Kelompok Layanan;Lihat', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Kelompok Layanan;Tambah', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Kelompok Layanan;Edit', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Kelompok Layanan;Hapus', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],

            ['name' => 'Jenis Syarat;Lihat', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Jenis Syarat;Tambah', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Jenis Syarat;Edit', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Jenis Syarat;Hapus', 'group_order' => 2, 'group_name' => 'Manajemen Data Master & Referensi'],

            ['name' => 'Status Penanganan Insiden;Lihat', 'group_order' => 1, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Status Penanganan Insiden;Tambah', 'group_order' => 1, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Status Penanganan Insiden;Edit', 'group_order' => 1, 'group_name' => 'Manajemen Data Master & Referensi'],
            ['name' => 'Status Penanganan Insiden;Hapus', 'group_order' => 1, 'group_name' => 'Manajemen Data Master & Referensi'],

            // ========================
            // KATALOG LAYANAN
            // ========================
            ['name' => 'Katalog Layanan;Lihat Riwayat', 'group_order' => 3, 'group_name' => 'Katalog Layanan'],
            ['name' => 'Katalog Layanan;Pendaftaran', 'group_order' => 3, 'group_name' => 'Katalog Layanan'],
            ['name' => 'Katalog Layanan;Edit', 'group_order' => 3, 'group_name' => 'Katalog Layanan'],
            ['name' => 'Katalog Layanan;Hapus', 'group_order' => 3, 'group_name' => 'Katalog Layanan'],
            ['name' => 'Katalog Layanan;Verifikasi', 'group_order' => 3, 'group_name' => 'Katalog Layanan'],

            // ========================
            // PERMINTAAN LAYANAN
            // ========================
            ['name' => 'Permintaan Layanan;Lihat Riwayat', 'group_order' => 4, 'group_name' => 'Manajemen Permintaan Layanan'],
            ['name' => 'Permintaan Layanan;Buat Permintaan', 'group_order' => 4, 'group_name' => 'Manajemen Permintaan Layanan'],
            ['name' => 'Permintaan Layanan;Edit', 'group_order' => 4, 'group_name' => 'Manajemen Permintaan Layanan'],
            ['name' => 'Permintaan Layanan;Hapus', 'group_order' => 4, 'group_name' => 'Manajemen Permintaan Layanan'],
            ['name' => 'Permintaan Layanan;Verifikasi', 'group_order' => 4, 'group_name' => 'Manajemen Permintaan Layanan'],

            // ========================
            // INSIDEN LAYANAN
            // ========================
            ['name' => 'Manajemen Insiden;Lihat Riwayat', 'group_order' => 6, 'group_name' => 'Manajemen Insiden'],
            ['name' => 'Manajemen Insiden;Edit', 'group_order' => 6, 'group_name' => 'Manajemen Insiden'],
            ['name' => 'Manajemen Insiden:Hapus', 'group_order' => 6, 'group_name' => 'Manajemen Insiden'],
            ['name' => 'Manajemen Insiden;Pelaporan Insiden', 'group_order' => 6, 'group_name' => 'Manajemen Insiden'],
            ['name' => 'Manajemen Insiden;Penugasan Penanganan Insiden', 'group_order' => 6, 'group_name' => 'Manajemen Insiden'],
            ['name' => 'Manajemen Insiden;Penanganan Insiden', 'group_order' => 6, 'group_name' => 'Manajemen Insiden'],
            ['name' => 'Manajemen Insiden;Penulusuran Insiden', 'group_order' => 6, 'group_name' => 'Manajemen Insiden'],
            ['name' => 'Manajemen Insiden;Penutupan Penanganan', 'group_order' => 6, 'group_name' => 'Manajemen Insiden'],
            
            // ========================
            // MANAJEMEN PERUBAHAN
            // ========================
            ['name' => 'Manajemen Perubahan;Lihat Riwayat', 'group_order' => 5, 'group_name' => 'Manajemen Perubahan'],
            ['name' => 'Manajemen Perubahan;Edit', 'group_order' => 5, 'group_name' => 'Manajemen Perubahan'],
            ['name' => 'Manajemen Perubahan;Hapus', 'group_order' => 5, 'group_name' => 'Manajemen Perubahan'],
            ['name' => 'Manajemen Perubahan;Permintaan Perubahan', 'group_order' => 5, 'group_name' => 'Manajemen Perubahan'],
            ['name' => 'Manajemen Perubahan;Persetujuan Perubahan', 'group_order' => 5, 'group_name' => 'Manajemen Perubahan'],
            ['name' => 'Manajemen Perubahan;Pelaporan', 'group_order' => 5, 'group_name' => 'Manajemen Perubahan'],
            ['name' => 'Manajemen Perubahan;Pelaksanaan Perubahan', 'group_order' => 5, 'group_name' => 'Manajemen Perubahan'],
            ['name' => 'Manajemen Perubahan;Penelusuran', 'group_order' => 5, 'group_name' => 'Manajemen Perubahan'],
            ['name' => 'Manajemen Perubahan;Penutupan', 'group_order' => 5, 'group_name' => 'Manajemen Perubahan'],

            // ========================
            // PELAPORAN
            // ========================
            [
                'name' => 'Rekapitulasi Penggunaan Layanan;Lihat',
                'group_order' => 7,
                'group_name' => 'Pelaporan',
                'is_aktif' => 0
            ],
        ];

        // Insert atau update permission
        foreach ($permissions as $perm) {
            Permission::updateOrCreate(['name' => $perm['name']], $perm);
        }

        // Role
        $admin = Role::firstOrCreate(['name' => 'Super Administrator']);
        $op = Role::firstOrCreate(['name' => 'Operator PMO']);

        // Assign semua permission ke admin
        $admin->syncPermissions(Permission::pluck('name'));

        $op->syncPermissions([
            'Permintaan Layanan;Lihat Riwayat',
            'Permintaan Layanan;Buat Permintaan',
            'Permintaan Layanan;Edit',
            'Permintaan Layanan;Hapus',
        ]);
    }
}
