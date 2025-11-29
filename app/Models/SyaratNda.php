<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PermintaanLayananSyarat;

class SyaratNda extends Model
{
    use SoftDeletes;

    protected $table = 'syarat_nda';

    protected $fillable = [
        'permintaan_layanan_syarat_id',
        'perubahan_layanan_id',
        'insiden_layanan_id',
        'nomor_dokumen',
        'nama_dokumen',
        'tanggal',
        'nama_pihak_1',
        'nama_pihak_2',
        'deskripsi',
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
