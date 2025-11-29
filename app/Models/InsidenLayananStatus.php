<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsidenLayananStatus extends Model
{
    protected $table = 'insiden_layanan_status';
    protected $fillable = ['insiden_layanan_id', 'status_id', 'keterangan', 'created_by'];

    public function insidenLayanan()
    {
        return $this->belongsTo(InsidenLayanan::class, 'insiden_layanan_id');
    }

    public function status()
    {
        return $this->belongsTo(StatusTransaksi::class, 'status_id');
    }
}
