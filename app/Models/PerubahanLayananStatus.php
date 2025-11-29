<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerubahanLayananStatus extends Model
{
    protected $table = 'perubahan_layanan_status';
    protected $fillable = ['perubahan_layanan_id', 'status_id', 'keterangan', 'created_by'];

    public function perubahanLayanan()
    {
        return $this->belongsTo(PerubahanLayanan::class, 'perubahan_layanan_id');
    }

    public function status()
    {
        return $this->belongsTo(StatusTransaksi::class, 'status_id');
    }
}
