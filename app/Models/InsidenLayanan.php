<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsidenLayanan extends Model
{
    use SoftDeletes;

    protected $table = 'insiden_layanan';

    protected $fillable = [
        'perangkat_daerah_pemohon_id',
        'perangkat_daerah_id',
        'penyedia_layanan_id',
        'layanan_id',
        'jenis_insiden_id',
        'no_permohonan',
        'no_antrian',
        'tanggal',
        'unit_kerja_pemohon',
        'nama_pemohon',
        'nip_pemohon',
        'jabatan_pemohon',
        'telepon_pemohon',
        'email_pemohon',
        'keluhan',
        'penanganan_insiden',
        'perangkat_yang_diperlukan',
        'status_penanganan_insiden_id',
        'keterangan_pelaksanaan',
        'status_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    public function perangkatDaerah()
    {
        return $this->belongsTo(PerangkatDaerah::class);
    }

    public function perangkatPemohon()
    {
        return $this->belongsTo(PerangkatDaerah::class, 'perangkat_daerah_pemohon_id');
    }


    public function penyediaLayanan()
    {
        return $this->belongsTo(PenyediaLayanan::class);
    }

    public function layanan()
    {
        return $this->belongsTo(KatalogLayanan::class);
    }

    public function insidenSyarat()
    {
        return $this->hasMany(InsidenLayananSyarat::class, 'insiden_layanan_id');
    }

    public function detailInsiden()
    {
        return $this->hasMany(InsidenLayananDetail::class, 'insiden_layanan_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusTransaksi::class, 'status_id');
    }

    public function riwayatStatus()
    {
        return $this->hasMany(InsidenLayananStatus::class, 'insiden_layanan_id')
            ->orderBy('created_at', 'asc');
    }

    public function jenisInsiden()
    {
        return $this->belongsTo(JenisInsiden::class);
    }

    public function statusPenanganan()
    {
        return $this->belongsTo(StatusPenangananInsiden::class, 'status_penanganan_insiden_id', 'id');
    }
}
