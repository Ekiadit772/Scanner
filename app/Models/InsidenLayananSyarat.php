<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsidenLayananSyarat extends Model
{
    use SoftDeletes;

    protected $table = 'insiden_layanan_syarat';

    protected $fillable = [
        'insiden_layanan_id',
        'layanan_syarat_id',
        'is_approve',
        'jenis_syarat_id',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function insidenLayanan()
    {
        return $this->belongsTo(InsidenLayanan::class, 'insiden_layanan_id');
    }

    public function layananSyarat()
    {
        return $this->belongsTo(LayananSyarat::class, 'layanan_syarat_id');
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
}
