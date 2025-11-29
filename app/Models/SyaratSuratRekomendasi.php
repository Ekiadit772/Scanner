<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PermintaanLayananSyarat;

class SyaratSuratRekomendasi extends Model
{
    use SoftDeletes;

    protected $table = 'syarat_surat_rekomendasi';

    protected $fillable = [
        'permintaan_layanan_syarat_id',
        'perubahan_layanan_syarat_id',
        'insiden_layanan_syarat_id',
        'nomor_surat',
        'tanggal',
        'nama',
        'nip',
        'jabatan',
        'deskripsi',
        'file_pendukung',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function permintaanLayananSyarat()
    {
        return $this->belongsTo(PermintaanLayananSyarat::class, 'permintaan_layanan_syarat_id');
    }

    public function permintaan()
    {
        return $this->permintaanLayananSyarat
            ? $this->permintaanLayananSyarat->permintaanLayanan
            : null;
    }
}
