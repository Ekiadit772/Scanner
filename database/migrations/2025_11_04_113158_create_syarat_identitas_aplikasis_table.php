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
        Schema::create('syarat_identitas_aplikasi', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('permintaan_layanan_syarat_id')->nullable();
            $table->foreignId('perubahan_layanan_syarat_id')->nullable();
            $table->foreignId('insiden_layanan_syarat_id')->nullable();

            $table->foreign(
                'permintaan_layanan_syarat_id',
                'fk_syarat_identitas_aplikasi_permintaan_layanan_syarat'
            )->references('id')->on('permintaan_layanan_syarat');

            $table->foreign(
                'perubahan_layanan_syarat_id', 
                'fk_syarat_identitas_aplikasi_perubahan_layanan_syarat'
                )->references('id')->on('perubahan_layanan_syarat');

            $table->foreign(
                'insiden_layanan_syarat_id',
                'fk_syarat_identitas_aplikasi_insiden_layanan_syarat'
                )->references('id')->on('insiden_layanan_syarat');

            $table->string('nama_aplikasi', 500);
            $table->string('pemilik_aplikasi', 100)->nullable();
            $table->string('versi', 30)->nullable();
            $table->text('deskripsi')->nullable()->comment('deskripsi ringkas aplikasi');
            $table->string('url_aplikasi', 100)->nullable();
            $table->string('username_aplikasi', 100)->nullable();
            $table->string('password_aplikasi', 100)->nullable();
            $table->string('file_pendukung', 100)->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syarat_identitas_aplikasi');
    }
};
