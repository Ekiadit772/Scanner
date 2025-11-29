<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelompokLayanan extends Model
{
     protected $table = 'kelompok_layanan';

     use SoftDeletes;

     protected $fillable = [
          'nama',
          'deskripsi',
          'created_by',
          'updated_by',
          'deleted_by',
     ];

     public function layanan()
     {
          return $this->hasMany(KatalogLayanan::class, 'kelompok_layanan_id');
     }
}
