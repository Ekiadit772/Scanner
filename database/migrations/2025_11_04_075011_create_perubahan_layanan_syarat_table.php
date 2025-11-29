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
        Schema::create('perubahan_layanan_syarat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perubahan_layanan_id')
                ->constrained('perubahan_layanan')
                ->cascadeOnDelete();
            $table->foreignId('layanan_syarat_id')
                ->nullable()
                ->constrained('katalog_layanan_syarat')
                ->nullOnDelete();
            $table->tinyInteger('is_approve')->default(0)->comment('0:blm-disetujui 1:disetujui');
            $table->foreignId('jenis_syarat_id')
                ->nullable()
                ->constrained('jenis_syarat')
                ->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perubahan_layanan_syarat');
    }
};
