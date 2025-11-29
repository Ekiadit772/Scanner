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
        Schema::create('permintaan_layanan', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('perangkat_daerah_pemohon_id')
                ->comment('perangkat daerah yang meminta layanan');

            $table->unsignedBigInteger('perangkat_daerah_id')
                ->comment('perangkat daerah penyedia layanan');

            $table->unsignedBigInteger('penyedia_layanan_id');
            $table->unsignedBigInteger('layanan_id');

            $table->string('no_antrian', 100)
                ->nullable();

            $table->string('no_permohonan')->nullable();
            $table->date('tanggal');
            $table->string('unit_kerja_pemohon');
            $table->string('nama_pemohon')->nullable();
            $table->string('nip_pemohon')->nullable();
            $table->string('jabatan_pemohon')->nullable();
            $table->string('telepon_pemohon', 50)->nullable();
            $table->string('email_pemohon', 50)->nullable();
            $table->string('deskripsi_spek');

            $table->unsignedBigInteger('status_id')
                ->nullable()
                ->comment('status terakhir dari permintaan');

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
        Schema::dropIfExists('permintaan_layanan');
    }
};
