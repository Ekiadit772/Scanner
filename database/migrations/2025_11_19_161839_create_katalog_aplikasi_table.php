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
        Schema::create('katalog_aplikasi', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('kode', 200);
            $table->string('nama_aplikasi', 255);
            $table->string('nama_ppk', 100);

            $table->unsignedBigInteger('perangkat_daerah_id');

            $table->unsignedBigInteger('rekomendasi_id')->nullable()
                ->comment('opsional hubungkan dengan permintaan layanan rekomendasi sistem informasi');
            $table->unsignedBigInteger('pelaporan_id')->nullable()
                ->comment('opsional hubungkan dengan permintaan layanan Pembangunan sistem informasi (pelaporan)');
            $table->unsignedBigInteger('pengujian_id')->nullable()
                ->comment('opsional hubungkan dengan permintaan layanan Pengujian Celah Keamanan');

            $table->tinyInteger('is_pentest')->default(0)->comment('1=true');
            $table->tinyInteger('is_integrasi')->default(0)->comment('1=true');
            $table->tinyInteger('is_hosting')->default(0)->comment('1=true');
            $table->tinyInteger('is_domain')->default(0)->comment('1=true');

            $table->text('keterangan')->nullable();

            $table->timestamps();

            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->string('deleted_by', 255)->nullable();

            $table->softDeletes(); // deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('katalog_aplikasi');
    }
};
