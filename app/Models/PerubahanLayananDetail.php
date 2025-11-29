<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerubahanLayananDetail extends Model
{
    protected $table = 'perubahan_layanan_detail';

    protected $fillable = [
        'perubahan_layanan_id',
        'nama_item',
        'deskripsi_layanan',
        'banyaknya',
        'satuan',
    ];

    public function detailPerubahan()
    {
        return $this->hasMany(PerubahanLayananDetail::class, 'perubahan_layanan_id', 'id');
    }
}
