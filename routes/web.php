<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\InsidenLayananController;
use App\Http\Controllers\JenisPeranController;
use App\Http\Controllers\JenisSyaratController;
use App\Http\Controllers\JenisInsidenController;
use App\Http\Controllers\JenisLayananController;
use App\Http\Controllers\KatalogAplikasiController;
use App\Http\Controllers\KatalogLayananController;
use App\Http\Controllers\PetaJalanSpbeController;
use App\Http\Controllers\KelompokLayananController;
use App\Http\Controllers\PenyediaLayananController;
use App\Http\Controllers\PerangkatDaerahController;
use App\Http\Controllers\KategoriPerubahanController;
use App\Http\Controllers\PermintaanLayananController;
use App\Http\Controllers\PerubahanLayananController;
use App\Http\Controllers\RealisasiBelanjaTikController;
use App\Http\Controllers\RekapitulasiPenggunaanLayananController;
use App\Http\Controllers\StatusPenangananInsidenController;
use App\Models\StatusPenangananInsiden;

Route::get('/', fn() => view('welcome'));

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * ===========================
     * USER MANAGEMENT
     * ===========================
     */
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])
            ->name('index')
            ->middleware('permission:Pengguna Aplikasi;Lihat');
    });

    /**
     * ===========================
     * ROLE MANAGEMENT
     * ===========================
     */
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])
            ->name('index')
            ->middleware('permission:Role Pengguna Aplikasi;Lihat');
    });

    /**
     * ===========================
     * PERANGKAT DAERAH MANAGEMENT
     * ===========================
     */
    Route::prefix('perangkat-daerah')->name('perangkat-daerah.')->group(function () {
        Route::get('/', [PerangkatDaerahController::class, 'index'])
            ->name('index')
            ->middleware('permission:Perangkat Daerah;Lihat');
    });

    /**
     * ===========================
     * JENIS LAYANAN MANAGEMENT
     * ===========================
     */
    Route::prefix('jenis-layanan')->name('jenis-layanan.')->group(function () {
        Route::get('/', [JenisLayananController::class, 'index'])
            ->name('index')
            ->middleware('permission:lihat jenis layanan');
    });

    /**
     * ===========================
     * JENIS SYARAT MANAGEMENT
     * ===========================
     */
    Route::prefix('jenis-syarat')->name('jenis-syarat.')->group(function () {
        Route::get('/', [JenisSyaratController::class, 'index'])
            ->name('index')
            ->middleware('permission:Jenis Syarat;Lihat');
    });

    /**
     * ===========================
     * KATEGORI PERUBAHAN MANAGEMENT
     * ===========================
     */
    Route::prefix('kategori-perubahan')->name('kategori-perubahan.')->group(function () {
        Route::get('/', [KategoriPerubahanController::class, 'index'])
            ->name('index')
            ->middleware('permission:Kategori Perubahan;Lihat');
    });

    /**
     * ===========================
     * JENIS INSIDEN MANAGEMENT
     * ===========================
     */
    Route::prefix('jenis-insiden')->name('jenis-insiden.')->group(function () {
        Route::get('/', [JenisInsidenController::class, 'index'])
            ->name('index')
            ->middleware('permission:Jenis Insiden;Lihat');
    });

    /**
     * ===========================
     * SATUAN MANAGEMENT
     * ===========================
     */
    Route::prefix('satuan')->name('satuan.')->group(function () {
        Route::get('/', [SatuanController::class, 'index'])
            ->name('index')
            ->middleware('permission:Satuan;Lihat');
    });

    /**
     * ===========================
     * STATUS PENANGANAN INSIDEN
     * ===========================
     */
    Route::prefix('status-penanganan-insiden')->name('status-penanganan-insiden.')->group(function () {
        Route::get('/', [StatusPenangananInsidenController::class, 'index'])
            ->name('index')
            ->middleware('permission:Status Penanganan Insiden;Lihat');
    });

    /**
     * ===========================
     * katalog layanan
     * ===========================
     */

    Route::prefix('katalog-layanan')->name('katalog-layanan.')->group(function () {
        Route::get('/', [KatalogLayananController::class, 'index'])
            ->name('index')
            ->middleware('permission:Katalog Layanan;Lihat Riwayat');

        Route::get('/verifikasi', [KatalogLayananController::class, 'verifikasi'])
            ->name('verifikasi')
            ->middleware('permission:Katalog Layanan;Verifikasi');

        Route::get('/create', [KatalogLayananController::class, 'create'])
            ->name('create')
            ->middleware('permission:Katalog Layanan;Pendaftaran');

        Route::get('/verifikasi', function () {
            return view('katalog_layanan.verifikasi', ['status' => 6]);
        })->name('verifikasi')->middleware('permission:Katalog Layanan;Verifikasi');

        Route::get('/{id}/preview-page', [KatalogLayananController::class, 'previewPage'])
            ->name('preview');

        Route::get('/{id}/edit', [KatalogLayananController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Katalog Layanan;Edit');

        Route::get('/{id}/show', [KatalogLayananController::class, 'show'])
            ->name('show')
            ->middleware('permission:Katalog Layanan;Lihat Riwayat');
    });

    /**
     * ===========================
     * KELOMPOK LAYANAN MANAGEMENT
     * ===========================
     */
    Route::prefix('kelompok-layanan')->name('kelompok-layanan.')->group(function () {
        Route::get('/', [KelompokLayananController::class, 'index'])
            ->name('index')
            ->middleware('permission:Kelompok Layanan;Lihat');
    });

    /**
     * ===========================
     * JENIS PERAN MANAGEMENT
     * ===========================
     */
    Route::prefix('jenis-peran')->name('jenis-peran.')->group(function () {
        Route::get('/', [JenisPeranController::class, 'index'])
            ->name('index')
            ->middleware('permission:Jenis Peran;Lihat');
    });

    /**
     * ===========================
     * PENYEDIA LAYANAN MANAGEMENT
     * ===========================
     */
    Route::prefix('penyedia-layanan')->name('penyedia-layanan.')->group(function () {
        Route::get('/', [PenyediaLayananController::class, 'index'])
            ->name('index')
            ->middleware('permission:Penyedia Layanan;Lihat');

        Route::get('/create', [PenyediaLayananController::class, 'create'])
            ->name('create')
            ->middleware('permission:Penyedia Layanan;Tambah');

        Route::get('/{id}/edit', [PenyediaLayananController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Penyedia Layanan;Edit');
        Route::get('/{id}/show', [PenyediaLayananController::class, 'show'])
            ->name('show')
            ->middleware('permission:Penyedia Layanan;Hapus');
    });

    /**
     * =============================
     * PERMINTAAN LAYANAN MANAGEMENT
     * =============================
     */
    Route::prefix('permintaan-layanan')->name('permintaan-layanan.')->group(function () {
        Route::get('/', [PermintaanLayananController::class, 'index'])
            ->name('index')
            ->middleware('permission:Permintaan Layanan;Lihat Riwayat');

        Route::get('/verifikasi', function () {
            return view('permintaan_layanan.verifikasi', ['status' => 1]);
        })->name('verifikasi')->middleware('permission:Permintaan Layanan;Verifikasi');

        Route::get('/proses', function () {
            return view('permintaan_layanan.proses', ['status' => 2]);
        })->name('proses')->middleware('permission:Permintaan Layanan;Verifikasi');

        Route::get('/closing', function () {
            return view('permintaan_layanan.closing', ['status' => 3]);
        })->name('closing')->middleware('permission:Permintaan Layanan;Verifikasi');

        Route::get('/{id}/preview-page', [PermintaanLayananController::class, 'previewPage'])
            ->name('preview');

        Route::get('/create', [PermintaanLayananController::class, 'create'])
            ->name('create')
            ->middleware('permission:Permintaan Layanan;Buat Permintaan');

        Route::get('/{id}/edit', [PermintaanLayananController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Permintaan Layanan;Edit');

        Route::get('/{id}/show', [PermintaanLayananController::class, 'show'])
            ->name('show')
            ->middleware('permission:Permintaan Layanan;Lihat Riwayat');
    });

    /**
     * ===========================
     * REPORTING REKAPITULASI PENGGUNAAN LAYANAN
     * ===========================
     */
    Route::prefix('rekapitulasi-penggunaan-layanan')->name('rekapitulasi-penggunaan-layanan.')->group(function () {
        Route::get('/', [RekapitulasiPenggunaanLayananController::class, 'index'])
            ->name('index')
            ->middleware('permission:Rekapitulasi Penggunaan Layanan;Lihat');
    });

    /**
     * ===========================
     * PETA JALAN SPBE
     * ===========================
     */

    Route::prefix('peta-jalan-spbe')->name('peta-jalan-spbe.')->group(function () {
        Route::get('/', [PetaJalanSpbeController::class, 'index'])
            ->name('index');
        // ->middleware('permission:lihat katalog layanan');
    });

    /**
     * ===========================
     * KATALOG APLIKASI
     * ===========================
     */

    Route::prefix('katalog-aplikasi')->name('katalog-aplikasi.')->group(function () {
        Route::get('/', [KatalogAplikasiController::class, 'index'])
            ->name('index');
        // ->middleware('permission:lihat katalog layanan');
    });

    /**
     * ===========================
     * INSIDEN LAYANAN
     * ===========================
     */

    Route::prefix('insiden-layanan')->name('insiden-layanan.')->group(function () {

        Route::get('/', [InsidenLayananController::class, 'index'])
            ->name('index')
            ->middleware('permission:Manajemen Insiden;Lihat Riwayat');

        Route::get('/penugasan-penanganan-insiden', function () {
            return view('insiden_layanan.verifikasi', ['status' => 15]);
        })->name('penugasan')
            ->middleware('permission:Manajemen Insiden;Penugasan Penanganan Insiden');

        Route::get('/penanganan-insiden', function () {
            return view('insiden_layanan.proses', ['status' => 16]);
        })->name('penanganan')
            ->middleware('permission:Manajemen Insiden;Penanganan Insiden');

        Route::get('/penelusuran', function () {
            return view('insiden_layanan.penelusuran', ['status' => 17]);
        })->name('penelusuran')
            ->middleware('permission:Manajemen Insiden;Penulusuran Insiden');

        Route::get('/closing', function () {
            return view('insiden_layanan.closing', ['status' => 18]);
        })->name('closing')
            ->middleware('permission:Manajemen Insiden;Penutupan Penanganan');

        Route::get('/create', [InsidenLayananController::class, 'create'])
            ->name('create')
            ->middleware('permission:Manajemen Insiden;Pelaporan Insiden');

        Route::get('/{id}/preview-page', [InsidenLayananController::class, 'previewPage'])
            ->name('preview');

        Route::get('/{id}/edit', [InsidenLayananController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Manajemen Insiden;Edit');

        Route::get('/{id}/show', [InsidenLayananController::class, 'show'])
            ->name('show')
            ->middleware('permission:Manajemen Insiden;Lihat Riwayat');
    });


    /**
     * =============================
     * PERUBAHAN LAYANAN MANAGEMENT
     * =============================
     */
    Route::prefix('perubahan-layanan')->name('perubahan-layanan.')->group(function () {
        Route::get('/', [PerubahanLayananController::class, 'index'])
            ->name('index')
            ->middleware('permission:Manajemen Perubahan;Lihat Riwayat');

        // Route::get('/', function () {
        //     return view('under-construction');
        // })
        //     ->name('index')
        //     ->middleware('permission:Manajemen Perubahan;Lihat Riwayat');

        Route::get('/persetujuan-perubahan', function () {
            return view('perubahan_layanan.verifikasi', ['status' => 9]);
        })->name('persetujuan')->middleware('permission:Manajemen Perubahan;Persetujuan Perubahan');

        Route::get('/pelaporan-perubahan', function () {
            return view('perubahan_layanan.pelaporan', ['status' => 10]);
        })->name('pelaporan')->middleware('permission:Manajemen Perubahan;Pelaporan Perubahan');

        // Route::get('/pelaksanaan-perubahan', function () {
        //     return view('perubahan_layanan.proses', ['status' => 10]);
        // })->name('pelaksanaan')->middleware('permission:Manajemen Perubahan;Pelaksanaan Perubahan');

        // Route::get('/penelusuran', function () {
        //     return view('perubahan_layanan.penelusuran', ['status' => 11]);
        // })->name('penelusuran')->middleware('permission:Manajemen Perubahan;Penelusuran');

        Route::get('/{id}/verifikasi-pelaporan', [PerubahanLayananController::class, 'pelaporanPage'])
            ->name('verifikasi-pelaporan');

        Route::get('/closing', function () {
            return view('perubahan_layanan.closing', ['status' => 21]);
        })->name('closing')->middleware('permission:Manajemen Perubahan;Penutupan');

        Route::get('/{id}/verifikasi-penutupan', [PerubahanLayananController::class, 'penutupanPage'])
            ->name('verifikasi-penutupan');

        Route::get('/{id}/preview-page', [PerubahanLayananController::class, 'previewPage'])
            ->name('preview');


        Route::get('/create', [PerubahanLayananController::class, 'create'])
            ->name('create')
            ->middleware('permission:Manajemen Perubahan;Permintaan Perubahan');

        Route::get('/{id}/edit', [PerubahanLayananController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:Manajemen Perubahan;Edit');

        Route::get('/{id}/show', [PerubahanLayananController::class, 'show'])
            ->name('show')
            ->middleware('permission:Manajemen Perubahan;Lihat Riwayat');
    });

    /**
     * ===========================
     * INSIDEN LAYANAN
     * ===========================
     */

    Route::prefix('realisasi-belanja-tik')->name('realisasi-belanja-tik.')->group(function () {

        Route::get('/', [RealisasiBelanjaTikController::class, 'index'])
            ->name('index');
        // ->middleware('permission:Manajemen Insiden;Lihat Riwayat');
    });
});

require __DIR__ . '/auth.php';
// require __DIR__ . '/api.php';
