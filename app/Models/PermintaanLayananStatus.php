<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanLayananStatus extends Model
{
    protected $table = 'permintaan_layanan_status';
    protected $fillable = ['permintaan_layanan_id', 'status_id', 'keterangan', 'created_by'];

    public function permintaan()
    {
        return $this->belongsTo(PermintaanLayanan::class, 'permintaan_layanan_id');
    }

    public function status()
    {
        return $this->belongsTo(StatusTransaksi::class, 'status_id');
    }
}
