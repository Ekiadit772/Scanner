<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerubahanLayananPelaporan extends Model
{
    use SoftDeletes;

    protected $table = 'perubahan_layanan_pelaporan';

    protected $fillable = [
        'perubahan_layanan_id',
        'tim_pelaksana',
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_rencana',
        'anggaran',
        'anggaran_catatan',
        'sumber_daya_lain',
        'sumber_daya_lain_catatan',
        'komunikasi_perubahan',
        'komunikasi_perubahan_catatan',
        'lainnya',
        'lainnya_catatan',
        'status_pelaksanaan',
        'langkah_implementasi',
        'catatan_khusus',
        'bukti_implementasi',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
}
