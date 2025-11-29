<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KatalogLayananStatus extends Model
{
    protected $table = 'katalog_layanan_status';

    protected $fillable = [
        'katalog_layanan_id',
        'status_id',
        'keterangan',
        'created_by',
        'updated_by',
    ];

    public function katalogLayanan()
    {
        return $this->belongsTo(KatalogLayanan::class, 'katalog_layanan_id');
    }

    public function status()
    {
        return $this->belongsTo(StatusTransaksi::class, 'status_id');
    }
}
