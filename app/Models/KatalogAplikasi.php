<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KatalogAplikasi extends Model
{
    protected $table = 'katalog_aplikasi';

    use SoftDeletes;

    protected $fillable = [
        'kode',
        'nama_aplikasi',
        'nama_ppk',
        'perangkat_daerah_id',
        'rekomendasi_id',
        'pelaporan_id',
        'pengujian_id',
        'is_pentest',
        'is_integrasi',
        'is_hosting',
        'is_domain',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function rekomendasi()
    {
        return $this->belongsTo(PermintaanLayanan::class, 'rekomendasi_id')
            ->where('layanan_id', config('constants.LAYANAN_REKOMENDASI_ID'));
    }

    public function pelaporan()
    {
        return $this->belongsTo(PermintaanLayanan::class, 'pelaporan_id')
            ->where('layanan_id', config('constants.LAYANAN_PELAPORAN_ID'));
    }

    public function pengujian()
    {
        return $this->belongsTo(PermintaanLayanan::class, 'pengujian_id')
            ->where('layanan_id', config('constants.LAYANAN_PENGUJIAN_ID'));
    }

    public function perangkatDaerah()
    {
        return $this->belongsTo(PerangkatDaerah::class, 'perangkat_daerah_id');
    }
}
