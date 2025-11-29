<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syarat_dokumen_pengujian', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('permintaan_layanan_syarat_id')->nullable();
            $table->foreignId('perubahan_layanan_syarat_id')->nullable();
            $table->foreignId('insiden_layanan_syarat_id')->nullable();

            $table->foreign(
                'permintaan_layanan_syarat_id',
                'fk_syarat_dokumen_pengujian_permintaan_layanan_syarat'
            )->references('id')->on('permintaan_layanan_syarat');

            $table->foreign(
                'perubahan_layanan_syarat_id',
                'fk_syarat_dokumen_pengujian_perubahan_layanan_syarat'
            )->references('id')->on('perubahan_layanan_syarat');

            $table->foreign(
                'insiden_layanan_syarat_id',
                'fk_syarat_dokumen_pengujian_insiden_layanan_syarat'
            )->references('id')->on('insiden_layanan_syarat');

            $table->string('nomor_dokumen', 50)->nullable();
            $table->string('nama_dokumen', 200)->nullable();
            $table->date('tanggal')->nullable();
            $table->string('penyusun', 100);
            $table->text('deskripsi')->nullable()->comment('deskripsi ringkas hasil test');
            $table->string('dokumen_type')->nullable()->comment('tipe dokumen pengujian');
            $table->string('file_pendukung', 100)->nullable();

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syarat_dokumen_pengujian');
    }
};
