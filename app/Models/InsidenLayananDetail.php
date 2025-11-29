<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsidenLayananDetail extends Model
{
    protected $table = 'insiden_layanan_detail';

    protected $fillable = [
        'insiden_layanan_id',
        'nama_item',
        'deskripsi_layanan',
        'banyaknya',
        'satuan',
    ];

    public function detailInsiden()
    {
        return $this->hasMany(InsidenLayanan::class, 'insiden_layanan_id', 'id');
    }
}
