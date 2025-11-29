<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCatalog extends Model
{
    protected $fillable = [
        'nama_pemohon',
        'nip',
        'nama_instansi',
        'unit_kerja',
        'no_permohonan',
        'tanggal',
        'penyedia_layanan',
        'bidang',
        'nama_layanan',
        'deskripsi_spek',
        'persyaratan',
        'nama_item',
        'deskripski_layanan',
        'banyaknya',
        'satuan',
    ];

    protected $casts = [
        'persyaratan' => 'array',
    ];
}
