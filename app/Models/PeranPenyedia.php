<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PeranPenyedia extends Model
{
     protected $table = 'peran_penyedia';

     use SoftDeletes;

     protected $fillable = [
          'penyedia_layanan_id',
          'jenis_peran_id',
          'nip',
          'nama',
          'jabatan',
          'created_by',
          'updated_by',
          'deleted_by',
     ];

     public function penyediaLayanan()
     {
          return $this->belongsTo(PenyediaLayanan::class, 'penyedia_layanan_id');
     }

     public function jenisPeran()
     {
          return $this->belongsTo(JenisPeran::class, 'jenis_peran_id');
     }
}
