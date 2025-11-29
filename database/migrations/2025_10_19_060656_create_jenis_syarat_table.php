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
        Schema::create('jenis_syarat', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kelompok');
            $table->text('keterangan')->nullable();;
            $table->tinyInteger('is_internal')
                ->default(0)
                ->comment('0: syarat dipenuhi oleh eksternal perangkat daerah; 1: syarat dipenuhi oleh internal diskominfo');

            $table->tinyInteger('is_aktif')
                ->default(1)
                ->comment('0: tidak aktif; 1: aktif');

            $table->tinyInteger('is_custom')
                ->default(0)
                ->comment('1: bisa diubah; 0: tidak bisa diubah');

            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_syarat');
    }
};
