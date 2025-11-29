<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PermintaanLayananSyarat;

class SyaratDokumenTeknisPengembangan extends Model
{
    use SoftDeletes;

    protected $table = 'syarat_dokumen_teknis_pengembangan';

    protected $fillable = [
        'permintaan_layanan_syarat_id',
        'perubahan_layanan_syarat_id',
        'insiden_layanan_syarat_id',
        'nomor_dokumen',
        'nama_dokumen',
        'tanggal',
        'penyusun',
        'deskripsi',
        'dokumen_type',
        'file_pendukung',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function permintaanLayananSyarat()
    {
        return $this->belongsTo(PermintaanLayananSyarat::class);
    }
}
