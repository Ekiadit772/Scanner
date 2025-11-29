<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerubahanLayanan extends Model
{
    use SoftDeletes;

    protected $table = 'perubahan_layanan';

    protected $fillable = [
        'perangkat_daerah_pemohon_id',
        'perangkat_daerah_id',
        'penyedia_layanan_id',
        'layanan_id',
        'kategori_perubahan_id',
        'no_permohonan',
        'no_antrian',
        'tanggal',
        'unit_kerja_pemohon',
        'nama_pemohon',
        'nip_pemohon',
        'jabatan_pemohon',
        'judul_perubahan',
        'deskripsi',
        'is_in_peta_spbe',
        'jenis_perubahan',
        'latar_belakang',
        'tujuan_perubahan',
        'area_perubahan_ids',
        'jadwal_mulai',
        'jadwal_selesai',
        'is_downtime',
        'downtime',
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

    public function perubahanSyarat()
    {
        return $this->hasMany(PerubahanLayananSyarat::class, 'perubahan_layanan_id');
    }

    public function detailPerubahan()
    {
        return $this->hasMany(PerubahanLayananDetail::class, 'perubahan_layanan_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusTransaksi::class, 'status_id', 'id');
    }

    public function riwayatStatus()
    {
        return $this->hasMany(PerubahanLayananStatus::class, 'perubahan_layanan_id');
    }

    public function kategoriPerubahan()
    {
        return $this->belongsTo(KategoriPerubahan::class);
    }

    public function getAreaPerubahanTextAttribute()
    {
        if (empty($this->area_perubahan_ids)) {
            return '-';
        }

        $ids = explode(',', $this->area_perubahan_ids);

        return AreaPerubahan::whereIn('id', $ids)
            ->pluck('nama')
            ->implode(', ');
    }

    public function verifikasi()
    {
        return $this->hasOne(PerubahanLayananVerifikasi::class, 'perubahan_layanan_id');
    }

    public function pelaporan()
    {
        return $this->hasOne(PerubahanLayananPelaporan::class, 'perubahan_layanan_id');
    }
}
