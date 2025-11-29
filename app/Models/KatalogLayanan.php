<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KatalogLayanan extends Model
{
    use SoftDeletes;

    protected $table = 'katalog_layanan';

    protected $fillable = [
        'perangkat_daerah_id',
        'penyedia_layanan_id',
        'kelompok_layanan_id',
        'jenis_layanan_id',
        'kode',
        'nama',
        'deskripsi',
        'status_id',
        'tahun',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];

    // protected $casts = [
    //     'sla' => 'array',
    //     'syarat' => 'array',
    // ];


    public function perangkatDaerah()
    {
        return $this->belongsTo(PerangkatDaerah::class);
    }

    public function penyediaLayanan()
    {
        return $this->belongsTo(PenyediaLayanan::class);
    }

    public function kelompokLayanan()
    {
        return $this->belongsTo(KelompokLayanan::class);
    }

    public function sla()
    {
        return $this->hasMany(LayananSla::class, 'katalog_layanan_id');
    }

    public function syarat()
    {
        return $this->hasMany(LayananSyarat::class, 'katalog_layanan_id');
    }

    public function manajemenPermintaan()
    {
        return $this->hasMany(PermintaanLayanan::class, 'katalog_layanan_id', 'id');
    }

    public function permintaanLayanan()
    {
        return $this->hasMany(PermintaanLayanan::class, 'layanan_id', 'id');
    }

    public function jenisLayanan(){
        return $this->belongsTo(JenisLayanan::class, 'jenis_layanan_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusTransaksi::class, 'status_id','id');
    }
}
