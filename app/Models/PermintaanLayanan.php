<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermintaanLayanan extends Model
{
    use SoftDeletes;

    protected $table = 'permintaan_layanan';

    protected $fillable = [
        'perangkat_daerah_pemohon_id',
        'perangkat_daerah_id',
        'penyedia_layanan_id',
        'layanan_id',
        'no_antrian',
        'no_permohonan',
        'tanggal',
        'unit_kerja_pemohon',
        'nama_pemohon',
        'nip_pemohon',
        'jabatan_pemohon',
        'telepon_pemohon',
        'email_pemohon',
        'deskripsi_spek',
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

    public function permintaanSyarat()
    {
        return $this->hasMany(PermintaanLayananSyarat::class, 'permintaan_layanan_id');
    }

    public function detailPermintaan()
    {
        return $this->hasMany(PermintaanLayananDetail::class, 'permintaan_layanan_id', 'id');
    }

    public function status()
    {
        return $this->belongsTo(StatusTransaksi::class, 'status_id', 'id');
    }

    public function riwayatStatus()
    {
        return $this->hasMany(PermintaanLayananStatus::class, 'permintaan_layanan_id');
    }

    public function syaratKAK()
    {
        return $this->hasOneThrough(
            SyaratKak::class,
            PermintaanLayananSyarat::class,
            'permintaan_layanan_id',
            'permintaan_layanan_syarat_id',
            'id',
            'id'
        )->where('jenis_syarat_id', 2);
    }

    public function suratRekomendasi()
    {
        $fileName = 'surat-rekomendasi-' . $this->id . '.docx';
        $path = 'surat-rekomendasi/' . $this->id . '/' . $fileName;

        return Storage::disk('public')->exists($path) ? $path : null;
    }
}
