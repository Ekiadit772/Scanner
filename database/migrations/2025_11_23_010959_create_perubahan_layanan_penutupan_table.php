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
        Schema::create('perubahan_layanan_penutupan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perubahan_layanan_id')->constrained('perubahan_layanan');
            $table->date('tanggal_penyelesaian');
            $table->enum('kesesuaian_hasil', ['Sesuai', 'Tidak Sesuai']);
            $table->string('kesesuaian_penjelasan')->nullable();
            $table->enum('dampak_spbe', ['Positif', 'Netral', 'Negatif'])->comment('dampak terhadap spbe kota bandung')->nullable();
            $table->string('dampak_spbe_penjelasan')->nullable();
            $table->enum('persetujuan_penyelesaian', ['Telah Selesai', 'Selesai Sebagian', 'Tidak Berhasil'])->nullable();
            $table->string('persetujuan_catatan')->nullable();
            $table->string('kordinator_spbe')->nullable();
            $table->string('kordinator_jabatan')->nullable();
            
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->string('deleted_by', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perubahan_layanan_penutupan');
    }
};
