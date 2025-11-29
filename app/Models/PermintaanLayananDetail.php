<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanLayananDetail extends Model
{
    protected $table = 'permintaan_layanan_detail';

    protected $fillable = [
        'permintaan_layanan_id',
        'nama_item',
        'deskripsi_layanan',
        'banyaknya',
        'satuan',
    ];

    public function manajemenPermintaan()
    {
        return $this->hasMany(PermintaanLayanan::class, 'permintaan_layanan_id', 'id');
    }
}
