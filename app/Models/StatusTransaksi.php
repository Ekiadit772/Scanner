<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusTransaksi extends Model
{
    protected $table = 'status_transaksi';
    protected $fillable = ['jenis_transaksi', 'nama_status', 'keterangan', 'is_aktif'];
}
