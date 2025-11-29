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
        Schema::create('perubahan_layanan', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('perangkat_daerah_pemohon_id')
                ->comment('perangkat daerah yang meminta layanan');

            $table->unsignedBigInteger('perangkat_daerah_id')
                ->comment('perangkat daerah penyedia layanan');

            $table->unsignedBigInteger('penyedia_layanan_id');
            $table->unsignedBigInteger('layanan_id');

            $table->string('no_antrian', 100)
                ->nullable();

            $table->unsignedBigInteger('kategori_perubahan_id')
                ->nullable()
                ->comment('kategori perubahan layanan');

            $table->foreign('kategori_perubahan_id', 'fk_perubahan_layanan_kategori_perubahan')
                ->references('id')
                ->on('kategori_perubahan')
                ->onDelete('set null');

            $table->string('no_permohonan')->nullable();
            $table->date('tanggal');
            $table->string('unit_kerja_pemohon');
            // $table->string('nama_pemohon')->nullable();
            // $table->string('nip_pemohon')->nullable();
            $table->string('jabatan_pemohon')->nullable();
            $table->string('judul_perubahan')->nullable();
            $table->string('deskripsi');
            $table->tinyInteger('is_in_peta_spbe')
                ->default(0)
                ->comment('1:perubahan terdapat pada peta rencana SPBE');
            $table->enum('jenis_perubahan', ['Minor', 'Mayor', 'Darurat'])->nullable();
            $table->string('latar_belakang', 500)->nullable();
            $table->string('tujuan_perubahan', 500)->nullable();
            $table->string('area_perubahan_ids', 50)
                ->nullable()
                ->comment('kumpulan id area_perubahan yg diceklist ex: 1,2,4,5');
            $table->date('jadwal_mulai')->nullable();
            $table->date('jadwal_selesai')->nullable();
            $table->tinyInteger('is_downtime')
                ->default(0)
                ->comment('1:ada downtime dan isi brp lama downtimenya jika ada');
            $table->string('downtime', 100)->nullable();

            $table->unsignedBigInteger('status_id')
                ->nullable()
                ->comment('status terakhir dari perubahan');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('no_permohonan', 'idx_perubahan_layanan_no_permohonan');
            $table->index('no_antrian', 'idx_perubahan_layanan_no_antrian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perubahan_layanan');
    }
};
