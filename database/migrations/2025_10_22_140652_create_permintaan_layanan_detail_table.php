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
        Schema::create('permintaan_layanan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_layanan_id')->constrained('permintaan_layanan');
            $table->string('nama_item');
            $table->string('deskripsi_layanan');
            $table->string('banyaknya');
            $table->string('satuan');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_layanan_detail');
    }
};
