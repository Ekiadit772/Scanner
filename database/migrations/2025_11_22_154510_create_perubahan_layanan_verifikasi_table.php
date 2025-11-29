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
        Schema::create('perubahan_layanan_verifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perubahan_layanan_id')->constrained('perubahan_layanan');
            $table->string('dampak_perubahan');
            $table->enum('tingkat_dampak', ['Tinggi', 'Sedang', 'Rendah']);
            $table->enum('kesiapan_personil', ['Tinggi', 'Sedang', 'Rendah']);
            $table->string('kesiapan_personil_catatan');
            $table->enum('kesiapan_organisasi', ['Tinggi', 'Sedang', 'Rendah']);
            $table->string('kesiapan_organisasi_catatan');
            $table->string('risiko_potensial');
            $table->string('rencana_mitigasi');
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
        Schema::dropIfExists('perubahan_layanan_verifikasi');
    }
};
