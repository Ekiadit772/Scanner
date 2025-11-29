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
        Schema::create('katalog_layanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perangkat_daerah_id')->constrained('perangkat_daerah');
            $table->foreignId('penyedia_layanan_id')->constrained('penyedia_layanan');
            $table->foreignId('kelompok_layanan_id')->constrained('kelompok_layanan');
            $table->foreignId('jenis_layanan_id')->nullable()->constrained('jenis_layanan')->onDelete('cascade');
            $table->foreignId('status_id')
                ->nullable()
                ->comment('status terakhir dari permintaan')
                ->constrained('status_transaksi')
                ->nullOnDelete();
            $table->string('kode', 50)->unique();
            $table->string('nama', 512)->unique();
            $table->string('deskripsi', 512)->nullable();
            $table->integer('tahun');
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
        Schema::dropIfExists('katalog_layanan');
    }
};
