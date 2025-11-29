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
        Schema::create('peran_penyedia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyedia_layanan_id')->constrained('penyedia_layanan')->cascadeOnDelete();
            $table->foreignId('jenis_peran_id')->constrained('jenis_peran')->cascadeOnDelete();
            $table->string('nip', 50)->nullable();
            $table->string('nama', 512);
            $table->string('jabatan', 500)->nullable();
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
        Schema::dropIfExists('peran_penyedia');
    }
};
