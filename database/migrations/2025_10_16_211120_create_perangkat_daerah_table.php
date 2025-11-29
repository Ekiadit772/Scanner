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
        Schema::create('perangkat_daerah', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama')->unique();
            $table->string('kadis_nama', 150)->nullable();
            $table->string('kadis_nip', 50)->nullable();
            $table->string('kadis_tte', 100)->nullable()->comment('path storage/app/private/');
            $table->timestamps();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perangkat_daerah');
    }
};
