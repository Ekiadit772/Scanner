<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerubahanLayananPenutupan extends Model
{
    use SoftDeletes;

    protected $table = 'perubahan_layanan_penutupan';

    protected $fillable = [
        'perubahan_layanan_id',
        'tanggal_penyelesaian',
        'kesesuaian_hasil',
        'kesesuaian_penjelasan',
        'dampak_spbe',
        'dampak_spbe_penjelasan',
        'persetujuan_penyelesaian',
        'persetujuan_catatan',
        'kordinator_spbe',
        'kordinator_jabatan',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function perubahanLayanan()
    {
        return $this->belongsTo(PerubahanLayanan::class, 'perubahan_layanan_id');
    }
}
