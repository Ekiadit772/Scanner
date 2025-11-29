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
        Schema::create('perubahan_layanan_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perubahan_layanan_id')->constrained('perubahan_layanan')->cascadeOnDelete();
            $table->foreignId('status_id')->constrained('status_transaksi')->cascadeOnDelete();
            $table->string('keterangan')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perubahan_layanan_status');
    }
};
