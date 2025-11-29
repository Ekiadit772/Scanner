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
        Schema::create('syarat_kak', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('permintaan_layanan_syarat_id')->nullable();
            $table->foreignId('perubahan_layanan_id')->nullable();
            $table->foreignId('insiden_layanan_syarat_id')->nullable();

            $table->foreign('permintaan_layanan_syarat_id', 'fk_syarat_kak_permintaan_layanan_syarat')
                ->references('id')
                ->on('permintaan_layanan_syarat')
                ->cascadeOnDelete();

            $table->foreign('perubahan_layanan_id', 'fk_syarat_kak_perubahan_layanan_syarat')
                ->references('id')
                ->on('perubahan_layanan_syarat');

            $table->foreign('insiden_layanan_syarat_id', 'fk_syarat_kak_insiden_layanan_syarat')
                ->references('id')
                ->on('insiden_layanan_syarat');

            $table->string('judul', 500);
            $table->year('tahun');
            $table->double('jumlah_anggaran')->default(0);
            $table->string('sumber_anggaran', 100)->nullable();
            $table->string('nama_ppk', 100)->nullable()->comment('deskripsi ringkas lingkup KAK');
            $table->text('deskripsi')->nullable();
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
        Schema::dropIfExists('syarat_kak');
    }
};
