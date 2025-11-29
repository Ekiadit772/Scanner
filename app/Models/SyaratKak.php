<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PermintaanLayananSyarat;

class SyaratKak extends Model
{
    use SoftDeletes;

    protected $table = 'syarat_kak';

    protected $fillable = [
        'permintaan_layanan_syarat_id',
        'perubahan_layanan_id',
        'insiden_layanan_syarat_id',
        'judul',
        'tahun',
        'jumlah_anggaran',
        'sumber_anggaran',
        'nama_ppk',
        'deskripsi',
        'file_pendukung',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function permintaanLayananSyarat()
    {
        return $this->belongsTo(PermintaanLayananSyarat::class);
    }

    public function insidenLayananSyarat()
    {
        return $this->belongsTo(InsidenLayananSyarat::class);
    }

    public function getPerangkatDaerahAttribute()
    {
        if ($this->permintaanLayananSyarat && $this->permintaanLayananSyarat->permintaanLayanan) {
            return $this->permintaanLayananSyarat->permintaanLayanan->perangkatDaerah;
        }

        if ($this->insidenLayananSyarat && $this->insidenLayananSyarat->insidenLayanan) {
            return $this->insidenLayananSyarat->insidenLayanan->perangkatDaerah;
        }

        return null;
    }
}
