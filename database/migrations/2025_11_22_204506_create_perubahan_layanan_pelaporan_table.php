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
        Schema::create('perubahan_layanan_pelaporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perubahan_layanan_id')->constrained('perubahan_layanan');
            $table->string('tim_pelaksana')->nullable();
            $table->date('tanggal_mulai')->nullable()->comment('tanggal penyelesaian perubahan');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('tanggal_rencana', ['Sesuai Rencana', 'Tidak Sesuai Rencana'])->nullable();
            $table->enum('anggaran', ['Memadai', 'Tidak Memadai'])->nullable();
            $table->string('anggaran_catatan')->nullable();
            $table->enum('sumber_daya_lain', ['Tersedia', 'Tidak Tersedia'])->nullable();
            $table->string('sumber_daya_lain_catatan')->nullable();
            $table->enum('komunikasi_perubahan', ['Dilaksanakan', 'Tidak Dilaksanakan'])->nullable();
            $table->string('komunikasi_perubahan_catatan')->nullable();
            $table->enum('lainnya', ['Dilaksanakan', 'Tidak Dilaksanakan'])->nullable();
            $table->string('lainnya_catatan')->nullable();
            $table->enum('status_pelaksanaan', ['Selesai', 'Parsial', 'Tertunda', 'Gagal'])->nullable();
            $table->string('langkah_implementasi')->nullable();
            $table->string('catatan_khusus')->nullable();
            $table->text('bukti_implementasi')->nullable()->comment('file upload');
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->string('deleted_by', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perubahan_layanan_pelaporan');
    }
};
