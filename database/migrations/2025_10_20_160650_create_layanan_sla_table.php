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
        Schema::create('katalog_layanan_sla', function (Blueprint $table) {
            $table->id();
            $table->foreignId('katalog_layanan_id')->constrained('katalog_layanan')->cascadeOnDelete();
            $table->string('nama', 512);
            $table->string('deskripsi', 512)->nullable();
            $table->integer('nilai')->default(0);
            $table->string('satuan', 100)->nullable();
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
        Schema::dropIfExists('katalog_layanan_sla');
    }
};
