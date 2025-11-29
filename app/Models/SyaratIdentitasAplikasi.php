<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PermintaanLayananSyarat;

class SyaratIdentitasAplikasi extends Model
{
    use SoftDeletes;

    protected $table = 'syarat_identitas_aplikasi';

    protected $fillable = [
        'permintaan_layanan_syarat_id',
        'perubahan_layanan_syarat_id',
        'insiden_layanan_syarat_id',
        'nama_aplikasi',
        'pemilik_aplikasi',
        'versi',
        'deskripsi',
        'url_aplikasi',
        'username_aplikasi',
        'password_aplikasi',
        'file_pendukung',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function permintaanLayananSyarat()
    {
        return $this->belongsTo(PermintaanLayananSyarat::class);
    }
}
