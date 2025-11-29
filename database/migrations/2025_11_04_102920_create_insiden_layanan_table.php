<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('insiden_layanan', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('perangkat_daerah_pemohon_id')
                ->comment('perangkat daerah yang meminta layanan');

            $table->unsignedBigInteger('perangkat_daerah_id')
                ->comment('perangkat daerah penyedia layanan');

            $table->unsignedBigInteger('penyedia_layanan_id');
            $table->unsignedBigInteger('layanan_id');

            $table->string('no_antrian', 100)->nullable();

            $table->unsignedBigInteger('jenis_insiden_id')
                ->nullable()
                ->comment('jenis insiden layanan');

            $table->foreign('jenis_insiden_id', 'fk_insiden_layanan_jenis_insiden')
                ->references('id')
                ->on('jenis_insiden')
                ->onDelete('set null');

            $table->string('no_permohonan')->nullable();
            $table->date('tanggal');
            $table->string('unit_kerja_pemohon');
            $table->string('nama_pemohon')->nullable();
            $table->string('nip_pemohon')->nullable();
            $table->string('jabatan_pemohon')->nullable();
            $table->string('telepon_pemohon', 50)->nullable();
            $table->string('email_pemohon', 50)->nullable();

            $table->text('keluhan');

            $table->text('penanganan_insiden')
                ->nullable()
                ->comment('diisi oleh Pelaksana Layanan saat pelaporan');

            $table->text('perangkat_yang_diperlukan')
                ->nullable()
                ->comment('diisi oleh Pelaksana Layanan saat pelaporan');

            $table->unsignedBigInteger('status_penanganan_insiden_id')
                ->nullable()
                ->comment('ref ke tabel status_penanangan_insiden');

            $table->text('keterangan_pelaksanaan')
                ->nullable()
                ->comment('diisi oleh Pelaksana Layanan saat pelaporan, optional');

            // foreign key ke tabel status_penangangan_insiden
            $table->foreign(
                'status_penanganan_insiden_id',
                'fk_insiden_layanan_status_penanganan'
            )
                ->references('id')
                ->on('status_penanganan_insiden');

            $table->unsignedBigInteger('status_id')
                ->nullable()
                ->comment('status terakhir dari perubahan');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('no_permohonan', 'idx_perubahan_layanan_no_permohonan');
            $table->index('no_antrian', 'idx_perubahan_layanan_no_antrian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insiden_layanan');
    }
};
