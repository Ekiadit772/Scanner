<?php

use App\Http\Controllers\PerubahanLayananPenutupanController;
use Illuminate\Support\Facades\Route;
use App\Models\StatusPenangananInsiden;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\SyaratController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AreaPerubahanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisPeranController;
use App\Http\Controllers\JenisSyaratController;
use App\Http\Controllers\JenisInsidenController;
use App\Http\Controllers\JenisLayananController;
use App\Http\Controllers\InsidenLayananController;
use App\Http\Controllers\KatalogLayananController;
use App\Http\Controllers\KatalogAplikasiController;
use App\Http\Controllers\KelompokLayananController;
use App\Http\Controllers\PenyediaLayananController;
use App\Http\Controllers\PerangkatDaerahController;
use App\Http\Controllers\StatusTransaksiController;
use App\Http\Controllers\PerubahanLayananController;
use App\Http\Controllers\KategoriPerubahanController;
use App\Http\Controllers\PermintaanLayananController;
use App\Http\Controllers\PerubahanLayananPelaporanController;
use App\Http\Controllers\PerubahanLayananSelesaiController;
use App\Http\Controllers\RealisasiBelanjaTikController;
use App\Http\Controllers\StatusPenangananInsidenController;
use App\Http\Controllers\RekapitulasiPenggunaanLayananController;
use App\Models\PerubahanLayananPelaporan;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'getUsers'])
            ->middleware('permission:Pengguna Aplikasi;Lihat');

        Route::get('/{id}/edit', [UserController::class, 'edit'])
            ->middleware('permission:Pengguna Aplikasi;Edit');

        Route::post('/', [UserController::class, 'store'])
            ->middleware('permission:Pengguna Aplikasi;Tambah');

        Route::put('/update/{id}', [UserController::class, 'update'])
            ->middleware('permission:Pengguna Aplikasi;Edit');

        Route::delete('/{id}/destroy', [UserController::class, 'destroy'])
            ->middleware('permission:Pengguna Aplikasi;Hapus');

        Route::get('/get-bidang/{perangkatDaerahId}', [UserController::class, 'getBidang'])
            ->name('getBidang');
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])
            ->name('index')
            ->middleware('permission:Role Pengguna Aplikasi;Lihat');

        Route::get('/create', [RoleController::class, 'create'])
            ->name('create')
            ->middleware('permission:Role Pengguna Aplikasi;Tambah');

        Route::post('/store', [RoleController::class, 'store'])
            ->name('store')
            ->middleware('permission:Role Pengguna Aplikasi;Tambah');

        Route::get('/{id}/edit', [RoleController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Role Pengguna Aplikasi;Edit');

        Route::put('/update/{id}', [RoleController::class, 'update'])
            ->name('update')
            ->middleware('permission:Role Pengguna Aplikasi;Edit');

        Route::delete('/{id}/destroy', [RoleController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Role Pengguna Aplikasi;Hapus');

        Route::get('/get-roles', [RoleController::class, 'getRoles'])
            ->name('get-roles')
            ->middleware('permission:Role Pengguna Aplikasi;Lihat');
    });

    Route::get('/roles/{id}/permissions', [RoleController::class, 'apiPermissions']);
    Route::post('/roles/{id}/permissions', [RoleController::class, 'apiUpdatePermissions']);

    Route::prefix('perangkat-daerah')->group(function () {
        Route::post('/store', [PerangkatDaerahController::class, 'store'])
            ->name('store')
            ->middleware('permission:Perangkat Daerah;Tambah');

        Route::get('/{id}/edit', [PerangkatDaerahController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Perangkat Daerah;Edit');

        Route::put('/update/{id}', [PerangkatDaerahController::class, 'update'])
            ->name('update')
            ->middleware('permission:Perangkat Daerah;Edit');

        Route::delete('/{id}/destroy', [PerangkatDaerahController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Perangkat Daerah;Hapus');

        Route::get('/get-perangkat-daerah', [PerangkatDaerahController::class, 'getPerangkatDaerah'])
            ->name('get-perangkat-daerah')
            ->middleware('permission:Perangkat Daerah;Lihat');

        Route::get('/tte/{filename}', [PerangkatDaerahController::class, 'viewTTE'])
            ->name('perangkat-daerah.tte');
    });

    Route::prefix('jenis-layanan')->group(function () {
        Route::post('/store', [JenisLayananController::class, 'store'])
            ->name('store')
            ->middleware('permission:tambah jenis layanan');

        Route::get('/{id}/edit', [JenisLayananController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:edit jenis layanan');

        Route::put('/update/{id}', [JenisLayananController::class, 'update'])
            ->name('update')
            ->middleware('permission:edit jenis layanan');

        Route::delete('/{id}/destroy', [JenisLayananController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:hapus jenis layanan');

        Route::get('/get-jenis-layanan', [JenisLayananController::class, 'getJenisLayanan'])
            ->name('get-jenis-layanan')
            ->middleware('permission:lihat jenis layanan');
    });

    Route::prefix('jenis-syarat')->group(function () {
        Route::post('/store', [JenisSyaratController::class, 'store'])
            ->name('store')
            ->middleware('permission:Jenis Syarat;Tambah');

        Route::get('/{id}/edit', [JenisSyaratController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Jenis Syarat;Edit');

        Route::put('/update/{id}', [JenisSyaratController::class, 'update'])
            ->name('update')
            ->middleware('permission:Jenis Syarat;Edit');

        Route::delete('/{id}/destroy', [JenisSyaratController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Jenis Syarat;Hapus');

        Route::get('/get-jenis-syarat', [JenisSyaratController::class, 'getJenisSyarat'])
            ->name('get-jenis-syarat')
            ->middleware('permission:Jenis Syarat;Lihat');
    });

    Route::prefix('kategori-perubahan')->group(function () {
        Route::post('/store', [KategoriPerubahanController::class, 'store'])
            ->name('store')
            ->middleware('permission:Kategori Perubahan;Tambah');

        Route::get('/{id}/edit', [KategoriPerubahanController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Kategori Perubahan;Edit');

        Route::put('/update/{id}', [KategoriPerubahanController::class, 'update'])
            ->name('update')
            ->middleware('permission:Kategori Perubahan;Edit');

        Route::delete('/{id}/destroy', [KategoriPerubahanController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Kategori Perubahan;Hapus');

        Route::get('/get-kategori-perubahan', [KategoriPerubahanController::class, 'getKategoriPerubahan'])
            ->name('get-kategori-perubahan')
            ->middleware('permission:Kategori Perubahan;Lihat');
    });

    Route::prefix('status-penanganan-insiden')->group(function () {
        Route::post('/store', [StatusPenangananInsidenController::class, 'store'])
            ->name('store')
            ->middleware('permission:Status Penanganan Insiden;Tambah');

        Route::get('/{id}/edit', [StatusPenangananInsidenController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Status Penanganan Insiden;Edit');

        Route::put('/update/{id}', [StatusPenangananInsidenController::class, 'update'])
            ->name('update')
            ->middleware('permission:Status Penanganan Insiden;Edit');

        Route::delete('/{id}/destroy', [StatusPenangananInsidenController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Status Penanganan Insiden;Hapus');

        Route::get('/get-status-penanganan-insiden', [StatusPenangananInsidenController::class, 'getStatusPenangananInsiden'])
            ->name('get-status-penanganan-insiden')
            ->middleware('permission:Status Penanganan Insiden;Lihat');
    });

    Route::prefix('jenis-insiden')->group(function () {
        Route::post('/store', [JenisInsidenController::class, 'store'])
            ->name('store')
            ->middleware('permission:Jenis Insiden;Tambah');

        Route::get('/{id}/edit', [JenisInsidenController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Jenis Insiden;Edit');

        Route::put('/update/{id}', [JenisInsidenController::class, 'update'])
            ->name('update')
            ->middleware('permission:Jenis Insiden;Edit');

        Route::delete('/{id}/destroy', [JenisInsidenController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Jenis Insiden;Hapus');

        Route::get('/get-jenis-insiden', [JenisInsidenController::class, 'getJenisInsiden'])
            ->name('get-jenis-insiden')
            ->middleware('permission:Jenis Insiden;Lihat');
    });

    Route::prefix('satuan')->group(function () {
        Route::post('/store', [SatuanController::class, 'store'])
            ->name('store')
            ->middleware('permission:Satuan;Tambah');

        Route::get('/{id}/edit', [SatuanController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Satuan;Edit');

        Route::put('/update/{id}', [SatuanController::class, 'update'])
            ->name('update')
            ->middleware('permission:Satuan;Edit');

        Route::delete('/{id}/destroy', [SatuanController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Satuan;Hapus');

        Route::get('/get-satuan', [SatuanController::class, 'getSatuan'])
            ->name('get-satuan')
            ->middleware('permission:Satuan;Lihat');
    });

    Route::prefix('kelompok-layanan')->group(function () {
        Route::post('/store', [KelompokLayananController::class, 'store'])
            ->name('store')
            ->middleware('permission:Kelompok Layanan;Tambah');

        Route::get('/{id}/edit', [KelompokLayananController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Kelompok Layanan;Edit');

        Route::put('/update/{id}', [KelompokLayananController::class, 'update'])
            ->name('update')
            ->middleware('permission:Kelompok Layanan;Edit');

        Route::delete('/{id}/destroy', [KelompokLayananController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Kelompok Layanan;Hapus');

        Route::get('/get-kelompok-layanan', [KelompokLayananController::class, 'getKelompokLayanan'])
            ->name('get-kelompok-layanan')
            ->middleware('permission:Kelompok Layanan;Lihat');
    });

    Route::prefix('jenis-peran')->name('jenis-peran.')->group(function () {
        Route::post('/store', [JenisPeranController::class, 'store'])
            ->name('store')
            ->middleware('permission:Jenis Peran;Tambah');

        Route::get('/{id}/edit', [JenisPeranController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Jenis Peran;Edit');

        Route::put('/update/{id}', [JenisPeranController::class, 'update'])
            ->name('update')
            ->middleware('permission:Jenis Peran;Edit');

        Route::delete('/{id}/destroy', [JenisPeranController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Jenis Peran;Hapus');

        Route::get('/get-jenis-peran', [JenisPeranController::class, 'getJenisPeran'])
            ->name('get-jenis-peran')
            ->middleware('permission:Jenis Peran;Lihat');
    });

    Route::prefix('katalog-layanan')->group(function () {
        Route::post('/{id}/update-status', [KatalogLayananController::class, 'updateStatus'])
            ->name('updateStatus')
            ->middleware('permission:Katalog Layanan;Verifikasi');

        Route::post('/store', [KatalogLayananController::class, 'store'])
            ->name('store')
            ->middleware('permission:Katalog Layanan;Pendaftaran');

        Route::get('/{id}/edit', [KatalogLayananController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Katalog Layanan;Edit');

        Route::put('/update/{id}', [KatalogLayananController::class, 'update'])
            ->name('update')
            ->middleware('permission:Katalog Layanan;Edit');

        Route::delete('/{id}/destroy', [KatalogLayananController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Katalog Layanan;Hapus');

        Route::get('/get-katalog-layanan', [KatalogLayananController::class, 'getKatalogLayanan'])
            ->name('get-katalog-layanan')
            ->middleware('permission:Katalog Layanan;Lihat Riwayat');

        Route::get('/get-by-status/{status}', [KatalogLayananController::class, 'getByStatus'])
            ->name('get-by-status')
            ->middleware('permission:Katalog Layanan;Lihat Riwayat');

        Route::post('/update-status/{id}', [KatalogLayananController::class, 'updateStatus'])
            ->name('updateStatus')
            ->middleware('permission:Katalog Layanan;Verifikasi');

        Route::get('/perangkat-daerah', [KatalogLayananController::class, 'getPerangkatDaerah']);
        Route::get('/penyedia-layanan', [KatalogLayananController::class, 'getPenyediaLayanan']);
        Route::get('/{id}/preview', [KatalogLayananController::class, 'preview']);
        Route::get('/summary', [KatalogLayananController::class, 'getSummary']);
    });

    Route::prefix('permintaan-layanan')->group(function () {
        Route::post('/update-status/{id}', [PermintaanLayananController::class, 'updateStatus'])
            ->name('updateStatus')
            ->middleware('permission:Permintaan Layanan;Verifikasi');


        Route::post('/store', [PermintaanLayananController::class, 'store'])
            ->name('store')
            ->middleware('permission:Permintaan Layanan;Buat Permintaan');

        Route::get('/{id}/edit', [PermintaanLayananController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Permintaan Layanan;Edit');

        Route::put('/update/{id}', [PermintaanLayananController::class, 'update'])
            ->name('update')
            ->middleware('permission:Permintaan Layanan;Edit');

        Route::delete('/{id}/destroy', [PermintaanLayananController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Permintaan Layanan;Hapus');

        Route::post('/katalog-aplikasi/{id}', [PermintaanLayananController::class, 'storeKatalogAplikasi'])
            ->name('katalog-aplikasi.store')
            ->middleware('permission:Permintaan Layanan;Verifikasi');;

        Route::get('/get-penyedia-layanan', [PermintaanLayananController::class, 'getPenyediaLayanan'])
            ->name('get-penyedia-layanan')
            ->middleware('permission:Permintaan Layanan;Lihat Riwayat');

        Route::get('/get-permintaan-layanan', [PermintaanLayananController::class, 'getPermintaanLayanan'])
            ->name('get-permintaan-layanan')
            ->middleware('permission:Permintaan Layanan;Lihat Riwayat');

        Route::get('/get-by-status/{status}', [PermintaanLayananController::class, 'getByStatus'])
            ->name('get-by-status')
            ->middleware('permission:Permintaan Layanan;Verifikasi');

        Route::get('/get-perangkat-daerah', [PermintaanLayananController::class, 'getPerangkatDaerah'])
            ->name('getPerangkatDaerah');

        Route::get('/get-pemohon-distinct', [PermintaanLayananController::class, 'getPemohonDistinct'])
            ->name('get-pemohon-distinct')
            ->middleware('permission:Permintaan Layanan;Lihat Riwayat');

        Route::get('/get-penyedia-perangkat-distinct', [PermintaanLayananController::class, 'getPenyediaPerangkatDistinct'])
            ->name('get-penyedia-perangkat-distinct')
            ->middleware('permission:Permintaan Layanan;Lihat Riwayat');

        Route::get('/get-bidang/{perangkatDaerahId}', [PermintaanLayananController::class, 'getBidang'])
            ->name('getBidang');

        Route::get('/get-bidang', [PermintaanLayananController::class, 'getBidangAll'])
            ->name('getBidangAll')
            ->middleware('permission:Permintaan Layanan;Lihat Riwayat');

        Route::get('/get-layanan/{penyediaLayananId}', [PermintaanLayananController::class, 'getLayanan'])
            ->name('getLayanan');

        Route::get('/get-syarat/{layananId}', [PermintaanLayananController::class, 'getSyarat'])
            ->name('getSyarat');

        Route::get('/generate-no', [PermintaanLayananController::class, 'generateNo']);
        Route::get('/form/{id}', [SyaratController::class, 'form'])->name('syarat.form');
        Route::get('/perangkat-daerah/users', [PermintaanLayananController::class, 'getUsersByPerangkatDaerah']);
        Route::get('/summary', [PermintaanLayananController::class, 'getSummaryPermintaanLayanan']);
    });

    Route::prefix('master-penyedia-layanan')->group(function () {
        Route::post('/store', [PenyediaLayananController::class, 'store'])
            ->name('store')
            ->middleware('permission:Penyedia Layanan;Tambah');

        Route::get('/{id}/edit', [PenyediaLayananController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Penyedia Layanan;Edit');

        Route::get('/{id}/show', [PenyediaLayananController::class, 'show'])
            ->name('show')
            ->middleware('permission:Penyedia Layanan;Lihat');

        Route::put('/update/{id}', [PenyediaLayananController::class, 'update'])
            ->name('update')
            ->middleware('permission:Penyedia Layanan;Edit');

        Route::delete('/{id}/destroy', [PenyediaLayananController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Penyedia Layanan;Hapus');

        Route::get('/get-master-penyedia-layanan', [PenyediaLayananController::class, 'getPenyediaLayanan'])
            ->name('get-master-penyedia-layanan')
            ->middleware('permission:Penyedia Layanan;Lihat');
    });

    // REKAPITULASI PENGGUNAAN LAYANAN
    Route::prefix('rekapitulasi-penggunaan-layanan')->group(function () {

        Route::get('/get-rekapitulasi-penggunaan-layanan', [RekapitulasiPenggunaanLayananController::class, 'getLayanan'])
            ->name('get-rekapitulasi-penggunaan-layanan')
            ->middleware('permission:Rekapitulasi Penggunaan Layanan;Lihat');

        Route::get(
            '/get-rekapitulasi-penggunaan-layanan/{layanan_id}/pengajuan',
            [RekapitulasiPenggunaanLayananController::class, 'getPengajuanByLayanan']
        )->name('get-rekapitulasi-penggunaan-layanan.subgrid');

        Route::get('/report-perangkat-daerah', [RekapitulasiPenggunaanLayananController::class, 'getPerangkatDaerah']);
        Route::get('/report-penyedia-layanan', [RekapitulasiPenggunaanLayananController::class, 'getPenyediaLayanan']);
    });

    // Insiden Layanan
    Route::prefix('insiden-layanan')->group(function () {
        Route::post('/update-status/{id}', [InsidenLayananController::class, 'updateStatus'])
            ->name('updateStatus')
            ->middleware('permission:Manajemen Insiden;Penugasan Penanganan Insiden|Manajemen Insiden;Penanganan Insiden|Manajemen Insiden;Penulusuran Insiden|Manajemen Insiden;Penutupan Penanganan');

        Route::post('/store', [InsidenLayananController::class, 'store'])
            ->name('store')
            ->middleware('permission:Manajemen Insiden;Pelaporan Insiden');

        Route::get('/{id}/edit', [InsidenLayananController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Manajemen Insiden;Edit');

        Route::put('/update/{id}', [InsidenLayananController::class, 'update'])
            ->name('update')
            ->middleware('permission:Manajemen Insiden;Edit');

        Route::delete('/{id}/destroy', [InsidenLayananController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Manajemen Insiden:Hapus');

        Route::get('/get-penyedia-layanan', [InsidenLayananController::class, 'getPenyediaLayanan'])
            ->name('get-penyedia-layanan')
            ->middleware('permission:Manajemen Insiden;Lihat Riwayat');

        Route::get('/get-insiden-layanan', [InsidenLayananController::class, 'getinsidenLayanan'])
            ->name('get-insiden-layanan')
            ->middleware('permission:Manajemen Insiden;Lihat Riwayat');

        Route::get('/get-by-status/{status}', [InsidenLayananController::class, 'getByStatus'])
            ->name('get-by-status')
            ->middleware('permission:Manajemen Insiden;Penugasan Penanganan Insiden|Manajemen Insiden;Penanganan Insiden|Manajemen Insiden;Penulusuran Insiden|Manajemen Insiden;Penutupan Penanganan');

        Route::get('/get-perangkat-daerah', [InsidenLayananController::class, 'getPerangkatDaerah'])
            ->name('getPerangkatDaerah');

        Route::get('/get-pemohon-distinct', [InsidenLayananController::class, 'getPemohonDistinct'])
            ->name('get-pemohon-distinct')
            ->middleware('permission:Manajemen Insiden;Lihat Riwayat');

        Route::get('/get-penyedia-perangkat-distinct', [InsidenLayananController::class, 'getPenyediaPerangkatDistinct'])
            ->name('get-penyedia-perangkat-distinct')
            ->middleware('permission:Manajemen Insiden;Lihat Riwayat');

        Route::get('/get-bidang/{perangkatDaerahId}', [InsidenLayananController::class, 'getBidang'])
            ->name('getBidang');

        Route::get('/get-bidang', [InsidenLayananController::class, 'getBidangAll'])
            ->name('getBidangAll')
            ->middleware('permission:Manajemen Insiden;Lihat Riwayat');

        Route::get('/get-layanan/{penyediaLayananId}', [InsidenLayananController::class, 'getLayanan'])
            ->name('getLayanan');

        Route::get('/get-syarat/{layananId}', [InsidenLayananController::class, 'getSyarat'])
            ->name('getSyarat');

        Route::get('/get-jenis-insiden', [InsidenLayananController::class, 'getJenisInsiden'])
            ->name('getJenisInsiden');

        Route::get('/generate-no', [InsidenLayananController::class, 'generateNo']);
        Route::get('/form/{id}', [SyaratController::class, 'form'])->name('syarat.form');
        Route::get('/perangkat-daerah/users', [InsidenLayananController::class, 'getUsersByPerangkatDaerah']);
        Route::get('/summary', [InsidenLayananController::class, 'getSummaryinsidenLayanan']);
    });

    Route::prefix('perubahan-layanan')->group(function () {
        Route::post('/update-status/{id}', [PerubahanLayananController::class, 'updateStatus'])
            ->name('updateStatus')
            ->middleware('permission:Manajemen Perubahan;Persetujuan Perubahan|Manajemen Perubahan;Pelaksanaan Perubahan|Manajemen Perubahan;Penelusuran|Manajemen Perubahan;Penutupan|Manajemen Perubahan;Pelaporan');

        Route::post('/update-status-ditolak/{id}', [PerubahanLayananController::class, 'updateStatusTolak'])
            ->name('updateStatusTolak')
            ->middleware('permission:Manajemen Perubahan;Persetujuan Perubahan|Manajemen Perubahan;Pelaksanaan Perubahan|Manajemen Perubahan;Penelusuran|Manajemen Perubahan;Penutupan|Manajemen Perubahan;Pelaporan');

        Route::post('/update-status-pelaporan/{id}', [PerubahanLayananPelaporanController::class, 'updateStatusPelaporan'])
            ->name('updateStatusPelaporan')
            ->middleware('permission:Manajemen Perubahan;Persetujuan Perubahan|Manajemen Perubahan;Pelaksanaan Perubahan|Manajemen Perubahan;Penelusuran|Manajemen Perubahan;Penutupan|Manajemen Perubahan;Pelaporan');

        Route::post('/update-status-penutupan/{id}', [PerubahanLayananPenutupanController::class, 'updateStatusPenutupan'])
            ->name('updateStatusPenutupan')
            ->middleware('permission:Manajemen Perubahan;Persetujuan Perubahan|Manajemen Perubahan;Pelaksanaan Perubahan|Manajemen Perubahan;Penelusuran|Manajemen Perubahan;Penutupan|Manajemen Perubahan;Pelaporan');

        Route::post('/store', [PerubahanLayananController::class, 'store'])
            ->name('store')
            ->middleware('permission:Manajemen Perubahan;Permintaan Perubahan');

        Route::get('/{id}/edit', [PerubahanLayananController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Manajemen Perubahan;Edit');

        Route::put('/update/{id}', [PerubahanLayananController::class, 'update'])
            ->name('update')
            ->middleware('permission:Manajemen Perubahan;Edit');

        Route::delete('/{id}/destroy', [PerubahanLayananController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:Manajemen Perubahan;Hapus');

        Route::get('/get-penyedia-layanan', [PerubahanLayananController::class, 'getPenyediaLayanan'])
            ->name('get-penyedia-layanan')
            ->middleware('permission:Manajemen Perubahan;Lihat Riwayat');

        Route::get('/get-perubahan-layanan', [PerubahanLayananController::class, 'getperubahanLayanan'])
            ->name('get-perubahan-layanan')
            ->middleware('permission:Manajemen Perubahan;Lihat Riwayat');

        Route::get('/get-by-status/{status}', [PerubahanLayananController::class, 'getByStatus'])
            ->name('get-by-status')
            ->middleware('permission:Manajemen Perubahan;Persetujuan Perubahan|Manajemen Perubahan;Pelaksanaan Perubahan|Manajemen Perubahan;Penelusuran|Manajemen Perubahan;Penutupan');

        Route::get('/get-perangkat-daerah', [PerubahanLayananController::class, 'getPerangkatDaerah'])
            ->name('getPerangkatDaerah');

        Route::get('/get-pemohon-distinct', [PerubahanLayananController::class, 'getPemohonDistinct'])
            ->name('get-pemohon-distinct')
            ->middleware('permission:Manajemen Perubahan;Lihat Riwayat');

        Route::get('/get-penyedia-perangkat-distinct', [PerubahanLayananController::class, 'getPenyediaPerangkatDistinct'])
            ->name('get-penyedia-perangkat-distinct')
            ->middleware('permission:Manajemen Perubahan;Lihat Riwayat');

        Route::get('/get-bidang/{perangkatDaerahId}', [PerubahanLayananController::class, 'getBidang'])
            ->name('getBidang');

        Route::get('/get-bidang', [PerubahanLayananController::class, 'getBidangAll'])
            ->name('getBidangAll')
            ->middleware('permission:Manajemen Perubahan;Lihat Riwayat');

        Route::get('/get-layanan/{penyediaLayananId}', [PerubahanLayananController::class, 'getLayanan'])
            ->name('getLayanan');

        Route::get('/get-syarat/{layananId}', [PerubahanLayananController::class, 'getSyarat'])
            ->name('getSyarat');

        Route::get('/get-kategori-perubahan', [PerubahanLayananController::class, 'getKategoriPerubahan'])
            ->name('getKategoriPerubahan');

        Route::get('/generate-no', [PerubahanLayananController::class, 'generateNo']);
        Route::get('/form/{id}', [SyaratController::class, 'form'])->name('syarat.form');
        Route::get('/perangkat-daerah/users', [PerubahanLayananController::class, 'getUsersByPerangkatDaerah']);
        Route::get('/summary', [PerubahanLayananController::class, 'getSummaryPerubahanLayanan']);
    });

    Route::prefix('realisasi-belanja-tik')->group(function () {
        Route::get('/list', [RealisasiBelanjaTikController::class, 'getList'])
            ->name('getList');

        Route::get('/summary', [RealisasiBelanjaTikController::class, 'summary'])
            ->name('summary');
    });

    Route::prefix('katalog-aplikasi')->group(function () {
        Route::get('/get-katalog-aplikasi', [KatalogAplikasiController::class, 'getKatalogAplikasi'])
            ->name('get-katalog-aplikasi')
            ->middleware('permission:Katalog Layanan;Lihat Riwayat');
    });
    // status filter
    Route::get('/status-transaksi', [StatusTransaksiController::class, 'getByJenis']);

    Route::prefix('area-perubahan')->group(function () {
        Route::get('/get-area-perubahan-data', [AreaPerubahanController::class, 'getAreaPerubahanData'])
            ->name('get-area-perubahan-data')
            ->middleware('permission:Manajemen Perubahan;Persetujuan Perubahan|Manajemen Perubahan;Pelaksanaan Perubahan|Manajemen Perubahan;Penelusuran|Manajemen Perubahan;Penutupan');
    });

    // Dashboard data
    Route::get('/dashboard/cardsData', [DashboardController::class, 'cardsData']);
    Route::get('/dashboard/chartData', [DashboardController::class, 'chartData']);
    Route::get('/dashboard/layananByKelompok/{kelompok}', [DashboardController::class, 'layananByKelompok']);
    Route::get('/dashboard/statusChartData', [DashboardController::class, 'statusChartData']);
    Route::get('/dashboard/permintaanByStatus/{status}', [DashboardController::class, 'permintaanByStatus']);
    Route::get('/dashboard/perubahanChartData', [DashboardController::class, 'perubahanChartData']);
    Route::get('/dashboard/perubahanByStatus/{status}', [DashboardController::class, 'perubahanByStatus']);
    Route::get('/dashboard/insidenChartData', [DashboardController::class, 'insidenChartData']);
    Route::get('/dashboard/insidenByStatus/{status}', [DashboardController::class, 'insidenByStatus']);
});
