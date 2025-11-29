<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SyaratLainnya extends Model
{
    use SoftDeletes;

    protected $table = 'syarat_lainnya';

    protected $fillable = [
        'permintaan_layanan_syarat_id',
        'perubahan_layanan_id',
        'insiden_layanan_syarat_id',
        'nama',
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
