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
        Schema::create('status_transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_transaksi');
            $table->string('nama_status');
            $table->string('keterangan')->nullable();
            $table->tinyInteger('is_aktif')->default(1)->comment('1=aktif,0=tidak aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_transaksi');
    }
};
