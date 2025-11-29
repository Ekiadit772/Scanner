<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayananSla extends Model
{
    use SoftDeletes;

    protected $table = 'katalog_layanan_sla';

    protected $fillable = [
        'katalog_layanan_id',
        'nama',
        'satuan',
        'nilai',
        'deskripsi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function layanan()
    {
        return $this->belongsTo(KatalogLayanan::class, 'katalog_layanan_id');
    }
}
