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
        Schema::create('area_perubahan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255);
            $table->tinyInteger('is_aktif')
                ->default(1)
                ->comment('0:tidak aktif   1:aktif');

            $table->timestamps();

            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->string('deleted_by', 255)->nullable();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('area_perubahan');
    }
};
