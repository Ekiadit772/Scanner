<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayananSyarat extends Model
{
    use SoftDeletes;

    protected $table = 'katalog_layanan_syarat';

    protected $fillable = [
        'katalog_layanan_id',
        'jenis_syarat_id',
        'deskripsi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function layanan()
    {
        return $this->belongsTo(KatalogLayanan::class, 'katalog_layanan_id');
    }

    public function jenisSyarat()
    {
        return $this->belongsTo(JenisSyarat::class, 'jenis_syarat_id');
    }
}
