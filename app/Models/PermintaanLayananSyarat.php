<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermintaanLayananSyarat extends Model
{
    use SoftDeletes;

    protected $table = 'permintaan_layanan_syarat';

    protected $fillable = [
        'permintaan_layanan_id',
        'layanan_syarat_id',
        'is_approve',
        'jenis_syarat_id',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function permintaanLayanan()
    {
        return $this->belongsTo(PermintaanLayanan::class, 'permintaan_layanan_id');
    }

    public function layananSyarat()
    {
        return $this->belongsTo(LayananSyarat::class, 'layanan_syarat_id');
    }

    public function jenisSyarat()
    {
        return $this->belongsTo(JenisSyarat::class, 'jenis_syarat_id');
    }

    public function syaratKak()
    {
        return $this->hasOne(SyaratKak::class);
    }

    public function syaratIdentitasAplikasi()
    {
        return $this->hasOne(SyaratIdentitasAplikasi::class);
    }

    public function syaratSuratRekomendasi()
    {
        return $this->hasOne(SyaratSuratRekomendasi::class);
    }

    public function syaratDokumenTeknisPengembangan()
    {
        return $this->hasOne(SyaratDokumenTeknisPengembangan::class);
    }

    public function syaratDokumenPengujian()
    {
        return $this->hasOne(SyaratDokumenPengujian::class);
    }

    public function syaratNda()
    {
        return $this->hasOne(SyaratNda::class);
    }

    public function syaratLainnya()
    {
        return $this->hasOne(SyaratLainnya::class);
    }
    
    public function suratRekomendasi()
    {
        return $this->hasOne(SyaratSuratRekomendasi::class, 'permintaan_layanan_syarat_id');
    }
}
