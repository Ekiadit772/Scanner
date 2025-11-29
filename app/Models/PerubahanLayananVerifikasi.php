<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerubahanLayananVerifikasi extends Model
{
    use SoftDeletes;

    protected $table = 'perubahan_layanan_verifikasi';

    protected $fillable = [
        'perubahan_layanan_id',
        'dampak_perubahan',
        'tingkat_dampak',
        'kesiapan_personil',
        'kesiapan_personil_catatan',
        'kesiapan_organisasi',
        'kesiapan_organisasi_catatan',
        'risiko_potensial',
        'rencana_mitigasi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function perubahanLayanan()
    {
        return $this->belongsTo(PerubahanLayanan::class, 'perubahan_layanan_id');
    }
}
