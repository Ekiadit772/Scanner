<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyediaLayanan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('penyedia_layanan')->insert([
            // 1
            // ['perangkat_daerah_id' => 1, 'nama_bidang' => 'Sekretariat', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            // ['perangkat_daerah_id' => 1, 'nama_bidang' => 'Bidang Umum dan Kepegawaian', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            // ['perangkat_daerah_id' => 1, 'nama_bidang' => 'Bidang Perencanaan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 2
            ['perangkat_daerah_id' => 1, 'nama_bidang' => 'Bidang Pembinaan Sekolah Dasar', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 1, 'nama_bidang' => 'Bidang Pembinaan Sekolah Menengah', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 1, 'nama_bidang' => 'Bidang Guru dan Tenaga Kependidikan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 3
            ['perangkat_daerah_id' => 2, 'nama_bidang' => 'Bidang Pelayanan Kesehatan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 2, 'nama_bidang' => 'Bidang Pencegahan dan Pengendalian Penyakit', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 2, 'nama_bidang' => 'Bidang Sumber Daya Kesehatan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 4
            ['perangkat_daerah_id' => 3, 'nama_bidang' => 'Bidang Jalan dan Jembatan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 3, 'nama_bidang' => 'Bidang Sumber Daya Air', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 3, 'nama_bidang' => 'Bidang Pemeliharaan Infrastruktur', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 5
            ['perangkat_daerah_id' => 4, 'nama_bidang' => 'Bidang Tata Ruang', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 4, 'nama_bidang' => 'Bidang Bina Konstruksi', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 4, 'nama_bidang' => 'Bidang Cipta Karya', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 6
            ['perangkat_daerah_id' => 5, 'nama_bidang' => 'Bidang Perumahan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 5, 'nama_bidang' => 'Bidang Kawasan Permukiman', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 5, 'nama_bidang' => 'Bidang Prasarana dan Sarana', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 7
            ['perangkat_daerah_id' => 6, 'nama_bidang' => 'Bidang Penegakan Perda', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 6, 'nama_bidang' => 'Bidang Trantibum', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 6, 'nama_bidang' => 'Bidang Sumber Daya Aparatur', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 8
            ['perangkat_daerah_id' => 7, 'nama_bidang' => 'Bidang Pencegahan dan Kesiapsiagaan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 7, 'nama_bidang' => 'Bidang Penanggulangan Kebakaran', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 7, 'nama_bidang' => 'Bidang Penyelamatan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 9
            ['perangkat_daerah_id' => 8, 'nama_bidang' => 'Bidang Kedaruratan dan Logistik', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 8, 'nama_bidang' => 'Bidang Rehabilitasi dan Rekonstruksi', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 8, 'nama_bidang' => 'Bidang Pencegahan Bencana', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 10
            ['perangkat_daerah_id' => 9, 'nama_bidang' => 'Bidang Rehabilitasi Sosial', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 9, 'nama_bidang' => 'Bidang Perlindungan dan Jaminan Sosial', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 9, 'nama_bidang' => 'Bidang Pemberdayaan Sosial', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 11
            ['perangkat_daerah_id' => 10, 'nama_bidang' => 'Bidang Hubungan Industrial', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 10, 'nama_bidang' => 'Bidang Penempatan dan Pelatihan Tenaga Kerja', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 10, 'nama_bidang' => 'Bidang Pengawasan Ketenagakerjaan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 12
            ['perangkat_daerah_id' => 11, 'nama_bidang' => 'Bidang Pemberdayaan Perempuan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 11, 'nama_bidang' => 'Bidang Perlindungan Anak', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 11, 'nama_bidang' => 'Bidang Kualitas Hidup dan Keluarga', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 13
            ['perangkat_daerah_id' => 12, 'nama_bidang' => 'Bidang Ketahanan Pangan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 12, 'nama_bidang' => 'Bidang Pertanian', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 12, 'nama_bidang' => 'Bidang Peternakan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 14
            ['perangkat_daerah_id' => 13, 'nama_bidang' => 'Bidang Pengendalian Pencemaran dan Kerusakan Lingkungan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 13, 'nama_bidang' => 'Bidang Pengelolaan Sampah dan Limbah', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 13, 'nama_bidang' => 'Bidang Konservasi Sumber Daya Alam', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 15
            ['perangkat_daerah_id' => 14, 'nama_bidang' => 'Bidang Pelayanan Kependudukan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 14, 'nama_bidang' => 'Bidang Pencatatan Sipil', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 14, 'nama_bidang' => 'Bidang Sistem Informasi Administrasi Kependudukan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 16
            ['perangkat_daerah_id' => 15, 'nama_bidang' => 'Bidang Pengendalian Penduduk', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 15, 'nama_bidang' => 'Bidang Keluarga Berencana', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 15, 'nama_bidang' => 'Bidang Ketahanan dan Kesejahteraan Keluarga', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 17
            ['perangkat_daerah_id' => 16, 'nama_bidang' => 'Bidang Lalu Lintas dan Angkutan Jalan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 16, 'nama_bidang' => 'Bidang Sarana dan Prasarana Transportasi', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 16, 'nama_bidang' => 'Bidang Keselamatan dan Pengawasan', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],

            // 18 (Dinas Kominfo)
            ['perangkat_daerah_id' => 17, 'nama_bidang' => 'Bidang Aplikasi Informatika, Persandian, dan Keamanan Informasi', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 17, 'nama_bidang' => 'Bidang Infrastruktur Teknologi Informasi dan Komunikasi', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 17, 'nama_bidang' => 'Bidang Diseminasi Informasi', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 17, 'nama_bidang' => 'Bidang Data dan Statistik', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 17, 'nama_bidang' => 'Bidang Perencanaan Teknologi Informasi dan Komunikasi', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
            ['perangkat_daerah_id' => 17, 'nama_bidang' => 'Sekretariat', 'created_by' => 'system', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $penyediaLayananList = DB::table('penyedia_layanan')->get();

        foreach ($penyediaLayananList as $penyedia) {
            DB::table('peran_penyedia')->insert([
                'penyedia_layanan_id' => $penyedia->id,
                'jenis_peran_id' => 2,
                'nip' => '123456' . $penyedia->id,
                'nama' => 'Personil ' . $penyedia->nama_bidang,
                'created_by' => 'system',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
