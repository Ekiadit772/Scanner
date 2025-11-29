<?php

namespace Database\Seeders;

use App\Models\StatusTransaksi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusTransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            // manajemen_permintaan
            ['jenis_transaksi' => 'manajemen_permintaan', 'nama_status' => 'Dalam Antrian', 'keterangan' => 'Permintaan layanan', 'is_aktif' => true],
            ['jenis_transaksi' => 'manajemen_permintaan', 'nama_status' => 'verifikasi', 'keterangan' => 'Sudah diverifikasi', 'is_aktif' => true],
            ['jenis_transaksi' => 'manajemen_permintaan', 'nama_status' => 'proses', 'keterangan' => 'Pelaporan layanan diproses', 'is_aktif' => true],
            ['jenis_transaksi' => 'manajemen_permintaan', 'nama_status' => 'selesai', 'keterangan' => 'Penutupan permintaan', 'is_aktif' => true],
            ['jenis_transaksi' => 'manajemen_permintaan', 'nama_status' => 'ditolak', 'keterangan' => 'Permintaan layanan ditolak', 'is_aktif' => true],

            // katalog_layanan
            ['jenis_transaksi' => 'katalog_layanan', 'nama_status' => 'Dalam antrian', 'keterangan' => 'Pendaftaran Layanan menunggu verifikasi', 'is_aktif' => true],
            ['jenis_transaksi' => 'katalog_layanan', 'nama_status' => 'Verifikasi', 'keterangan' => 'Pendaftaran Layanan sudah diverifikasi', 'is_aktif' => true],
            ['jenis_transaksi' => 'katalog_layanan', 'nama_status' => 'Ditolak', 'keterangan' => 'Pendaftaran layanan ditolak', 'is_aktif' => true],

            // perubahan_layanan
            ['jenis_transaksi' => 'perubahan_layanan', 'nama_status' => 'Dalam antrian', 'keterangan' => 'Pendaftaran perubahan Layanan menunggu persetujuan', 'is_aktif' => true],
            ['jenis_transaksi' => 'perubahan_layanan', 'nama_status' => 'Disetujui', 'keterangan' => 'Perubahan Layanan disetujui menunggu pelaksanaan', 'is_aktif' => true],
            ['jenis_transaksi' => 'perubahan_layanan', 'nama_status' => 'Dilaksanakan', 'keterangan' => 'Perubahan Layanan disetujui menunggu pelaksanaan', 'is_aktif' => false],
            ['jenis_transaksi' => 'perubahan_layanan', 'nama_status' => 'Penelusuran', 'keterangan' => 'Perubahan Layanan dalam Penelusuran dan Status Implementasi', 'is_aktif' => false],
            ['jenis_transaksi' => 'perubahan_layanan', 'nama_status' => 'Selesai', 'keterangan' => 'Penutupan Permintaan Perubahan', 'is_aktif' => true],
            ['jenis_transaksi' => 'perubahan_layanan', 'nama_status' => 'Ditolak', 'keterangan' => 'Permintaan Perubahan Layanan ditolak', 'is_aktif' => true],
            
            // insiden_layanan
            ['jenis_transaksi' => 'insiden_layanan', 'nama_status' => 'Dalam antrian', 'keterangan' => 'Pendaftaran Layanan insiden menunggu persetujuan', 'is_aktif' => true],
            ['jenis_transaksi' => 'insiden_layanan', 'nama_status' => 'Penugasan', 'keterangan' => 'Penugasan Penanganan Insiden (ke pihak internal, atau pihak terkait lainnya)', 'is_aktif' => true],
            ['jenis_transaksi' => 'insiden_layanan', 'nama_status' => 'Penanganan', 'keterangan' => 'Penanganan Insiden (dilaporkan oleh pelaksana)', 'is_aktif' => true],
            ['jenis_transaksi' => 'insiden_layanan', 'nama_status' => 'Penelusuran', 'keterangan' => 'Penelusuran dan Status Penanganan Insiden', 'is_aktif' => true],
            ['jenis_transaksi' => 'insiden_layanan', 'nama_status' => 'Selesai', 'keterangan' => 'Penutupan Penanganan Insiden', 'is_aktif' => true],
            ['jenis_transaksi' => 'insiden_layanan', 'nama_status' => 'Ditolak', 'keterangan' => 'Pelaporan Insiden ditolak', 'is_aktif' => true],
            
            ['jenis_transaksi' => 'perubahan_layanan', 'nama_status' => 'Pelaporan', 'keterangan' => 'Pelaporan Perubahan', 'is_aktif' => true],
        ];

        foreach ($statuses as $status) {
            StatusTransaksi::create($status);
        }
    }
}
