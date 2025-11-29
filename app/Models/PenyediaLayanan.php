<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenyediaLayanan extends Model
{
    protected $table = 'penyedia_layanan';

    use SoftDeletes;

    protected $fillable = [
        'perangkat_daerah_id',
        'nama_bidang',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function peranPenyedia()
    {
        return $this->hasMany(PeranPenyedia::class, 'penyedia_layanan_id');
    }

    public function perangkatDaerah()
    {
        return $this->belongsTo(PerangkatDaerah::class, 'perangkat_daerah_id');
    }
}
