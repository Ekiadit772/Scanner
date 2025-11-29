<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syarat_lainnya', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->foreignId('permintaan_layanan_syarat_id')->nullable();
            $table->foreignId('perubahan_layanan_syarat_id')->nullable();
            $table->foreignId('insiden_layanan_syarat_id')->nullable();

            $table->foreign(
                'permintaan_layanan_syarat_id',
                'fk_syarat_lainnya_permintaan_layanan_syarat'
            )->references('id')->on('permintaan_layanan_syarat')->cascadeOnDelete();

            $table->foreign(
                'perubahan_layanan_syarat_id',
                'fk_syarat_lainnya_perubahan_layanan_syarat'
            )->references('id')->on('perubahan_layanan_syarat');

            $table->foreign(
                'insiden_layanan_syarat_id',
                'fk_syarat_lainnya_insiden_layanan_syarat'
            )->references('id')->on('insiden_layanan_syarat');

            $table->string('nama')->comment('nama syarat atau nama dokumen');
            $table->text('deskripsi')->nullable()->comment('deskripsi ringkas');
            $table->string('file_pendukung')->nullable();

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syarat_lainnya');
    }
};
